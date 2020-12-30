<?php
// Inclui o arquivo com o sistema de segurança
require_once("./seguranca.php");
require_once("./conexao.php");

$erroUsr = "";

$hostNameCliente = $_POST['txthostname'];

$userDigitado = $_POST['txtusuario'];
$passDigitado = $_POST['txtsenha'];



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($userDigitado)) {
        $erroUsr = "nous=1";
    }
    if ($erroUsr !== "") {
        $erroUsr .= "&";
    }
    if (isset($passDigitado)) {
        $erroUsr .= "nopa=1";
    }
    echo $erroUsr.'<br>';
    echo 'Usuario: ' . $userDigitado . '<br>Senha: ' . $passDigitado;

    //Se tiver algum campo não preenchido no login, o mesmo redireciona de volta para a página de login Informando os problemas.
    if ($erroUsr !== "") {
        //echo 'Usuario: ' . $userDigitado . '<br>Senha: ' . $passDigitado . '<br>Hash: ' . password_hash($passDigitado,PASSWORD_DEFAULT);
        header('Location: login.php?' . $erroUsr . '&hostNameCliente=' . $hostNameCliente);
    }

    $validLog = validaUsuario($userDigitado, $passDigitado);

    if ($validLog == true) {
        $_SESSION['hostNameClient']=$hostNameCliente;
        header('Location: poslogin.php');
    } else {
        //header('Location: login.php?passWrong=1&hostNameCliente=' . $hostNameCliente);
    }
}


?>