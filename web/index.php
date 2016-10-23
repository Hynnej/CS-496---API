<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
var_dump($request);
$doc = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));

if($method == "GET")
{
	if($doc == "team")
	{	
		$name = explode('=', $request[0]);
		echo $name[0] . " " . $name[1];
	}
	
	if($doc == "player")
}


?>

