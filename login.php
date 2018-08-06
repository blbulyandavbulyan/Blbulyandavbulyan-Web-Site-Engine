<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Вход</title>
	</head>
	<body>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/config/db.php'; require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/functions.php';  require_once $_SERVER['DOCUMENT_ROOT'] . '/config/mail.php';
		if(isset($_SESSION['logged_user']) && !isset($_SESSION['UserVerifyCode'])):?>
			<?php header('Location: index.html'); ?>
		<?php else if(!isset($_SESSION['logged_user']) || !isset($_SESSION['UserVerifyCode'])):?>
		<h2>Вход</h2>
		<form method="POST">
			<input type="text" name="UserName" placeholder="Имя пользователя">
			<input type="password" name="password" placeholder="Пароль">
			<input type="submit" name="submit" placeholder="Войти">
		</form>
		<?php
			if(isset($_POST['submit']) && isset($_POST['password']) && isset($_POST['UserName'])){
				$data = $_POST;
				$PRE_find_user = $pdo->prepare("SELECT `UserPassword`, `UserEmail` FROM `users` WHERE `UserName` = :UserName");
				$PRE_find_user->execute(array('UserName' => $data['UserName']));
				$User = $PRE_find_user->fetch();
				if(!is_null($User)){
					if(password_verify($data['password'], $User['UserPassword'])){
						$UserVerifyCode = GenerateVerifyCode(500);
						if(SendVerifyMessage($UserVerifyCode, $user['UserEmail'], $mail)){
							session_start();
							$_SESSION['UserVerifyCode'] = $UserVerifyCode;
							$_SESSION['UserName'] = $User['UserName'];
							header('Location: login.php');
						}
					}
				}
			}
		?>
		<?php else if(isset($_SESSION['UserName']) && isset($_SESSION['UserVerifyCode'])):?>
		<form method="POST">
			<input type="text" name="VerifyCode" placeholder="Код подтверждения входа">
			<input type="submit" name="SubmitVerifyCode" placeholder="Войти">
		</form>
		<?php
			if(isset($_POST['SubmitVerifyCode']) && isset($_POST['VerifyCode'])){
				if($_POST['VerifyCode'] == $_SESSION['UserVerifyCode']){
					unset($_SESSION['UserVerifyCode']);
					$_SESSION['logged_user'] = $_SESSION['UserName'];
					unset($_SESSION['UserName']);
				}
				else{
					echo "Неверный код авторизации! Повторите попытку!";
				}
			}
		?>
		<?php endif;?>	
	</body>
</html>