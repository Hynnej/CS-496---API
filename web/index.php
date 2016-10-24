<?php
	require('../vendor/autoload.php');
	//connects to mongodb hosted at mlabs
	$uri = "mongodb://sirmiq:door5454@ds048719.mlab.com:48719/playerteam";
	$client = new MongoDB\Client($uri);
	$db = $client->playerteam;
	$teams = $db->team;


	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
	//var_dump($method);
	//var_dump($request);
	$doc = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));
	foreach($request as $stuff)
	{ 
		$tmp = explode('=', $stuff);
		$key[] = $tmp[0];
		$value[] = $tmp[1];
	}
	
	$data = array_combine($key, $value);
	

				//$teaminfo = $teams->find();
			var_dump($doc);

	/*if($method == "GET")
	{
		if($doc == "team")
		{	

			//$query = array('name' => 'seattle_seahawks');
			//$retTeam = $teams->findOne($query);	
			//$teamInfo = ['id' => retTeam['id'], 'name' => retTeam['name'], 'division' => retTeam['division']]; 
			//header('Content-type: application/json');
			//echo json_encode($teamInfo);
		}
		
		else if($doc == "player")
		{
			$query = array($and,'fname' => $data['fname'], 'lname' => $data['lname']);
			$retPlayer = $teams->findOne($query);	
			$teamInfo = ['id' => retPlayer['id'], 'fname' => retPlayer['fname'], 'lname' => retPlayer['lname'], 'position' => retPlayer['position'], 'team' => retPlayer['team']]; 
			header('Content-type: application/json');
			echo json_encode($data);
		}
		
		else
			echo "you must specify a correct collection."
	}*/
	
/*	else if($method == "POST")
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
						'division' => $data['name'],
						'name' => $data['division']);
						
					$teams->insert($addTeam);
					echo "team was added";
				}
			}
			
			else
				echo "Document not saved.  Be sure you have entered league, division name and website.";
				
		}	
	}
	*/
	
?>

