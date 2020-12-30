<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/kickstart.js"></script>
    <!-- KICKSTART -->
    <link rel="stylesheet" href="./css/kickstart.css" media="all"/>

    <!-- KICKSTART -->
    <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
    <!--    <script type="text/javascript" src="./js/cliente.js"></script>-->


    <title>Cliente de Senhas - By Igor Menin (Secretaria Municipal da Saúde)</title>
</head>

<body style="background-color: #ede9e3;overflow: hidden;">
<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
session_destroy();
require_once('seguranca.php');

$usuarioBranco = (isset($_GET['nous'])) ? $_GET['nous'] : '';
$senhaBranco = (isset($_GET['nopa'])) ? $_GET['nopa'] : '';

$passWrong = (isset($_GET['passWrong'])) ? $_GET['passWrong'] : '';

$hostNameCliente = (isset($_GET['hostNameCliente'])) ? $_GET['hostNameCliente'] : '';;
$userDigit = (isset($_GET['userDigitado'])) ? $_GET['userDigitado'] : '';

$disableCode = '';
if (validaHOSTNAME($hostNameCliente) === false) {
    $mensagem = '<strong class="center col_12 alertaLogin">Estação: ' . $hostNameCliente . '<br><br>Computador não cadastrado.</strong>';
}
$camposErr = '';
$msgmErroCamposUsr = '';
$msgmErroCamposUsrEnd = '';

if ($usuarioBranco == '1') {
    $camposErr = 'o usuário';
}

if ($senhaBranco == '1') {
    if ($camposErr != '') {
        $camposErr .= ' e da senha';
    } else {
        $camposErr = 'a senha';
    }
}
if ($usuarioBranco == '1') {
    $msgmErroCamposUsr = 'error ';
}

if ($passWrong == '1' || $senhaBranco == '1') {
    $msgmErroCamposUsrEnd = 'error ';
}

if (!isset($passWrong) && $passWrong == '1' && $senhaBranco = '1') {
    $userDigit .= '#teste';
} else {
    $userDigit = '';
}

?>

<div class="col_12">
    <!--    <img class="img_centro" src="imagens/security_login.png"/>-->
    <img class="img_centro" src="../imagens/logo_sistema.png"/>
</div>
<div class="col_12">
    <h6 class="center col_12">SGF - Sistema de Gerenciamento de Filas</h6>
    <div class="col_4 right">Unidade:</div><div class="left col_8"><?php echo getLocalServidor(); ?></div>
    <p class="center col_12">Módulo Atendente</p>
</div>
<form action="./valida.php" method="post">
    <?php
    if (empty($mensagem)) {
        echo '<div class="col_12">';
        echo '    <input class="' . $msgmErroCamposUsr . 'col_12 column" id="txtusuario" type="text" placeholder="Usuário" name="txtusuario" autofocus/>';
        echo '    <input class="' . $msgmErroCamposUsrEnd . 'col_12 column" id="txtsenha" type="password" placeholder="Senha" name="txtsenha"/>';
        echo '    <input class="col_12" id="txthostname" type="hidden" placeholder="HOST-NAME" value="' . $hostNameCliente . '" name="txthostname"/>';
        echo '</div>';
        echo '<div class="col_6">';
        echo '    <button id="btnlogin" type="submit" class="medium pill col_12"><i class="fa fa-unlock-alt"></i> Acessar</button>';
        echo '</div>';
        echo '<div class="col_6">';
        echo '    <button id="btnreload" class="medium pill col_12" type="reset"><i class="fa fa-refresh"></i> Limpar</button>';
        echo '</div>';
        if (liberaPassRecovery() == true) {
            $_SESSION['recoverOrigem'] = 'cliente/login.php';
            echo '<div class="col_12 center"><a class="center" href="../admin/recoverypass.php?origem=cliente">Esqueci a Senha</a></div>';
        }
    } else {
        echo '<div class="col_12">';
        echo $mensagem;
        echo '</div>';
    }
    ?>

</form>
</body>
</html>