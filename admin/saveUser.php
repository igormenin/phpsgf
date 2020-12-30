<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 30/10/2016
 * Time: 20:16
 */

require_once("../cliente/conexao.php");
include('../cliente/seguranca.php');

include('topo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $radio_tipo_usuario = $_POST['radio_tipo_usuario'];;
    $des_usuario = $_POST['des_usuario'];
    $des_nome = $_POST['des_nome'];
    $des_email = $_POST['des_email'];
    $des_senha = $_POST['des_senha'];
    $ind_situacao = $_POST['ind_situacao'];
    // $olostechuser = $_POST['olostechuser'];
    $id = $_POST['id'];

    $servicos_selecteds = $_POST['servicos_selecteds'];

    if (isset($_POST['btn_confirma']) == true) {
        $retorno = salvaUsuario($id, $des_usuario, $des_nome, $des_email, $des_senha, $ind_situacao, $radio_tipo_usuario, $servicos_selecteds);
        header('Location: usuarios.php');
    } else {
        header('Location: usuarios.php');
    }
}
?>