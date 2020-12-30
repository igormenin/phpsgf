<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 22/08/16
 * Time: 16:07
 */
require_once("./conexao.php");

$sql  = "DELETE FROM senhas ";
$sql .= "WHERE id_senha NOT IN(SELECT id_senha FROM senhaschamadas)";

$conexao = Conexao::getInstance();

$stm = $conexao->prepare($sql);
$stm->execute();


?>