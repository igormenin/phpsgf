<!--<html>-->
<!--<head>-->
<!--    <title>PHPMailer - SMTP (Gmail) basic test</title>-->
<!--</head>-->
<!--<body>-->
<!---->
<?php
//
////error_reporting(E_ALL);
//error_reporting(E_STRICT);
//
////date_default_timezone_set('America/Toronto');
//
//require_once('./phpmailer/class.phpmailer.php');
////include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
//
//$mail             = new PHPMailer();
//
////$body             = 'Super teste'; //file_get_contents('contents.html');
////$body             = preg_replace('/[\]/','',$body);

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: id10950@jaraguadosul.sc.gov.br";
$assunto = '';
$destinatario='';
mail($destinatario,$assunto,$message,$headers);

?>
<!---->
<!--</body>-->
<!--</html>-->
