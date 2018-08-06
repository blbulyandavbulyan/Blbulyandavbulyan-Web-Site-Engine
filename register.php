<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
		<form method="POST">
			<input type="text" name="UserName" placeholder="Имя пользователя">
			<input type="email" name="UserEmail" placeholder="Email">
			<input type="text" name="UserFirstName" placeholder="Ваше имя">
			<input type="text" name="UserLastName" placeholder="Ваша фамилия">
			<input type="password" name="UserFirstPassword" placeholder="Ваш пароль">
			<input type="password" name="UserLastPassword" placeholder="Повторите ваш пароль">
			<input type="text" name="UserToken" placeholder="Ваш токен для регистрации">
			<input type="submit" name="submit" placeholder="Зарегистрироваться">
		</form>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/config/db.php'; require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/functions.php';  require_once $_SERVER['DOCUMENT_ROOT'] . '/config/mail.php'; 
			if(isset($_POST['submit']){
				if(isset($_POST['UserName'], $_POST['UserEmail'], $_POST['UserFirstName'], $_POST['UserLastName'], $_POST['UserFirstPassword'], $_POST['UserLastPassword'], $_POST['UserToken'])){
					//начало обработки входных данных
					$UserExamination = $pdo->prepare("SELECT * FROM `users` WHERE `UserName` = :UserName");
					$UserAdd = $pdo->prepare("INSERT INTO `users` ")
					$UserName = trim($_POST['UserName']);
					$UserEmail = filter_var(trim($_POST['UserEmail'], FILTER_SANITIZE_EMAIL));
					$UserFirstName = trim($_POST['UserFirstName']);
					$UserLastName = trim($_POST['UserLastName']);
					$UserFirstPassword = trim($_POST['UserFirstPassword']);
					$UserLastPassword = trim($_POST['UserLastPassword']);
					$UserToken = trim($_POST['UserToken']);
					$errors = array();
					if($UserName == ''){
						$errors[] = 'Введите имя пользователя!!';
					}
					if(!preg_match("#^[aA-zZ0-9]+$#", $UserName)){
						$errors = 'Имя пользователя содержит недопустимые символы! В имени пользователя допускаеться использовать латинские буквы (любого регистра), нижнее подчёркивание и цифры.';
					}
					if($UserEmail == ''){
						$errors[] = 'Введите Email!';
					}
					if(filter_var($UserEmail, FILTER_VALIDATE_EMAIL){
						$errors = 'Не корректный Email адрес!';
					}
					if($UserFirstName == ''){
						$errros[] = 'Введите ваше имя!'; 
					}
					if(!preg_match("#^[А-Яа-яA-Za-z0-9_]+$#u", $UserFirstName)){
						$errors[] = 'Ваше имя содержит недопустимые символы! В вашем имени допускаеться использовать латинские и кирилические буквы(любого регистра), нижнее подчёркивание, цифры.';
					}
					if($UserLastName == ''){
						$errors[] = 'Введите вашу фамилию!';
					}
					if(!preg_match("#^[А-Яа-яA-Za-z0-9_]+$#u", $UserLastName)){
						$errors[] = 'Ваша фамилия содержит недопустимые символы! В вашей фамилии допускаеться использовать латинские и кирилические буквы(любого регистра), нижнее подчёркивание, цифры.'
					}
					if($UserFirstPassword == ''){
						$errors[] = 'Введите пароль!';
					}
					if($UserFirstPassword != $UserLastPassword){
						$errors[] = 'Повторный пароль введён не верно!';
					}


					if(R::count('user', "login = ?", array($data['login'])) > 0 ){
						$errors[] = 'Пользователь с таким именем пользователя уже существует!';
					}
					if(R::count('user', "email = ?", array($data['email'])) > 0 ){
						$errors[] = 'Пользователь с таким email уже существуетЪ!!';
					}
					if(empty($errors) ){
						// процесс регистрации 
						
						$user = R::dispense('user');
						$user->login = $data['login'];
						$user->email = $data['email'];
						$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
						R::store($user);
						header('Location: index.html');
					}
					else{
						echo '<div class="error" id="error_register">'.array_shift($errors).'</div>';
					}
				}
				else{
					die("Одного из полей не существует!");
				}
			}
		?>
	</body>
</html>