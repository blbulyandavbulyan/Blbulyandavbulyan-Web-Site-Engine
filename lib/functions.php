
<?php 
	/*
	Copyright Blbulyan Software
	This software is distributed under the GNU GPL v3
	This software was developed by one Russian schoolboy.
	All proposals for its development are accepted, criticism is welcomed.
	*/
	/*Защищённые от sql инъекций функциии (используеться PDO*/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'config/config.php';
	function GenerateCategoryOutput($PDO, $query, $AWVFE){/*Эта функция формирует вывод категорий на главной странице (это самые верхний уровень категорий)
	в качестве параметров принимает: $PDO - объект PDO, $query - строка с подготовленным заппросом к БД, по которому будут отобраны категории,
	// $AWVFE - массив со значениями для execute */ 
		$categories = $PDO->prepare($query);//подготовка запроса к БД, по кторому будут отобраны категории
		$categories->execute($AWVFE);// исполнение подготовдленного запроса
		$result = "";// в этой переменной храниться результат работы этой функции, то есть готовая строка с категориями и с HTML разметкой 
		$number_categories = $categories->rowCount();
 		$number_of_vertical_lines = $number_categories - 1; // в этой переменной храниться количество вартикальных линий, которые будут выведенны между ссылками
		$vertical_line_counter = 0; // это счётчик вертикальных линий
		foreach($categories as $row){// этот цикл переберает результат запроса, который храниться в $categories
			$temp = "<h3><a href=\"" . $row['CategoryLink'] . "\"><b>" . $row['CategoryName'] . "</b></a></h3>";// эта строка вормирует ссылку на категорию
			// и записываеться в переменную $temp, которая предназначена для временного хранения данных
			if($number_categories > 1){// это условие предназначенно для вывода вертикальных линий
			// алгоритм таков, если количество записей в $categories больше одного,
			//то вывести столько вертикальных линий сколько заданно в переменной $number_of_vertical_lines
			// это реализуеться с помощью счётчика срабатывания этого условия 
				if($vertical_line_counter != $number_of_vertical_lines){ // само условие, для проверки нужно ли выводить вертикальную черту
					$temp = $temp . " <span class=\"spa\">|</span> ";// формирование и присоединение строки с вертикальной чертой
				}
				$vertical_line_counter++;// увеличение сётчика на 1
			}
			$result = $result . $temp;// присоединение временной строки к результату
		}
		return $result;// возвращение результата
	}
	function GenerateSubcategoryOutput($PDO, $query, $AWVFE){
		$subcategories = $PDO->prepare($query);
		$subcategories->execute($AWVFE);
		$result = "";
		$number_subcategories = $subcategories->rowCount();
		$number_of_vertical_lines = $number_subcategories - 1;
		$vertical_line_counter = 0;
		foreach($subcategories as $row){
			$temp = "<h4><a href=\"" . $row['SubcategoryLink'] . "\"><b>" . $row['SubcategoryName'] . "</b></a></h4>";
				if($number_subcategories > 1){// это условие предназначенно для вывода вертикальных линий
				// алгоритм таков, если количество записей в $subcategories больше одного,
				//то вывести столько вертикальных линий сколько заданно в переменной $number_of_vertical_lines
				// это реализуеться с помощью счётчика срабатывания этого условия 
					if($vertical_line_counter != $number_of_vertical_lines){ // само условие, для проверки нужно ли выводить вертикальную черту
						$temp = $temp . " <span class=\"spa\">|</span> ";// формирование и присоединение строки с вертикальной чертой
					}
					$vertical_line_counter++;// увеличение сётчика на 1
				} 
				$result = $result . $temp;// присоединение временной строки к результату
		}
		return $result;
	}
	function GenerateChannelOutput($PDO, $query, $AWVFE){/*Выводит сслыки на каналы, в качестве параметров принимает:
	$PDO - объект PDO, $query - строка с подготовленным запросом к БД, по которому будут отобраны каналы, $AWVFE - массив со значениями для execute */
		$channels = $PDO->prepare($query);// Выполнение запроса к БД, для выбора каналов
		$channels->execute($AWVFE);
		$result = "";
		$number_of_channels = $channels->rowCount();
		$number_of_spaces = $number_of_channels + 4; // в этой переменной храниться количество пробелов которое необходимо вывести после вертикальной черты в конце 
		$space_counter = 0; // это счётчик пробелов
		$number_of_vertical_lines = $number_of_channels + 1; // в этой переменной храниться количество вартикальных линий, которые будут выведенны между ссылками
		$vertical_line_counter = 0; // это счётчик вертикальных линий
		if($number_of_channels === 0){
			return array($result, $number_of_channels);
		}
		foreach($channels as $channel){// этот цикл переберает результат запроса, который храниться в $channels
			$temp = "<a target=\"_blank\"  href=\"" . $channel['ChannelLink'] . "\" title=\"" . $channel['Description'] ."\">" . $channel['ChannelName'] . "</a>";// эта строка вормирует ссылку на канал
			// и записываеться в переменную $temp, которая предназначена для временного хранения данных
			if($number_of_channels > 1){// это условие предназначенно для вывода пробелов
			// алгоритм таков, если количество записей в $channels больше одного,
			//то вывести столько пробелов сколько заданно в переменной $number_of_spaces
			// это реализуеться с помощью счётчика срабатывания этого условия 
				if($space_counter <= $number_of_spaces){ // само условие, для проверки нужно ли выводить пробел
					$temp = $temp . " ";// формирование и присоединение строки с пробелом
				}
				if($vertical_line_counter <= $number_of_vertical_lines){ // само условие, для проверки нужно ли выводить вертикальную черту
					$temp = $temp . "|";// формирование и присоединение строки с вертикальной чертой
				}
				if($space_counter <= $number_of_spaces){ // само условие, для проверки нужно ли выводить пробел
					$temp = $temp . " ";// формирование и присоединение строки с пробелом
				}
				$vertical_line_counter++;
				$space_counter++;// увеличение сётчика на 1
			}

			else if($number_of_channels === 1){
				$temp = $temp . " |";
			}

			$result = $result . $temp;// присоединение временной строки к результату
		}
		$result = "<p>| " . $result . "</p>";
		return array($result, $number_of_channels);// возвращение результата
	}
	function GenerateCategoryAndChannelsOutput($PDO, $UCATEGORY_ID){
		// GenerateCategoryAndChannelsOutputSecure - безопасная функция вывода категорий, пинимает на вход 
		// $PDO - объект PDO, $UCATEGORY_NAME - строка с именен категории
			$PRE_category = $PDO->prepare("SELECT `CategoryName`, `HTMLClass`, `HTMLid` FROM `categories` WHERE `CategoryID` = :CategoryID"); // подготовка запроса для выбора имени категории по её ИД 
			$PRE_category->execute(array('CategoryID' => $UCATEGORY_ID));// исполнение подготовленного запроса, передав ассоциативный массив с переменными
			$category = $PRE_category->fetch();// получение имени категории 
			$PRE_category->closeCursor();
			$subcategories = $PDO->prepare("SELECT * FROM `subcategories` WHERE `CategoryID` = :CategoryID");// подготовка запроса для получения подкатегорий по ИД категории
			$subcategories->execute(array('CategoryID' => $UCATEGORY_ID));// исполнение подготовдленного запроса, передав ассоциативный массив с переменными
			$result = "";
			if(!is_null($category['CategoryName'])){
				if($category['HTMLClass'] != "NONE"){
					$result = $result . "<h1 class=\"" . $category['HTMLClass'] . "\"";
				}
				else{
					$result = $result . "<h1";
				}
				if($category['HTMLid'] != "NONE"){
					$result = $result . " id=\"" . $category['HTMLid'] . "\">";
				}
				else{
					$result = $result . ">";
				}
				$result = $result  . $category['CategoryName'] . ":</h1>";// формирование строки с именем категории
				foreach($subcategories as $row_subcategories){// цикл перебора подкатегорий
					$temp = "";// это временная переменная, она действует только внутри этого цикла
					if($row_subcategories['SubcategoryLink'] === "NONE"){// если не существует ссылка на подкатегорию
						list ($channels_in_subcategory, $CountChannelsInSubcategory) = GenerateChannelOutput($PDO, 
							"SELECT * FROM `channels` WHERE `CategoryID` = :CategoryID && `SubcategoryID` = :SubcategoryID && `SubsubcategoryID` = 0",
							array('CategoryID' => $UCATEGORY_ID, 'SubcategoryID' => $row_subcategories['SubcategoryID']));// вызов функции GenerateChannelOutputSecure для получения каналов в подкатегории, результат этой функции 
							// это строка с ссылками на каналы, этот результат записываеться в переменную $channels_in_subcategory
						$subsubcategories = $PDO->prepare("SELECT * FROM `subsubcategories` WHERE `SubcategoryID` = :SubcategoryID");// подготовка запроса для выбора подподкатегорий по ИД подкатегории
						$subsubcategories->execute(array('SubcategoryID' => $row_subcategories['SubcategoryID']));// исполнение подготовдленного запроса, передав ассоциативный массив с переменными
						if($CountChannelsInSubcategory > 0){
							if($row_subcategories['HTMLClass'] != "NONE"){
								$temp = $temp . "<h2 class=\"" . $row_subcategories['HTMLClass'] . "\"";
							}
							else{
								$temp = $temp . "<h2";
							}
							if($row_subcategories['HTMLid'] != "NONE"){
								$temp = $temp . " id=\"" . $row_subcategories['HTMLid'] . "\">";
							}
							else{
								$temp = $temp . ">";
							}
							$temp = $temp  . $row_subcategories['SubcategoryName'] . ":</h2>";// формирование строки с именем категории
							$temp = $temp . $channels_in_subcategory;// 
							if($row_subcategories['HRClass'] != "NONE"){
								$temp = $temp . "<hr class=\"" . $row_subcategories['HRClass'] . "\"";
							}
							else{
								$temp = $temp . "<hr";
							}
							if($row_subcategories['HRid'] != "NONE"){
								$temp = $temp . " id=\"" . $row_subcategories['HRid'] . "\">";
							}
							else{
								$temp = $temp . ">";
							}
						}
						else if($subsubcategories->rowCount() > 0){// если у подкатегории есть подподкатегории 
							if($row_subcategories['HTMLClass'] != "NONE"){
								$temp = $temp . "<h2 class=\"" . $row_subcategories['HTMLClass'] . "\"";
							}
							else{
								$temp = $temp . "<h2";
							}
							if($row_subcategories['HTMLid'] != "NONE"){
								$temp = $temp . " id=\"" . $row_subcategories['HTMLid'] . "\">";
							}
							else{
								$temp = $temp . ">";
							}
							$temp = $temp  . $row_subcategories['SubcategoryName'] . ":</h2>";// формирование строки с именем категории
							foreach($subsubcategories as $row_subsubcategories){// цикл перебора подподкатегорий
							if($row_subsubcategories['SubsubcategoryLink'] === "NONE"){/// если нет ссылки на подподкатегорию
								list ($channels_in_subsubcategory, $CountChannelsInSubsubcategory) = GenerateChannelOutput($PDO, 
								"SELECT * FROM `channels` WHERE `CategoryID` = :CategoryID && `SubcategoryID` = :SubcategoryID && `SubsubcategoryID` = :SubsubcategoryID",
								array('CategoryID' => $UCATEGORY_ID, 'SubcategoryID' => $row_subcategories['SubcategoryID'], 'SubsubcategoryID' => $row_subsubcategories['SubsubcategoryID']));
								if($CountChannelsInSubsubcategory > 0){
									if($row_subsubcategories['HTMLClass'] != "NONE"){
										$temp = $temp . "<h3 class=\"" . $row_subsubcategories['HTMLClass'] . "\"";
									}
									else{
										$temp = $temp . "<h3";
									}
									if($row_subsubcategories['HTMLid'] != "NONE"){
										$temp = $temp . " id=\"" . $row_subsubcategories['HTMLid'] . "\">";
									}
									else{
										$temp = $temp . ">";
									}
									$temp = $temp  . $row_subsubcategories['SubsubcategoryName'] . ":</h3>";
									$temp = $temp . $channels_in_subsubcategory;
									if($row_subsubcategories['HRClass'] != "NONE"){
										$temp = $temp . "<hr class=\"" . $row_subsubcategories['HRClass'] . "\"";
									}
									else{
										$temp = $temp . "<hr";
									}
									if($row_subsubcategories['HRid'] != "NONE"){
										$temp = $temp . " id=\"" . $row_subsubcategories['HRid'] . "\">";
									}
									else{
										$temp = $temp . ">";
									}
								}
							}
							else {// если ссылка на подподкатегорию существует
								$temp = $temp . "<a href=\"" . $row_subsubcategories['SubsubcategoryLink'] . "\">" . $row_subsubcategories['SubsubcategoryName'] . "</a>";
								$temp = $temp . "<hr>";
							}
						}
					}
					else{// если у подкатегории нет подподкатегорий 
						// пока что тут нет ничего
					}
				}
				else{// если существует ссылка на подкатегорию
					$temp = $temp . "<h2><a href=\"" . $row_subcategories['SubcategoryLink'] . "\">" . $row_subcategories['SubcategoryName'] . "</a></h2>";// формирование ссылки на подкатегорию
				}
				$result = $result . $temp;// запись результата цикла в переменную $result
			} 
		}
		else{
			$result = "<div class=\"error error_when_searching_for_a_category_by_its_id\" id=\"category_with_such_identifier_does_not_exist\">Категории с ИД " . $UCATEGORY_ID . " не существует!</div>";
		}
		$result = "<section><br>" . $result . "</section>";// оборачивание результата в тег <section></section> 
		return $result;
	}
	function GenerateSubcategoryAndChannelsOutput($PDO, $USUBCATEGORY_ID){
		$PRE_category = $PDO->prepare("SELECT `CategoryName`, `CategoryLink`, `HTMLClass`, `HTMLid` FROM `categories` WHERE `CategoryID` = :CategoryID");
		$PRE_subcategory = $PDO->prepare("SELECT `SubcategoryName`, `CategoryID`, `HTMLClass`, `HTMLid`, `HRClass`, `HRid` FROM `subcategories` WHERE `SubcategoryID` = :SubcategoryID");
		$subsubcategories = $PDO->prepare("SELECT `SubsubcategoryID`, `SubsubcategoryName`, `SubsubcategoryLink`, `HTMLClass`, `HTMLid`, `HRClass`, `HRid` FROM `subsubcategories` WHERE `SubcategoryID` = :SubcategoryID && `CategoryID` = :CategoryID");
		$PRE_subcategory->execute(array('SubcategoryID' => $USUBCATEGORY_ID));
		$subcategory = $PRE_subcategory->fetch();
		$PRE_subcategory->closeCursor();
		$result = "";
		if(!is_null($subcategory['SubcategoryName'])){
			$PRE_category->execute(array('CategoryID' => $subcategory['CategoryID']));
			$category = $PRE_category->fetch();
			$PRE_category->closeCursor();
			if(!is_null($category['CategoryName'])){
				$subsubcategories->execute(array('SubcategoryID' => $USUBCATEGORY_ID, 'CategoryID' => $subcategory['CategoryID']));
				$temp = "";
				if($category['HTMLClass'] != "NONE"){
					$temp = $temp . "<h1 class=\"" . $category['HTMLClass'] . "\"";
				}
				else{
					$temp = $temp . "<h1";
				}
				if($category['HTMLid'] != "NONE"){
					$temp = $temp . " id=\"" . $category['HTMLid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$temp = $temp  . $category['CategoryName'] . ":</h1>";// формирование строки с именем категории
				
				if($subcategory['HTMLClass'] != "NONE"){
					$temp = $temp . "<h2 class=\"" . $subcategory['HTMLClass'] . "\"";
				}
				else{
					$temp = $temp . "<h2";
				}
				if($subcategory['HTMLid'] != "NONE"){
					$temp = $temp . " id=\"" . $subcategory['HTMLid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$temp = $temp  . $subcategory['SubcategoryName'] . ":</h2>";// формирование строки с именем подкатегории
				list ($channels_in_subcategory, $CountChannelsInSubcategory) = GenerateChannelOutput($PDO, "SELECT * FROM `channels` WHERE `CategoryID` = :CategoryID && `SubcategoryID` = :SubcategoryID && `SubsubcategoryID` = 0", array('CategoryID' => $subcategory['CategoryID'], 'SubcategoryID' => $USUBCATEGORY_ID));// вызов функции GenerateChannelOutputSecure для получения каналов в подкатегории, результат этой функции 
							// это строка с ссылками на каналы, этот результат записываеться в переменную $channels_in_subcategory
				if($CountChannelsInSubcategory > 0){
					$temp = $temp . $channels_in_subcategory;
					if($subcategory['HRClass'] != "NONE"){
						$temp = $temp . "<hr class=\"" . $subcategory['HRClass'] . "\"";
					}
					else{
						$temp = $temp . "<hr";
					}
					if($subcategory ['HRid'] != "NONE"){
						$temp = $temp . " id=\"" . $subcategory['HRid'] . "\">";
					}
					else{
						$temp = $temp . ">";
					}
					$result = $result . $temp;
				}
				else if($subsubcategories->rowCount() > 0){// если у подкатегории есть подподкатегории 
						foreach($subsubcategories as $row_subsubcategories){// цикл перебора подподкатегорий
							if($row_subsubcategories['SubsubcategoryLink'] === "NONE"){/// если нет ссылки на подподкатегорию
								list ($channels_in_subsubcategory, $CountChannelsInSubsubcategory) = GenerateChannelOutput($PDO, "SELECT * FROM `channels` WHERE `CategoryID` = :CategoryID && `SubcategoryID` = :SubcategoryID && `SubsubcategoryID` = :SubsubcategoryID", array('CategoryID' => $subcategory['CategoryID'], 'SubcategoryID' => $USUBCATEGORY_ID, 'SubsubcategoryID' => $row_subsubcategories['SubsubcategoryID']));
								if($CountChannelsInSubsubcategory > 0){
									if($row_subsubcategories['HTMLClass'] != "NONE"){
											$temp = $temp . "<h3 class=\"" . $row_subsubcategories['HTMLClass'] . "\"";
									}
									else{
										$temp = $temp . "<h3";
									}
									if($row_subsubcategories['HTMLid'] != "NONE"){
										$temp = $temp . " id=\"" . $row_subsubcategories['HTMLid'] . "\">";
									}
									else{
										$temp = $temp . ">";
									}
									$temp = $temp  . $row_subsubcategories['SubsubcategoryName'] . ":</h3>";
									$temp = $temp . $channels_in_subsubcategory;
									if($row_subsubcategories['HRClass'] != "NONE"){
										$temp = $temp . "<hr class=\"" . $row_subsubcategories['HRClass'] . "\"";
									}
									else{
										$temp = $temp . "<hr";
									}
									if($row_subsubcategories['HRid'] != "NONE"){
										$temp = $temp . " id=\"" . $row_subsubcategories['HRid'] . "\">";
									}
									else{
										$temp = $temp . ">";
									}
								}
							}
							else {// если ссылка на подподкатегорию существует
								$temp = $temp . "<a href=\"" . $row_subsubcategories['SubsubcategoryLink'] . "\">" . $row_subsubcategories['SubsubcategoryName'] . "</a>";
								$temp = $temp . "<hr>";
							}
						}
						$result = $result . $temp;
				}
			}
			else{
				$result = "<div class=\"error error_when_searching_for_a_subcategory_by_its_id\" id=\"error_in_subcategory_has_no_parent_category\">Непредвиденная ошибка! У подкатегории нет родительской категории! Обратитесь к администратору сайта для решения этой проблемы!</div>";
			}
		}
		else{
			$result = "<div class=\"error error_when_searching_for_a_subcategory_by_its_id\" id=\"subcategory_with_such_identifier_does_not_exist\">Подкатегории с ИД " . $USUBCATEGORY_ID . " не существует!</div>";
		}
		$result = "<section><br>" . $result . "</section>";
		return $result;
	}
	function GenerateSubsubcategoryAndChannelsOutput($PDO, $USUBSUBCATEGORY_ID){
		$result = "";
		$PRE_category = $PDO->prepare("SELECT `CategoryName`, `CategoryIconHref`, `CategoryStyleHref`, `HTMLClass`, `HTMLid` FROM `categories` WHERE `CategoryID` = :CategoryID");
		$PRE_subcategory = $PDO->prepare("SELECT `SubcategoryName`, `HTMLClass`, `HTMLid` FROM `subcategories` WHERE `SubcategoryID` = :SubcategoryID");
		$PRE_subsubcategory = $PDO->prepare("SELECT `SubsubcategoryName`, `SubcategoryID`, `CategoryID`, `HTMLClass`, `HTMLid`, `HRid`, `HRClass` FROM `subsubcategories` WHERE `SubsubcategoryID` = :SubsubcategoryID");
		$PRE_subsubcategory->execute(array('SubsubcategoryID' => $USUBSUBCATEGORY_ID));
		$subsubcategory = $PRE_subsubcategory->fetch();
		$PRE_subsubcategory->closeCursor();
		if(!is_null($subsubcategory['SubsubcategoryName'])){
			$PRE_subcategory->execute(array('SubcategoryID' => $subsubcategory['SubcategoryID']));
			$subcategory = $PRE_subcategory->fetch();
			$PRE_subcategory->closeCursor();
			$PRE_category->execute(array('CategoryID' => $subsubcategory['CategoryID']));
			$category = $PRE_category->fetch();
			$PRE_category->closeCursor();
			list ($channels_in_subsubcategory, $CountChannelsInSubsubcategory) = GenerateChannelOutput($PDO, "SELECT * FROM `channels` WHERE `CategoryID` = :CategoryID && `SubcategoryID` = :SubcategoryID && `SubsubcategoryID` = :SubsubcategoryID", array('CategoryID' => $subsubcategory['CategoryID'], 'SubcategoryID' => $subsubcategory['SubcategoryID'], 'SubsubcategoryID' => $USUBSUBCATEGORY_ID));
			if($CountChannelsInSubsubcategory > 0){
				$temp = "";
				if($category['HTMLClass'] != "NONE"){
					$temp = $temp . "<h1 class=\"" . $category['HTMLClass'] . "\"";
				}
				else{
					$temp = $temp . "<h1";
				}
				if($category['HTMLid'] != "NONE"){
					$temp = $temp . " id=\"" . $category['HTMLid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$temp = $temp  . $category['CategoryName'] . ":</h1>";// формирование строки с именем категории
				
				if($subcategory['HTMLClass'] != "NONE"){
					$temp = $temp . "<h2 class=\"" . $subcategory['HTMLClass'] . "\"";
				}
				else{
					$temp = $temp . "<h2";
				}
				if($subcategory['HTMLid'] != "NONE"){
					$temp = $temp . " id=\"" . $subcategory['HTMLid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$temp = $temp  . $subcategory['SubcategoryName'] . ":</h2>";
				if($subsubcategory['HTMLClass'] != "NONE"){
					$temp = $temp . "<h3 class=\"" . $subsubcategory['HTMLClass'] . "\"";
				}
				else{
					$temp = $temp . "<h3";
				}
				if($subsubcategory['HTMLid'] != "NONE"){
					$temp = $temp . " id=\"" . $subsubcategory['HTMLid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$temp = $temp  . $subsubcategory['SubsubcategoryName'] . ":</h3>";
				$temp = $temp . $channels_in_subsubcategory;
				if($subsubcategory['HRClass'] != "NONE"){
					$temp = $temp . "<hr class=\"" . $subsubcategory['HRClass'] . "\"";
				}
				else{
					$temp = $temp . "<hr";
				}
				if($subsubcategory['HRid'] != "NONE"){
					$temp = $temp . " id=\"" . $subsubcategory['HRid'] . "\">";
				}
				else{
					$temp = $temp . ">";
				}
				$result .= $temp;
			}
		}
		else{
			$result = "<div class=\"error error_when_searching_for_a_subsubcategory_by_its_id\" id=\"subsubcategory_with_such_identifier_does_not_exist\">Подподкатегории с ИД " . $USUBSUBCATEGORY_ID . " не существует!</div>";
		}
		$result = "<section><br>" . $result . "</section>";
		return $result;
	}
	/*GenerateHeadForPageWithCategory - эта функция генерирует содержимое тега <head> в зависимости от ИД категории*/
	function GenerateHeadForPageWithCategory($PDO, $UCATEGORY_ID){
		$result = "";
		$PRE_category = $PDO->prepare("SELECT `CategoryName`, `CategoryStyleHref`, `CategoryIconHref`, `CategoryDescription` FROM `categories` WHERE `CategoryID` = :CategoryID");
		$PRE_category->execute(array('CategoryID' => $UCATEGORY_ID));
		$category = $PRE_category->fetch();
		$PRE_category->closeCursor();
		if(!is_null($category['CategoryName'])){
			$result = "<meta charset=\"" . $GLOBALS['default_charset'] ."\" name=\"description\" content=\"" . $category['CategoryDescription'] . "\">";
			$result .= "<title>" . $category['CategoryName'] . "</title>";
			$result .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . $category['CategoryStyleHref'] . "\">";
			$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $category['CategoryIconHref'] . "\">";
		}
		else{
			$result = "<meta charset=\"" . $GLOBALS['default_charset'] . "\">";
			$result .= "<title>Такой категории у нас нет.</title>";
			$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $GLOBALS['icon_404_error_href'] . "\">";
		}
		return $result;
	}
	function GenerateHeadForPageWithSubcategory($PDO, $USUBCATEGORY_ID){
		$result = "";
		$PRE_subcategory = $PDO->prepare("SELECT `SubcategoryName`, `CategoryID` FROM `subcategories` WHERE `SubcategoryID` = :SubcategoryID");
		$PRE_subcategory->execute(array('SubcategoryID' => $USUBCATEGORY_ID));
		$subcategory = $PRE_subcategory->fetch();
		$PRE_subcategory->closeCursor();
		if(!is_null($subcategory['SubcategoryName'])){
				$PRE_category = $PDO->prepare("SELECT `CategoryName`, `CategoryIconHref`, `CategoryStyleHref`, `CategoryDescription` FROM `categories` WHERE `CategoryID` = :CategoryID");
				$PRE_category->execute(array('CategoryID' => $subcategory['CategoryID']));
				$category = $PRE_category->fetch();
				$PRE_category->closeCursor();
			if(!is_null($category['CategoryName'])){
				$result = "<meta charset=\"" . $GLOBALS['default_charset'] ."\" name=\"description\" content=\"" . $category['CategoryDescription'] . "\">";
				$result .= "<title>Подкатегория: \"" . $subcategory['SubcategoryName'] . "\" в категории: \"" . $category['CategoryName'] . "\"</title>";
				$result .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . $category['CategoryStyleHref'] . "\">";
				$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $category['CategoryIconHref'] . "\">"; 
			}
			else{
				$result = "<meta charset=\"" . $GLOBALS['default_charset'] . "\">";
				$result .= "<title>Подкатегория: \"" . $subcategory['SubcategoryName'] . "\" в неизвестной категории</title>";
				$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $GLOBALS['icon_404_error_href'] . "\">"; 
			}
		}
		else{
			$result = "<meta charset=\"" . $GLOBALS['default_charset'] . "\">";
			$result .= "<title>Такой подкатегории у нас нет.</title>";
			$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $GLOBALS['icon_404_error_href'] . "\">";
			return $result;
		}
		return $result;
	}
	function GenerateHeadForPageWithSubsubcategory($PDO, $USUBSUBCATEGORY_ID){
		$result = "";
		$PRE_subsubcategory = $PDO->prepare("SELECT `CategoryID`, `SubcategoryID`, `SubsubcategoryName` FROM `subsubcategories` WHERE `SubsubcategoryID` = :SubsubcategoryID");
		$PRE_subsubcategory->execute(array('SubsubcategoryID' => $USUBSUBCATEGORY_ID));
		$subsubcategory = $PRE_subsubcategory->fetch();
		$PRE_subsubcategory->closeCursor();
		if(!is_null($subsubcategory['SubsubcategoryName'])){
			$PRE_category = $PDO->prepare("SELECT `CategoryName`, `CategoryIconHref`, `CategoryStyleHref`, `CategoryDescription` FROM `categories` WHERE `CategoryID` = :CategoryID");
			$PRE_subcategory = $PDO->prepare("SELECT `SubcategoryName` FROM `subcategories` WHERE `SubcategoryID` = :SubcategoryID");
			$PRE_category->execute(array('CategoryID' => $subsubcategory['CategoryID']));
			$PRE_subcategory->execute(array('SubcategoryID' => $subsubcategory['SubcategoryID']));
			$category = $PRE_category->fetch();
			$PRE_category->closeCursor();
			$subcategory = $PRE_subcategory->fetch();
			$PRE_subcategory->closeCursor();
			$result = "<meta charset=\"" . $GLOBALS['default_charset'] ."\" name=\"description\" content=\"" . $category['CategoryDescription'] . "\">";
			$result .= "<title>Подподкатегория: \"" . $subsubcategory['SubsubcategoryName'] . "\" в подкатегории: \"" . (!is_null($subcategory['SubcategoryName']) ? $subcategory['SubcategoryName'] : "неизвестная подкатегория") . "\" в категории: \"" . (!is_null($category['CategoryName']) ? $category['CategoryName'] : "неизвестная категория") . "\"</title>";
			$result .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . $category['CategoryStyleHref'] . "\">";
			$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $category['CategoryIconHref'] . "\">"; 
			return $result;
		}
		else{
			$result = "<meta charset=\"" . $GLOBALS['default_charset'] . "\">";
			$result .= "<title>Такой подподкатегории у нас нет.</title>";
			$result .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $GLOBALS['icon_404_error_href'] . "\">";
			return $result;
		}
		return $result;
	}
	function GenerateVerifyCode($number)  {  
		$arr = array('a','b','c','d','e','f',  
					 'g','h','i','j','k','l',  
					 'm','n','o','p','r','s',  
					 't','u','v','x','y','z',  
					 'A','B','C','D','E','F',  
					 'G','H','I','J','K','L',  
					 'M','N','O','P','R','S',  
					 'T','U','V','X','Y','Z',  
					 '1','2','3','4','5','6',  
					 '7','8','9','0','.',',',  
					 '(',')','[',']','!','?',  
					 '&','^','%','@','*','$',  
					 '<','>','/','|','+','-',  
					 '{','}','`','~');  
		$pass = "";  
		for($i = 0; $i < $number; $i++){  
		  // Вычисляем случайный индекс массива  
		  $index = rand(0, count($arr) - 1);  
		  $pass .= $arr[$index];  
		}  
		return $pass;  
	}  
	function GenerateVerifyMessage($VerifyCode){
		$result = "";
		$result .= "<h1>Запрошен авторизационный код с IP адресса"  . $_SERVER['HTTP_CLIENT_IP'] . "</h1>";
		$result .= "<p>UserAgent запросившего авторизационный код:" .  $_SERVER['HTTP_USER_AGENT']. "</p>";
		$result .= "<p>Ваш авторизационный код: " . "<div id=\"verify_code\">" . $VerifyCode . "</div><p>";
		$result .= "<p color=\"red\">Пожалуйста ни кому не сообщайте его! Если запросили его не вы, то побробуйте <a href=\"" . $_SERVER['SERVER_NAME'] . "/resetpassword.php\">восстановить пароль</a>";
		return $result;
	}
	function SendVerifyMessage($VerifyCode, $addressee, $MAIL){
		$MAIL->Subject     = "Запрошен код авторизации";
		$MAIL->ContentType = "text/html; charset=utf-8\r\n";
		$MAIL->isHTML(true);
		$MAIL->Body = GenerateVerifyMessage($VerifyCode);
		$MAIL->WordWrap = 50;
		//адрес, на котоый нужно отправить письмо
		$MAIL->AddAddress($addressee);
		if(!$MAIL->send()) {
			return false;
		}
		else{
			return true;
		}
	}
?>