<?php 
/*Параметры для БД*/
	$PDO_SETTINGS = array(
		'host' => 'databasehost',
		'db' => 'databasename',
		'user' => 'dbuser',
		'password' => 'dbpassword',
		'charset' => 'dbcharset'
	);
	$DSN = "mysql:host=$PDO_SETTINGS[host];dbname=$PDO_SETTINGS[db];charset=$PDO_SETTINGS[charset]";
	$OPTIONS = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$pdo = new PDO($DSN, $PDO_SETTINGS['user'], $PDO_SETTINGS['password'], $OPTIONS);
?>
