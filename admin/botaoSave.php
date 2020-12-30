<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 30/10/2016
 * Time: 20:16
 */

$nomeTela = 'Cadastro/Edição de Botões';

require_once("../cliente/conexao.php");
include('../cliente/seguranca.php');

include('topo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $cod_botaoteclado = $_POST['cod_botaoteclado'];
    $des_descricao = $_POST['des_descricao'];
    $radio_servico = $_POST['radio_servico'];

    if (isset($_POST['btn_confirma']) == true) {
        if (empty($id)){
            $id=0;
        }
        $retorno = salvaBotao($id,$des_descricao,$cod_botaoteclado,$radio_servico);
        if ( $retorno == 'OK') {
            $ret=['sucesso','Salvo com sucesso!'];
            $_SESSION['retornoSalvoBotao']=$ret;
        } else{
            $ret=['erro','Erro ao tentar salvar!<br>' . $retorno];
            $_SESSION['retornoSalvoBotao']=$ret;
        }
//        echo var_dump($_SESSION['retornoSalvoBotao']);
        header('Location: botoes.php');
    } else {
        header('Location: botoes.php');
    }
}
?>