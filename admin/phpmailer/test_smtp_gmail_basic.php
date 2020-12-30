<html>
<head>
    <title>PHPMailer - SMTP (Gmail) basic test</title>
</head>
<body>

<?php

require_once('./class.phpmailer.php');
$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug = 2;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "none";
$mail->Host = "webmail.jaraguadosul.sc.gov.br";
$mail->Port = 25;
$mail->Username = "no-reply";
$mail->Password = "OGNmNDBk";
$mail->SetFrom('no-reply@jaraguadosul.sc.gov.br', 'Recuperacao Senha - Nao Responda');
$mail->AddReplyTo("id10694@jaraguadosul.sc.gov.br", "Rafael Silveira Carneiro");
$dataagora = new datetime();
$mail->Subject = "Test Subject via smtp (), basic " . date_format($dataagora, 'd/mm/Y H:i:s');
$mail->MsgHTML($mail->AltBody);
$address = "id10950@jaraguadosul.sc.gov.br";
$mail->AddAddress($address, "Rafael Silveira Carneiro");
$mail->Send();

?>

</body>
</html>
