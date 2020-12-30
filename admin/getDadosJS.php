<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 25/10/2016
 * Time: 23:25
 */

require_once("../cliente/seguranca.php");

$funcao = $_POST['funcao'];
$userdigitado = $_POST['userdigitado'];


if($funcao=='validaNewUser'){
    return validaUsuarioNovo($userdigitado);
}