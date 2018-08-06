<?php  require_once $_SERVER['DOCUMENT_ROOT'] . 'config/config.php'; require_once $_SERVER['DOCUMENT_ROOT'] . 'config/db.php'; require_once $_SERVER['DOCUMENT_ROOT'] . 'lib/functions.php'; ?>
<!DOCTYPE html>
<html id="htm" lang="ru">
	<head>
		<meta charset="<?php echo $default_charset?>">
		<title><?php echo $index_style_title ?></title>	
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $index_style_file_href ?>">
		<link rel="icon" href="<?php echo $index_icon_href ?>" type="image/x-icon">
		<link rel="shortcut icon" href="<?php echo $index_icon_href ?>" type="image/x-icon">
	</head>
	<body id="body3">
		<nav class="nav">
			<?php
				echo GenerateCategoryOutput($pdo, "SELECT * FROM `categories` WHERE :param", array('param' => 1));
			?>
		</nav>
		<?php if($show_footer === true):?>
		<footer class="pd" id="pd3"><?php echo $footer_value ?></footer>
		<?php endif;?>
	</body>
</html>