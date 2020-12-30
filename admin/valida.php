<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 29/09/16
 * Time: 15:30
 */

if (session_status() == PHP_SESSION_NONE){
	session_start();
}
require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");

$userDigitado = $_POST['txtusuario'];
$passDigitado = $_POST['txtsenha'];
echo $userDigitado . " - pass: " . $passDigitado . "<br>-";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validLog = validaUsuario($userDigitado, $passDigitado,"1");

    if ($validLog == true){
        echo 'True';
        header('Location: index.php');
    } else {
        echo 'False';
        session_destroy();
        header('Location: login.php');
    }
}
