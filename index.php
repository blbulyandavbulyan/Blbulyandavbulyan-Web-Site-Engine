<!DOCTYPE html>
<html id="htm" lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Список каналов и сайтов.</title>
		<link type="text/css" rel="stylesheet" href="/Style/CSS/main.css">
		<link type="text/css" rel="stylesheet" href="/Style/CSS/index.css">
		<link rel="icon" href="Style/Icon/favicon.ico" type="image/x-icon">
		<link rel="shortcut icon" href="/Style/Icon/favicon.ico" type="image/x-icon">
	</head>
	<body id="body3">
		<nav class="nav">
			<?php require 'config/db.php'; require 'lib/functions.php';
				echo  GenerateCategoryOutputSecure($pdo, "SELECT * FROM `categories` WHERE :param", array('param' => 1));
			?>
		</nav>
		<footer class="pd" id="pd3">© Давид Блбулян<br>blbulyandavbulyan@gmail.com</footer>
	</body>
</html>