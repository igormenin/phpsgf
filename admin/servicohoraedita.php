<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}

require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");


$nomeTela = 'Hora dos Serviços';
//$multiselect = true;
$telaEdicao = 'configuracao';
include('topo.php');

$retBtn = get_post_action($_SESSION['idservicohora']);
$id_servico = str_replace('btn_', '', $retBtn);


$servivo = getServicos($id_servico);


?>

<div id="wrapper">
    <div id="page-wrapper">
        <form id="formCadastro" name="formulario" class="form-horizontal" method="post" action="./servicohorasave.php">
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="des_servico">Serviço</label>
                    <div class="col-md-6">
                        <input id="des_servico" name="des_servico" placeholder="" class="form-control input-md"
                               required="" type="text" <?php echo 'value="' . $servivo['des_descricao'] . '"' ?> >
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>


<?php
include_once('footer.php');
?>