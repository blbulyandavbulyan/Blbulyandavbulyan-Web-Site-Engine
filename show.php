<?php  require_once $_SERVER['DOCUMENT_ROOT'] . 'config/config.php'; require_once $_SERVER['DOCUMENT_ROOT'] . 'config/db.php'; require_once $_SERVER['DOCUMENT_ROOT'] . 'lib/functions.php'; ?>
<?php if((isset($_GET['category_id']) && !((filter_var($_GET['category_id'], FILTER_VALIDATE_INT) === 0) || filter_var($_GET['category_id'], FILTER_VALIDATE_INT)))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset;?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		
		<title>Неверные входные данные!</title>	
	</head>
	<body>
		<div id="invalid_parameter" class="error">В качестве ИД категории можно передавать только число, мамкин ты хацкер!</div>
	</body>
</html>
<?php exit;?>
<?php elseif((isset($_GET['category_id']) && (filter_var($_GET['category_id'], FILTER_VALIDATE_INT) || filter_var($_GET['category_id'], FILTER_VALIDATE_INT) === 0))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset;?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		<?php 
			echo GenerateHeadForPageWithCategory($pdo, (int)$_GET['category_id']);
		?>
	</head>
	<body>
		<?php
			if($show_nav === true){
				echo "<nav class=\"nav\">" . "<h3><a href=\"" . $index_file_href . "\"><b>Главная страница</b></a></h3> <span class=\"spa\">|</span> " . GenerateCategoryOutput($pdo, "SELECT * FROM `categories` WHERE `CategoryID` != :CategoryID", array('CategoryID' => (int)$_GET['category_id'])) . "</nav>";
			}
			echo GenerateCategoryAndChannelsOutput($pdo, (int)$_GET['category_id']);
		?>
		<?php 
			if($show_footer === true){
				echo "<footer class=\"pd\" id=\"pd3\">" . $footer_value . "</footer>";
			}
		?>
	</body>
</html>
<?php elseif((isset($_GET['subcategory_id']) && !((filter_var($_GET['subcategory_id'], FILTER_VALIDATE_INT) === 0) || filter_var($_GET['subcategory_id'], FILTER_VALIDATE_INT)))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset;?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		
		<title>Неверные входные данные!</title>		
	</head>
	<body>
		<div id="invalid_parameter" class="error">В качестве ИД подкатегории можно передавать только число, мамкин ты хацкер!</div>
	</body>
</html>
<?php exit;?>
<?php elseif((isset($_GET['subcategory_id']) && ((filter_var($_GET['subcategory_id'], FILTER_VALIDATE_INT) === 0) || filter_var($_GET['subcategory_id'], FILTER_VALIDATE_INT)))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset;?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		<?php echo GenerateHeadForPageWithSubcategory($pdo, $_GET['subcategory_id']);?>
	</head>
	<body>
		<?php
			echo GenerateSubcategoryAndChannelsOutput($pdo, $_GET['subcategory_id']);
		?>
		<?php 
			if($show_footer === true){
				echo "<footer class=\"pd\" id=\"pd3\">" . $footer_value . "</footer>";
			}
		?>
	</body>
</html>
<?php exit;?>
<?php elseif((isset($_GET['subsubcategory_id']) && !((filter_var($_GET['subsubcategory_id'], FILTER_VALIDATE_INT) === 0) || filter_var($_GET['subsubcategory_id'], FILTER_VALIDATE_INT)))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset;?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		
		<title>Неверные входные данные!</title>	
	</head>
	<body>
		<div id="invalid_parameter" class="error">В качестве ИД подподкатегории можно передавать только число, мамкин ты хацкер!</div>
	</body>
</html>
<?php exit;?>
<?php elseif((isset($_GET['subsubcategory_id']) && ((filter_var($_GET['subsubcategory_id'], FILTER_VALIDATE_INT) === 0) || filter_var($_GET['subsubcategory_id'], FILTER_VALIDATE_INT)))):?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		<?php echo GenerateHeadForPageWithSubsubcategory($pdo, $_GET['subsubcategory_id']);?>
	</head>
	<body>
		<?php echo GenerateSubsubcategoryAndChannelsOutput($pdo, $_GET['subsubcategory_id']);?>
		<?php 
			if($show_footer === true){
				echo "<footer class=\"pd\" id=\"pd3\">" . $footer_value . "</footer>";
			}
		?>
	</body>
</html>
<?php exit;?>
<?php else:?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="<?php echo $default_charset?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $root_style_file_href;?>">
		
		<title>Отсутствуют необходимые параметры</title>	
	</head>
	<body>
		<div id="missing_required_input" class="error">Отсутствуют необходимые GET параметры!</div>
	</body>
</html>
<?php endif;?>
