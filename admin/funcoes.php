<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
$funcao = $_POST['funcao'];
if ($funcao == 'atualizaListaUsuarios') {
  unset($_SESSION['usuariosInativos']);
  $inativo = false;
  if ($_POST['chkUsers'] == 1){
    $inativo = true;
  }
  $_SESSION['usuariosInativos']= $inativo;
}
?>