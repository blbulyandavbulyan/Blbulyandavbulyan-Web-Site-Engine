<?php require __DIR__ . '/config/db.php'; require __DIR__ . '/lib/functions.php'; ?>
<?php if(!filter_var($_GET['category_id'], FILTER_VALIDATE_INT)):?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link type="text/css" rel="stylesheet" href="/Style/CSS/main.css">
		
		<title>Давай досвидания!</title>	
	</head>
	<body>
		<?php echo("<div id=\"invalid_parameter\" class=\"error\">Сюда можно передавать только число, мамкин ты хацкер!</div>");?>
	</body>
</html>
<?php exit;?>
<?php else:?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link type="text/css" rel="stylesheet" href="/Style/CSS/main.css">
		<?php 
			if(isset($_GET['category_id'])){
				echo GenerateHeadForPageWithCategory($pdo, (int)$_GET['category_id']);
			}
		?>
	</head>
	<body>
		<?php
		if(isset($_GET['category_id'])){
			echo "<nav class=\"nav\">" . "<h3><a href=\"/index.php\"><b>Главная страница</b></a></h3> <span class=\"spa\">|</span> " . GenerateCategoryOutputSecure($pdo, "SELECT * FROM `categories` WHERE `CategoryID` != :CategoryID", array('CategoryID' => (int)$_GET['category_id'])) . "</nav>" . GenerateCategoryAndChannelsOutputSecure($pdo, (int)$_GET['category_id']);
			}
		?>
		<footer class="pd">© Давид Блбулян<br>blbulyandavbulyan@gmail.com</footer>
	</body>
</html>
<?php endif;?>
