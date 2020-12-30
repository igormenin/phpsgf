<?php

$nomeTela = 'Inicial';
$telaEdicao = 'index';
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
}

if (isset($_SESSION['id_usuario'])) {
    include('topo.php');
    echo '<div class="divInicial">';
    echo '<p style="text-align: center;"><img src="../imagens/logo_sistema.png" alt="Logo" width="200" height="200" /></p>';
    echo '<p class="textInicial text-center" >SGF</p>';
    echo '<p class="textNomeInicial text-center" >Sistema de Gerenciamento de Filas</p>';
//    echo '<p style="text-align: center;"><img src="../imagens/secretaria_municipal_de_saude_2018.png" alt="Logo" width="400" height="70" /></p>';
    echo '</div>';
    include_once('footer.php');
}