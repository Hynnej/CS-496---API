<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
var_dump($method);
var_dump($request);
$doc = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));

	foreach($request as $stuff)
	{ 
		$data= explode('=', $stuff);
	var_dump($data);
	}
?>

