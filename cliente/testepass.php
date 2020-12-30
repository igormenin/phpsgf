<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/cliente.css" type="text/css" media="screen">
    <title>Gerador de Senhas para Salvar do Servidor de Senhas..</title>

</head>
<body>
<?php
require_once "./seguranca.php";
$passDig = (isset($_GET['passUsuario'])) ? $_GET['passUsuario'] : 'smssaude';
echo 'Caso precise senha diferente de smssaude, use o parâmetro passUsuario na URL acima.<br><br>';
echo 'Senha Digitada: ' . $passDig . '<br><br>';

$pass = geraSenhaCriptografada($passDig);
echo('Senha Gerada: ');
echo '<input size="' . strlen($pass) . '"  type="text" placeholder="Senha" name="txtsenha" value="' . $pass . '"/>';
$verify = password_verify($passDig,$pass);
if ($verify){
    echo '<br>Confere!<br>';
}
?>

</body>
</html>
