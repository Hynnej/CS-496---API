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
	//parses the strings to form keys and values
	foreach($request as $stuff)
	{ 
		$tmp = explode('=', $stuff);
		$key[] = $tmp[0];
		$value[] = $tmp[1];
	}
	
	//combines keys and arrays into single array
	$data = array_combine($key, $value);
	//proccesses the get requests
	if($method == "GET")
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
					echo "No team with that name was found";
			}
			
			//error message if no team name was given
			else
				echo "no team name was given.";
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
					echo "No player with that name was found";
			}
			
			//error message if no player name was given
			else
				echo "no player name was given.";
		}


		
		else
			echo "you must specify a correct collection.";
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
					echo "The team name must be unique. Entry not added.";
				}
				
				else
				{	
					$addTeam = array(
					'name' => $data['name'],
					'division' => $data['division']);
						
					$teams->insertOne($addTeam);
					echo "team was added";
				}
			}
			
			else
				echo "Document not saved.  Be sure you have entered team name and division.";
				
		}
		
		if($doc == "player")
		{
			if($data['fname'] && $data['lname'] && $data['position'] && $data['team'])
			{
				$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
				$unique = $players->findOne($query);	
				
				if($unique)		
				{
					echo "The player first and last name combo must be unique. Entry not added.";
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
							
						$teams->insertOne($addPlayer);
						echo "Player was added";
					}
					
					else
						echo "team has not been entered yet.  Please add team first.";
				}	
			}
			
			else
				echo "Document not saved.  Be sure you have entered player first name, last name, position and team.";
				
		}
	}	
	
	else if($method == "DELETE")
	{
		if($doc == "player")
		{
			if($data['fname'] && $data['lname'])
			{
				$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
				$delPlayer = $players->findOne($query);	

				if($delPlayer)
				{
					$players->remove($delPlayer);
				}	
				//error message if player name was not found
				else
					echo "No player with that name was found";
			}
			
			//error message if no player name was given
			else
				echo "no player name was given.";
		}
			
	}			
	
	
?>

