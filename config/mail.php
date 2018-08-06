<?php 
	//Этот файл предназначен для конфигурации PHPMailer 
	require $_SERVER['DOCUMENT_ROOT'] . 'lib/PHPMailer/Exception.php';
	require $_SERVER['DOCUMENT_ROOT'] . 'lib/PHPMailer/PHPMailer.php';
	require $_SERVER['DOCUMENT_ROOT'] . 'lib/PHPMailer/SMTP.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	$mail = new PHPMailer(true);// Passing `true` enables exceptions
    //Server settings
    $mail->SMTPDebug = 2;// Enable verbose debug output
    $mail->isSMTP();// Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;// Enable SMTP authentication
    $mail->Username = 'youremail@gmail.com';// SMTP username
    $mail->Password = 'yourpassword'; // SMTP password
    $mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;// TCP port to connect to
    $mail->CharSet  = 'UTF-8';
    $mail->Encoding  = '8bit';
	$mail->setFrom('youremail@gmail.com', 'System');
?>
