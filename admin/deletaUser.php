<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 01/11/2016
 * Time: 23:16
 */
if (session_status() == PHP_SESSION_NONE){
	session_start();
}

require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");


$nomeTela = 'Deleta Usuários';

include('topo.php');

$iduser = $_GET['idUser'];



if (!is_null($iduser)) {
    $retorno = deletaUser($iduser);
    echo '<script>alert("Função Desabilitada!"); window.location.replace("./index.php");</script>';
//    echo '<script>alert("Excluído com sucesso!"); window.location.replace("./index.php");</script>';
} else {
    echo '<script>window.location.replace("./index.php");</script>';
}
