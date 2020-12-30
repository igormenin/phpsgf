<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 25/08/16
 * Time: 15:43
 */

if (session_status() == PHP_SESSION_NONE){
	session_start();
}
require_once("./conexao.php");

function buscaSenhaEmAberto(){
    try{
        $sql ="SELECT SC.id_senha as id_senha, ";
        $sql .="CONCAT(SER.sigla, substring('0000', -4 + LENGTH(SEN.num_sequencia)), SEN.num_sequencia) as num_sequencia, ";
        $sql .="SER.sigla as sigla, ";
        $sql .="SER.des_descricao as des_descricao ";
        $sql .="FROM senhaschamadas as SC ";
        $sql .="LEFT JOIN senhas as SEN ON SEN.id_senha=SC.id_senha ";
        $sql .="LEFT JOIN servico as SER ON SER.id_servico=SEN.id_servico ";
        $sql .="WHERE SC.log_encerrada=0 ";
        $sql .="AND SC.id_usuario= ". $_SESSION['id_usuario'];
        $conexao = Conexao::getInstance();
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
        limpaSenhasChamadas();

        if (isset($resultado[0])) {
            //Gravar dados na tabela de senhaschamadas
            $_SESSION['id_senha'] = $resultado[0]['id_senha'];
            $_SESSION['num_sequencia'] = $resultado[0]['num_sequencia'];
            $_SESSION['des_descricao'] = $resultado[0]['des_descricao'];
            $_SESSION['sigla'] = $resultado[0]['sigla'];
        }

        header('Location: index.php');
    }catch(Exception $e){
        limpaSenhasChamadas();
        echo '<script type="text/javascript">alert("Ocorreu um erro ao buscar senhas em aberto!\n"' . $e->getMessage() . ');window.location.replace("./index.php");</script>';
    }
}

function limpaSenhasChamadas()
{
    $_SESSION['id_senha'] = null;
    $_SESSION['num_sequencia'] = null;
    $_SESSION['des_descricao'] = null;
    $_SESSION['sigla'] = null;
}

buscaSenhaEmAberto();

?>