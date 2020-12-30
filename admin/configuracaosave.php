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
    unset($arrayInfo);
    $arrayInfo = array();
    $arrayInfo['des_tipoLocal'] = $_POST['des_tipoLocal'];
    $arrayInfo['des_nomeUnidade'] = $_POST['des_nomeUnidade'];
    $arrayInfo['des_modeloprint'] = $_POST['des_modeloprint'];
    $arrayInfo['ind_tipoprint'] = $_POST['ind_tipoprint'];
    $arrayInfo['des_enderecoprint'] = $_POST['des_enderecoprint'];
    $arrayInfo['des_portaprintnetwork'] = $_POST['des_portaprintnetwork'];
    $arrayInfo['des_subcabecalho'] = $_POST['des_subcabecalho'];
    $arrayInfo['des_subcabecalho2'] = $_POST['des_subcabecalho2'];
    $arrayInfo['int_intervalopresskey'] = $_POST['int_intervalopresskey'];
    $arrayInfo['log_consultMedico'] = $_POST['log_consultMedico'];
    $arrayInfo['log_permiterecoverypass'] = $_POST['log_permiterecoverypass'];
    $arrayInfo['log_vinculartipoestacapservico'] = $_POST['log_vinculartipoestacapservico'];
    $arrayInfo['num_limiterechamar'] = $_POST['num_limiterechamar'];

//    echo '<pre>' . var_dump($arrayInfo) . '</pre>';

    if (isset($_POST['btn_confirma']) == true) {
        try {
            $retorno = salvaConfiguracaoGeral($arrayInfo);
            if ($retorno == 'OK'){
                $_SESSION['tipoRetorno'] = 'success';
                $_SESSION['mensagemRetorno'] = 'Informação salva com sucesso!';
                header('Location: configuracao.php');
            } else {
                $_SESSION['tipoRetorno'] = 'warning';
                $_SESSION['mensagemRetorno'] = 'Sem confirmação que os dados foram salvos!';
                header('Location: configuracao.php');
            }


        } catch (Exception $e) {
            $_SESSION['tipoRetorno'] = 'danger';
            $_SESSION['mensagemRetorno'] = 'Ocorreu um erro ao salvar.<br>Erro: ' . $e->getMessage();
            $arrayInfo['id_configuracao'] = $_POST['id_configuracao'];
            $_SESSION['ArrayConfigError'] = $arrayInfo;
            header('Location: configuracao.php');
        }
    } else {
        header('Location: configuracao.php');
    }
}

?>