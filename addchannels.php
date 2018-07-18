<!DOCTPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Добавления каналов</title>
	</head>
	<body>
		<h2>Для добавления каналов загрузите HTML файл</h2>
		<form method="POST" enctype="multipart/form-data">
			<input type="file" name="HTMLfile" />
			<input type="submit" name="submit" value="Загрузить" />
		</form>
		<?php
			 /*
			Copyright Blbulyan Software 
			This software is distributed under the GNU GPL v2
			This software was developed by one Russian schoolboy.
			All proposals for its development are accepted, criticism is welcomed.
			*/
			/* This script is designed to add channels to the database */
			/*Этот скрипт предназначен для добавления каналов в базу данных*/
			
				/*Подключение основных файлов для работы скрипта*/
				require __DIR__ .'/config/db.php';// файл с объектом PDO
				/*подключение файлов касающихся DiDom*/
				require __DIR__ .'/lib/DiDom/Document.php';
				require __DIR__ .'/lib/DiDom/Element.php';
				require __DIR__ .'/lib/DiDom/Encoder.php';
				require __DIR__ .'/lib/DiDom/Errors.php';
				require __DIR__ .'/lib/DiDom/Query.php';
				require __DIR__ . '/lib/DiDom/StyleAttribute.php';
				require __DIR__ . '/lib/DiDom/ClassAttribute.php';
				require __DIR__ . '/lib/DiDom/Exceptions/InvalidSelectorException.php';
				/*конец подключения файлов касающихся DiDom*/
				/*конец подключения основных файлов для работы скрипта*/
				/*Импорт пространств имён библиотеки DiDom*/
				use DiDom\Document;
				use DiDom\Query;
				use DiDom\Element;
				/* конец импорта пространств имён библиотеки DiDom*/
				if(isset($_POST['submit'])){// если была нажата кнопка submit
					$extension_info = pathinfo($_FILES['HTMLfile']['name'], PATHINFO_EXTENSION);
					if(!($extension_info == 'html')){
						$error_text = "<div class=\"extension_info\">" . "Ваше расширение: " . $extension_info . "</div>" . "<div class=\"error\">Неверный тип файла, сюда можно загружать тлько HTML файлы! (расширение должно быть .html)</div>";
						die($error_text);
					}
					$dom = new Document($_FILES['HTMLfile']['tmp_name'], true);// создание элемента типа Document
					/*Подготовка запросов*/
					$PRE_category_id = $pdo->prepare("SELECT `CategoryID` FROM `categories` WHERE `CategoryName` = :category_name");// готовим запрос для выбора ид категории по её имени
					$PRE_add_category = $pdo->prepare("INSERT INTO `categories`(`CategoryName`,  `CategoryLink`, `CategoryDescription`, `HTMLClass`, `HTMLid`, `CategoryIconHref`, `CategoryStyleHref`)
						VALUES(:CategoryName, :CategoryLink, :CategoryDescription, :HTMLClass, :HTMLid, :CategoryIconHref, :CategoryStyleHref)");// подготовка запроса для добавления категории
					$PRE_add_subcategory = $pdo->prepare("INSERT INTO `subcategories` (`CategoryID`, `SubcategoryName`, `SubcategoryLink`, `HTMLClass`, `HTMLid`, `HRClass`, `HRid`)
					VALUES(:CategoryID, :SubcategoryName, :SubcategoryLink, :HTMLClass, :HTMLid, :HRClass, :HRid)");// готовим запрос для добавления подкатегории
					$PRE_add_subsubcategory = $pdo->prepare("INSERT INTO `subsubcategories`(`SubcategoryID`, `CategoryID`, `SubsubcategoryName`, `SubsubcategoryLink`, `HTMLClass`, `HTMLid`, `HRClass`, `HRid`)
					VALUES(:SubcategoryID, :CategoryID, :SubsubcategoryName, :SubsubcategoryLink, :HTMLClass, :HTMLid, :HRClass, :HRid)");// готовим запрос для боавления подподкатегории
					$PRE_update_category_link = $pdo->prepare("UPDATE `categories` SET `CategoryLink` = :CategoryLink WHERE `CategoryID` = :CategoryID");//подготовка запроса для обновления ссылки на категорию
					
					/*Получение имени категории */
					$h1 =  $dom->find('h1'); // поиск категории на странице (на одной странице может быть только одна категория вернего уровня)
					$category_name = "";// в этой переменной храниться имя категории
					foreach($h1 as $row){// цикл для перебара категорий верхнего уровня, сразу же завершаеться после первого исполнения
						$category_name = $row->text();
						break;
					}
					$category_name = str_replace(":", "", $category_name);// убираем двоеточие из значения тега h2 (в базе категория храниться без двоеточия)
					/*Начало добавления категории*/
					$metatag = $dom->find('head meta');
					$category_description = "";// описание категори
					/* echo "dump meta"; */
					/* print_r($metatag); */
					foreach($metatag as $mt){// цикл перебора метатегов
						$category_description = $mt->attr('content');// получение описания категории из атрибута content тега meta
						break;
					}
					$category_html_class = "";// в этой переменной храниться HTML класс категории
					$category_html_id = "";// в этой переменной храниться HTML ИД категории
					$category_style_href = "";// ссылка на файл стилей для категории
					$category_icon_href = "";// ссылка на файл иконки для категории
					foreach($h1 as $row){
						if($row->hasAttribute('class')){// проверка существует ли атрибут class у тега h1
							//тут код для получения HTMl класа категории
							$category_html_class = $row->attr('class');
						}
						else{
							$category_html_class = "default_category_class";
						}
						if($row->hasAttribute('id')){// проверка существует ли атрибут id у тега h1
							//тут код для получения HTML ид категории
							$category_html_id = $row->attr('id');
							// так же нужно проверить существуют ли в базе категории с таким ид, если да то сделать так чтобы $category_html_id
							// не совпадал с тем что лежит в базе
						}
						else{// если отсутствовует атрибут id у тега h1
							// тогда пишем в качестве HTML ид NONE
							$category_html_id = "NONE";
						}
						break;
					}
					$TagsLink = $dom->find('head link');// ищем тег link 
					$CountTagsLink = count($TagsLink);// количество найденых тегов link
					/* echo "CountTagsLink: $CountTagsLink<br>"; */
					if($CountTagsLink === 2){// если количество найденых тегов link равно двум
						foreach($TagsLink as $TagLink){// цикл перебора тегов link
							$TagLinkAttrType = $TagLink->attr('type');// получение значения атрибута type
							$TagLinkAttrHref = $TagLink->attr('href');// получение значения атрибута href
							if($TagLinkAttrType == "text/css"){// если атрибут type равен "text/css" (этот тип ставиться когда подключаеться файл стилей)
								// код для получения ссылки на файл стилей для категории
								$category_style_href = $TagLinkAttrHref;// получаем ссылку на файл стилей
							}
							if($TagLinkAttrType  == "image/x-icon"){// если атрибут type равен "image/x-icon"
								$category_icon_href = $TagLinkAttrHref;// тогда получаем ссылку на файл с иконкой
							}
						}
					}
					else if($CountTagsLink === 1){// если количество тегов link равно 1
						foreach($TagsLink as $TagLink){// цикл перебора тегов link
							$TagLinkAttrType = $TagLink->attr('type');// получаем атрибут type
							$TagLinkAttrHref = $TagLink->attr('href');// получаем атрибут href
							if($TagLink->attr('type') == "text/css"){// если атрибут type равен "text/css" (этот тип ставиться когда подключаеться файл стилей)
								// код для получения ссылки на файл стилей для категории
								$category_style_href = $TagLinkAttrHref;// тогда берём значение атрибута href в качестве ссылки на файл стилей
								$category_icon_href = '/Default/Style/Icon/default.ico';// а ссылку на файл иконки устанавливаем по умолчнаию
							}
							if($TagLink->attr('type') == "image/x-icon"){// если атрибут type равен "image/x-icon"
								$category_icon_href = $TagLinkAttrHref;// тогда берём значение атрибута href в качестве ссылки на файл с иконкой
								$category_style_href = 'Default/Style/CSS/default.css';// а ссылку на файл стилей ставим по умолчанию
							}
						}
					}
					else if($CountTagsLink === 0){// если количество найденных тегов link равно нулю
						$category_icon_href = '/Default/Style/Icon/default.ico';// ставим ссылку на файл иконки поумолчанию
						$category_style_href = 'Default/Style/CSS/default.css';// ставим ссылку на файл стилей по умоланию
					}
					$PRE_add_category->execute(array('CategoryName' => $category_name, 'CategoryLink' => "", 'CategoryDescription' => $category_description, 'HTMLClass' => $category_html_class,
					'HTMLid' => $category_html_id, 'CategoryIconHref' => $category_icon_href, 'CategoryStyleHref' => $category_style_href));// исполнение подготовленного запроса для добавления категоррии 
					/*Конец добавления категори*/
					$PRE_category_id->execute(array('category_name' => $category_name));// исполняем подготовленный запрос, передав в качестве параметра ассоциативный массив со значениями для выполнения запроса
					$category_id = $PRE_category_id->fetch();// получаем результат
					$category_link = '/show.php?category_id=' . $category_id['CategoryID'];// формирование ссылки на категорию 
					$PRE_update_category_link->execute(array('CategoryLink' => $category_link, 'CategoryID' => $category_id['CategoryID']));
					$PRE_subsubcategory_id = $pdo->prepare("SELECT `SubsubcategoryID` FROM `subsubcategories` WHERE `SubsubcategoryName` = :SubsubcategoryName");// готовим запрос для  поиска ИД подподкатегории по её имени	
					$PRE_subcategory_id = $pdo->prepare("SELECT `SubcategoryID` FROM `subcategories` WHERE `SubcategoryName` = :SubcategoryName");// готовим запрос для получения ИД подкатегории по её имени
					$add_channel = $pdo->prepare("INSERT INTO `channels`(`CategoryID`, `SubcategoryID`, `SubsubcategoryID`, `ChannelName`, `ChannelLink`, `Description`)
							VALUES(:CategoryID, :SubcategoryID, :SubsubcategoryID, :ChannelName, :ChannelLink, :Description);");// готовим запрос для добавления канала
					/*Добавления подкатегорий и подподкатегорий*/
					$H2 = $dom->find('body section h2');// ищем тег <h2>
					foreach($H2 as $row){// цикл перебора тегов <h2> 
						$subcategory_name = $row->text();// получаем текст из тега <h2>
						$subcategory_name = str_replace(":", "", $subcategory_name);// убираем двоеточие из полученного значения (в базе оно храниться без доветочия)
						$HRUnderSubcategory = $row->nextSibling('hr');//ищем тег hr, который идёт за тегом h2
						$HTMLClassHrForSubcategory = "";//HTML класс тега hr для подкатегории
						$HTMLIdHrForSubcategory = "";//HTML ИД тега hr для подкатегории
						$HTMLClassForSubcategory = "";// HTML класс для подкатегории
						$HTMLIdForSubcategory = "";// HTML ИД для подкатегории
						$SubcategoryLink = "NONE"; // ссылка на подкатегорию по умолчанию равна NONE 
						$HTMLClassHrForSubsubcategory = "";//HTML класс тега hr для подподкатегории
						$HTMLIdHrForSubsubcategory = "";//HTML  ИД тега hr для подподкатегории 
						$HTMLClassForSubsubcategory = "";// HTML класс для подподкатегории
						$HTMLIdForSubsubcategory = "";// HTML ИД для подподкатегории
						$SubsubcategoryLink = "NONE"; // ссылка на подподкатегорию по умолчанию равна NONE 
						if($HRUnderSubcategory->hasAttribute('id')){// если hr имеет атрибут id
							//код для получения значения атрибута id
							$HTMLIdHrForSubcategory = $HRUnderSubcategory->attr('id');
						}
						else{// если hr не имеет атрибут id
							// тогда присваиваем переменной $HTMLIdHrForSubcategory значение NONE
							$HTMLIdHrForSubcategory = "NONE";
						}
						if($HRUnderSubcategory->hasAttribute('class')){// если существует атрибут class у тега hr
							// тогда получаем значение этого атрибута
							$HTMLClassHrForSubcategory = $HRUnderSubcategory->attr('class');
						}
						else{// иначе устанавливаем класс HR для подкатегории по умолчанию
							$HTMLClassHrForSubcategory = "default_subcategory_hr_class";
						}
						if($row->hasAttribute('id')){// если тег <h2> имеет атрибут ИД
							$HTMLIdForSubcategory = $row->attr('id');// получаем атрибут значение атрибута id
						}
						else{// иначе устанавливаем его в NONE
							$HTMLIdForSubcategory = "NONE";
						}
						if($row->hasAttribute('class')){// если тег <h2> имеет атрибут class
							$HTMLClassForSubcategory = $row->attr('class');// получаем значение атрибута class
						}
						else{// иначе
							$HTMLClassForSubcategory = "default_subcategory_class";// устанавливаем класс по умолчанию
						}
						// получение ссылки на подкатегорию считаеться что ссылка существует если у <h2> есть родительский тег <a> 
						$ParentTagAH2Element = $row->closest('a');// получение родительского тега <a> для тега <h2> (у тега <a> обязательно должен быть атрибут href)
						if(!is_null($ParentTagAH2Element)){// если переменная в которой должен содержаться родительский элемент не NULL 
							$SubcategoryLink = $ParentTagAH2Element->attr('href');// получения атрибута href для тега <a>
						}
						$PRE_add_subcategory->execute(array('CategoryID' => $category_id['CategoryID'], 'SubcategoryName' => $subcategory_name,
						'SubcategoryLink' => $SubcategoryLink, 'HTMLClass' => $HTMLClassForSubcategory,
						'HTMLid' => $HTMLIdForSubcategory, 'HRClass' => $HTMLClassHrForSubcategory, 'HRid' => $HTMLIdHrForSubcategory));// исполняем подготовленый запрос для добавления подкатегории
					}
					unset($H2);
					$H3 = $dom->find('body section h3');// ищем тег <h3>
					if(!is_null($H3)){//  если существует тег <h3> 
						foreach($H3 as $row){// цикл перебора подподкатегорий
							$H2 = $row->previousSibling('h2');// полуения тега <h2> который стоит над <h3>, из тега <h2> мы получим имя подкатегории
							$subcategory_name  = "";// инициализируем строку с именем подкатегории
							$subcategory_name = $H2->text();// получаем текст из тега <h2>
/* 							echo "$subcategory_name "; */
							$subsubcategory_name = $row->text();// получаем текст из тега <h3>
/* 							echo "$subsubcategory_name"; */
							$subcategory_name = str_replace(":", "", $subcategory_name);// убираем двоеточие из полученного значения (в базе оно храниться без доветочия)
							$subsubcategory_name = str_replace(":", "", $subsubcategory_name);// удаляем двоеточие из строки с именем подподкатегории
							$PRE_subcategory_id->execute(array('SubcategoryName' => $subcategory_name));// исполняем подготовленный запрос, передав в качестве параметра ассоциативный массив со значениями для выполнения запроса
							$subcategory_id = $PRE_subcategory_id->fetch();// получаем ИД подкатегории
							$HRUnderSubsubcategory = $row->nextSibling('hr');// получения hr для подподкатегории
							if($HRUnderSubsubcategory->hasAttribute('class')){// если тег <hr> имеет атрибут class
								$HTMLClassHrForSubsubcategory = $HRUnderSubsubcategory->attr('class');// получения класа тега <hr>
							}
							else{// если нет атрибута class у тега hr 
								$HTMLClassHrForSubsubcategory = "default_subsubcategory_hr_class";
							}
							if($HRUnderSubsubcategory->hasAttribute('id')){// если у тега hr есть есть атрибут id
								$HTMLIdHrForSubsubcategory = $HRUnderSubsubcategory->attr('id');// получение значения атрибута id для тега <hr>
							}
							else{// если тег <hr> не имеет атрибута id
								$HTMLIdHrForSubsubcategory = "NONE";
							}
							if($row->hasAttribute('class')){// если тег <h3> имеет атрибут class
								$HTMLClassForSubsubcategory = $row->attr('class');
							}
							else{// если тег <h3> не имеет атрибута class
								$HTMLClassForSubsubcategory = "default_subsubcategory_class";
							}
							if($row->hasAttribute('id')){// если тег <h3> имеет атрибут id
								$HTMLIdForSubsubcategory = $row->attr('id');
							}
							else{// если тег <h3> не имеет атрибута id
								$HTMLIdForSubsubcategory = "NONE";
							}
							$ParentTagAH3Element = $row->closest('a');// получение родительского тега <a> для тега <h3> (у тега <a> обязательно должен быть атрибут href)
							if(!is_null($ParentTagAH2Element)){// если переменная в которой должен содержаться родительский элемент не NULL 
								$SubsubcategoryLink = $ParentTagAH3Element->attr('href');// получения атрибута href для тега <a>
							}
							$PRE_add_subsubcategory->execute(array('SubcategoryID' => $subcategory_id['SubcategoryID'], 'CategoryID' => $category_id['CategoryID'],
								'SubsubcategoryName' => $subsubcategory_name, 'SubsubcategoryLink' => $SubsubcategoryLink, 
								'HTMLClass' => $HTMLClassForSubsubcategory, 'HTMLid' => $HTMLIdForSubsubcategory,
								'HRClass' => $HTMLClassHrForSubsubcategory, 'HRid' => $HTMLIdHrForSubsubcategory));// ияполняем подготовленный запрос для добавления подподкатегории, передав в качестве параметра массив с необходимыми значениями 
						}
					}
					unset($H2, $H3);
					/*Добавление каналов*/
					$categoies_and_channels = $dom->find('p');// ищем элемент <p></p> (данная строка найдёт все такие элементы)
					foreach($categoies_and_channels as $row){// цикл для переборки найденных тегов <p>
						$H2 = $row->previousSibling('h2');// получение тега <h2> в нём храниться имя подкатегории
						$elexment_x = $row->previousSibling(null, 'DOMElement');// получение элемента x (дальше будет идти проверка являеться ли он тегом <h3>)
						$subcategory_name = $H2->text();// получаем имя подкатегории 
						$subcategory_name = str_replace(":", "", $subcategory_name);// убираем двоеточие из полученного значения (в базе оно храниться без доветочия)
						$subsubcategory_id = 0;// ид подподкатегории (по умолчанию равно нулю)
						/* echo $subcategory_name . " " . $subsubcategory_name . $subsubcategory_id['SubsubcategoryID'];// отладочная информациия после завершения отладки и настройки скрипта удалить или закомментировать эту строку */
						$PRE_subcategory_id->execute(array('SubcategoryName' => $subcategory_name));// исполняем подготовленный запрос, передав в качестве параметра ассоциативный массив со значениями для выполнения запроса
						$subcategory_id = $PRE_subcategory_id->fetch();// получаем ИД подкатегории
						$link_of_channel = $row->find('p a');// выбираем все ссылки в элементе <p>
/* 						echo "Тег $elexment_x->tag"; */
						if($elexment_x->tag == "h3"){// gпроверка являеться ли Element X тегом h3 (это нужно для того чтобы получить имя подподкатегории, а затем по имени подподкатегории получить её ИД)
							$subsubcategory_name = $elexment_x->text();// получаем имя подподкатегории (может отсутствовать)
							$subsubcategory_name = str_replace(":", "", $subsubcategory_name);// удаляем двоеточие из строки с именем подподкатегории
							$PRE_subsubcategory_id->execute(array('SubsubcategoryName' => $subsubcategory_name));// исполняем подготовленный запрос, передав в качестве параметра ассоциативный массив со значениями для выполнения запроса
							$subsubcategory_id_array = $PRE_subsubcategory_id->fetch();// получаем ИД подподкатегории
							$subsubcategory_id = $subsubcategory_id_array['SubsubcategoryID'];// присваиваем ИД подподкатегории из массива в переменную
						}
						else{// если Element X не являеться тегом <h3> тогда
							$subsubcategory_id = 0;// ИД подподкатегории равно нулю
						}
						foreach($link_of_channel as $row2){// перебираем значения выбранные в предидущем шаге
							$add_channel->execute(array('CategoryID' => $category_id['CategoryID'], 'SubcategoryID' => $subcategory_id['SubcategoryID'],
							'SubsubcategoryID' => $subsubcategory_id, 'ChannelName' => $row2->text(), 
							'ChannelLink' => $row2->attr('href'), 'Description' => $row2->attr('title')));// исполняем подготовленный запрос, передав в качестве параметра ассоциативный массив со значениями для выполнения запроса
						}
						/* echo $row->html();// отладочная информация после завершения отладки убрать или закоментировать */
					}
					/*Конец добавления каналов*/
					unset($_POST['submit']);
				}
			?>
	</body>
</html>
