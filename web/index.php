<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
var_dump($method);
var_dump($request);
$doc = preg_replace('/[^a-z0-9_]+/i','', array_shift($request));

	$data= explode('=', $request);
	var_dump($data);

?>

