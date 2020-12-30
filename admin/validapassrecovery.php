<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
require_once '../cliente/seguranca.php';
$senha = '';
$resenha = '';
unset($_SESSION['msgmSenha']);

if (isset($_POST['txtsenha'])) {
    $senha = $_POST['txtsenha'];
}
if (isset($_POST['txtresenha'])) {
    $resenha = $_POST['txtresenha'];
}

if ($senha == '' && $resenha == '') {
    redirecionaSenha('Preencha os campos abaixo!');
}
echo 'Pós validar Senha em Branco<br>';
if ($senha != $resenha) {
    redirecionaSenha('Senhas são diferentes.');
} else {
    $retorno = atualizaSenhaRecovery($_SESSION['recohash'], $senha);
    if ($retorno != true) {
        redirecionaSenha($retorno);
    }
    $redirLink = geraLinkRecuperacao($_SESSION['recohash']) . '&sucesso=ok';
    if (isset($_SESSION['http_origem_cliente'])){
        $redirLink .= '&origem=cliente';
    }
    unset($_SESSION['recohash']);
    unset($_SESSION['msgmSenha']);
    header('Location: ' . $redirLink);
}

function redirecionaSenha($pErro = '')
{
    if ($pErro != '') {
        $_SESSION['msgmSenha'] = $pErro;
    }
    $link = geraLinkRecuperacao($_SESSION['recohash']);
    header('Location: ' . $link);
}