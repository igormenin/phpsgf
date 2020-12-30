<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 30/10/2016
 * Time: 20:16
 */

require_once("../cliente/conexao.php");
include('../cliente/seguranca.php');

//include('topo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $des_tipoLocal=$_POST['des_tipoLocal'];
    $des_nomeUnidade=$_POST['des_nomeUnidade'];
    $des_modeloprint=$_POST['des_modeloprint'];
    $ind_tipoprint= $_POST['ind_tipoprint'];
    $des_enderecoprint= $_POST['des_enderecoprint'];
    $des_portaprintnetwork= $_POST['des_portaprintnetwork'];
    $des_subcabecalho= $_POST['des_subcabecalho'];
    $des_subcabecalho2= $_POST['des_subcabecalho2'];
    $int_intervalopresskey= $_POST['int_intervalopresskey'];
    $log_consultMedico= $_POST['log_consultMedico'];
    $log_permiterecoverypass= $_POST['log_permiterecoverypass'];
    $log_vinculartipoestacapservico = $_POST['log_vinculartipoestacapservico'];

    if (isset($_POST['btn_confirma']) == true) {
        
        header('Location: configuracao.php');
    } else {
        header('Location: configuracao.php');
    }
}
?>