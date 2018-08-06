<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php 
			require $_SERVER['DOCUMENT_ROOT'] '/config/db.php';
 
			if(isset($_POST['submit'])){
				if($db->connect_errno){/*Проверка на ошибки подлючения к БД*/
					$error_text = "<div class=\"error\" id=\"error_connection\">Исключение в функции GenerateCategoryOutput: не удалось подключиться к MySQL: (" . $db->connect_errno . ") " . $db->connect_error . "</div>";
					die($error_text);
				}
				$category_name = $_POST['category-name'];
				$subcategory_name = $_POST['subcategory-name'];
				$subsubcategory_name = $_POST['subsubcategory-name'];
				$channel_name = $_POST['channel-name'];
				$channel_description = $_POST['channel-description'];
				$channel_link = $_POST['channel-link'];
				/*Подготовка SQL запросов*/
				
				$PRE_category_id = $db->stmt_init();
				$PRE_subcategory_id = $db->stmt_init();
				$PRE_subsubcategory_id = $db->stmt_init();
				$PRE_add_channel = $db->stmt_init();
				// для добавления канала требуеться знать ИД категории, ИД подкатегории , ИД подподкатегории
				
				$PRE_category_id->prepare("SELECT `CategoryID` FROM `categories` WHERE `CategoryName` = ?");//sql запрос для получения ИД категории по её имени
				$PRE_subcategory_id->prepare("SELECT `SubcategoryID` FROM `subcategories` WHERE `SubcategoryName` = ?");//sql запрос для получения ИД подкатегории по её имени
				$PRE_subsubcategory_id->prepare("SELECT `SubsubcategoryID`  FROM `subsubcategories` WHERE `SubsubcategoryName` = ?");//sql запрос для получения ИД подподкатегории по её имени
				$PRE_add_channel->prepare("INSERT INTO `channels`(`CategoryID`, `SubcategoryID`, `SubsubcategoryID` ,`ChannelName`, `ChannelLink`, `Description`)VALUES(?, ?, ?, ?, ?, ?)");// sql запрос для добавления канала 
				// для добавления канала требуеться знать ИД категории, ИД подкатегории , ИД подподкатегории
				$PRE_category_id->bind_param('s', $category_name);
				$PRE_subcategory_id->bind_param('s', $subcategory_name);
				$PRE_subsubcategory_id->bind_param('s', $subsubcategory_name);
				$PRE_category_id->execute();
				$PRE_category_id->closeCursor();
				$PRE_subcategory_id->execute();
				$PRE_subcategory_id->closeCursor();
				$PRE_subsubcategory_id->execute();
				$PRE_subsubcategory_id->closeCursor();
				$PRE_category_id->bind_result($category_id);
				$PRE_subcategory_id->bind_result($subcategory_id);
				$PRE_subsubcategory_id->bind_result($subsubcategory_id);
				$PRE_category_id->fetch();
				$PRE_subcategory_id->fetch();
				$PRE_subsubcategory_id->fetch();
				$PRE_add_channel->bind_param('iisss', $category_id, $subcategory_id, $subsubcategory_id, $channel_name, $channel_link, $channel_description);
				$PRE_add_channel->execute();
				$PRE_add_channel->fetch();
			}
		?>
		<form method="POST">
			<input type="text" name="category-name" placeholder="Имя категории">
			<input type="text" name="subcategory-name" placeholder="Имя подкатегории">
			<input type="text" name="subsubcategory-name" placeholder="Имя подподкатегории">
			<input type="text" name="channel-name" placeholder="Имя канала">
			<input type="text" name="channel-description" placeholder="Описание канала">
			<input type="text" name="channel-link" placeholder="Ссылка на канал">
			<input type="submit" name="submit" placeholder="Добавить">
		</form>
	</body>
</html>