<?php
	require('../vendor/autoload.php');
	
	//connects to mongodb hosted at mlabs
	$uri = "mongodb://sirmiq:door5454@ds048719.mlab.com:48719/playerteam";
	$client = new MongoDB\Client($uri);
	$db = $client->playerteam;
	$teams = $db->team;
	$players = $db->player;
	
	//gathers data sent
	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
	$doc = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));


	if($method == "POST")
	{
		 $data = json_decode(file_get_contents("php://input"), true);
	}	
	
	else
	{	
		//parses the strings to form keys and values
		foreach($request as $stuff)
		{ 
			$tmp = explode('=', $stuff);
			$key[] = $tmp[0];
			$value[] = $tmp[1];
		}

		//combines keys and arrays into single array
		$data = array_combine($key, $value);

	}
	//proccesses the get requests
	/*if($method == "GET")
	{
		if($doc == "team")
		{	
			if($data['name'])
			{
				$query = array('name' => $data['name']);
				$retTeam = $teams->findOne($query);	
				
				if($retTeam)
				{
					header('Content-type: application/json');
					echo json_encode($retTeam);
				}	
				//error message if team name was not found
				else
				{
					$response = array("response" => "No team with that name was found");
					header('Content-type: application/json');
					echo json_encode((object)$response);
				}
			}
			
			//error message if no team name was given
			else
			{
				$response = array("response" => "No team name was given");
					header('Content-type: application/json');
					echo json_encode((object)$response);
			}
		}
		
		else if($doc == "player")
		{	
			if($data['fname'] && $data['lname'])
			{
				$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
				$retPlayer = $players->findOne($query);	
				
				if($retPlayer)
				{
					header('Content-type: application/json');
					echo json_encode($retPlayer);
				}	
				//error message if player name was not found
				else
				{
					$response = array("response" => "No player with that name found");
					header('Content-type: application/json');
					echo json_encode((object)$response);
				}	
			}
			
			//error message if no player name was given
			else
				{
					$response = array("response" => "No player name was given");
					header('Content-type: application/json');
					echo json_encode((object)$response);
				}
		}
	
		else if($doc == "teamList")
		{
			$teamList = $teams->find();
			
			foreach($teamList as $team)
			{
				$list[] = $team["name"];
			}
			
			echo implode("\n", $list);
		}	
		
		else if($doc == "roster")
		{
			$query = array('team' => $data['team']);
			$roster = $players->find($query);
			
			foreach($roster as $member)
			{
				$rosterMember = $member['fname'] . " " . $member['lname'] . "-" . $member['position'];
				$list[] = $rosterMember;
			}
			
			echo implode("\n", $list);
		}
		
		else
		{
			$response = array("response" => "You must specify a correct collection.");
			header('Content-type: application/json');
			echo json_encode((object)$response);
		}
	}
	
	//processes post requests
	else if($method == "POST")
	{
		if($doc == "team")
		{
			if($data['name'] && $data['division'])
			{
				$query = array('name' => $data['name']);
				$unique = $teams->findOne($query);	
				
				if($unique)
				{
					$response = array("response" => "The team name must be unique.  Entry not added.");
					header('Content-type: application/json');
					echo json_encode((object)$response);
				}				
				
				else
				{	
					$addTeam = array(
					'name' => $data['name'],
					'division' => $data['division']);
						
					$teams->insertOne($addTeam);
					$response = array('response' => 'team was added');
					header('Content-type: application/json');

					echo json_encode($respone);
					
				}
			}
			
			else
			{
				$response = array("response" => "Document not saved. Be sure you ahve entered a team name and division.");
				header('Content-type: application/json');
				echo json_encode((object)$response);
			}
				
		}
		
		else if($doc == "player")
		{
			if($data['fname'] && $data['lname'] && $data['position'] && $data['team'])
			{
				$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
				$unique = $players->findOne($query);	
				
				if($unique)		
				{
					$response = array("response" => "The team name must be unique.  Entry not added.");
					header('Content-type: application/json');
					echo json_encode((object)$response);
				}	
				
				else
				{
					$query = array('name' => $data['team']);
					$retTeam = $teams->findOne($query);	
					
					if($retTeam)
					{
						$addPlayer = array(
						'fname' => $data['fname'],
						'lname' => $data['lname'],
						'position' => $data['position'],
						'team' => $data['team']
						);
							
						$players->insertOne($addPlayer);
								
						$response = array("response" => "Player added.");
						header('Content-type: application/json');
						echo json_encode((object)$response);
					}
					
					else
					{
						$response = array("response" => "Team has not been entered yet.  Please add team first.");
						header('Content-type: application/json');
						echo json_encode((object)$response);
					}	
				}	
			}
			
			else
			{
				$response = array("response" => "Document not saved.  Be sure you have entered player first name, last name, position and team.");
				header('Content-type: application/json');
				echo json_encode((object)$response);
			}					
		}
		
		else
		{
			$response = array("response" => "You must specify a correct collection.");
			header('Content-type: application/json');
			echo json_encode((object)$response);
		}
	}	
	
	else if($method == "DELETE")
	{		
		if($doc == "team")
		{
			if($data['name'])
			{
				$query = array('name' => $data['name']);
				$delTeam = $teams->findOne($query);	
				
				if($delTeam)
				{
					$teams->deleteOne($delTeam);
				response = array("response" => "Team was deleted.");
				header('Content-type: application/json');
				echo json_encode((object)$response);
				}
				//error message if player name was not found
				else
				{	
					$response = array("response" => "No team with that name was found.");
					header('Content-type: application/json');
					echo json_encode((object)$response);	
				}
			
			//error message if no player name was given
			else
			{	
				$response = array("response" => "No team name was given.");
				header('Content-type: application/json');
				echo json_encode((object)$response);	
			}	
		}
		else if($doc == "player")
		{
			if($data['fname'] && $data['lname'])
			{
				$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
				$delPlayer = $players->findOne($query);	
				if($delPlayer)
				{
					$players->deleteOne($delPlayer);
					{	
					$response = array("response" => "Player was deleted.");
					header('Content-type: application/json');
					echo json_encode((object)$response);	
					}
				}	
				//error message if player name was not found
				else
				{	
					$response = array("response" => "No player with that name was found.");
					header('Content-type: application/json');
					echo json_encode((object)$response);	
				}	
			}
			
			//error message if no player name was given
			else
			{	
				$response = array("response" => "No player name was given.");
				header('Content-type: application/json');
				echo json_encode((object)$response);	
			}	
		}

		else
		{
			$response = array("response" => "You must specify a correct collection.");
			header('Content-type: application/json');
			echo json_encode((object)$response);
		}		
		
	}*/			
	
?>


