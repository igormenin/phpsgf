<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 04/10/16
 * Time: 14:56
 */

if (session_status() == PHP_SESSION_NONE){
	session_start();
}

require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");


$tipoEntrada = $_GET['tipoEntrada'];
$nomeTela = 'Cadastro/Edição de Botões';
$multiselect = true;
$telaEdicao = 'botao';
include('topo.php');


$idBotao = '';
$codTeclado = '';
$descricao = '';
$idServico = '';

$servicos = getServicos();

$servicosUsuario[] = null;

if (!isset($tipoEntrada)) {

    $retBtn = get_post_action($_SESSION['idsbotoes']);
    $btnpress = '';
    if (strpos($retBtn, 'btn_') !== false) {
        $btnpress = 'btn_';
    } elseif (strpos($retBtn, 'bexcl_') !== false) {
        $btnpress = 'bexcl_';
    }
    $idbotaoClick = str_replace($btnpress, '', $retBtn);
    $botao = buscaBotao($idbotaoClick);

    if (count($botao) > 0) {
        $id_botao = $botao[0]['id_botao'];
        $cod_botaoteclado = $botao[0]['cod_botaoteclado'];
        $descricao = $botao[0]['des_descricao'];
        $idServico = $botao[0]['id_servico'];
    }

}

?>

    <div id="wrapper">
        <div id="page-wrapper">
            <form id="formCadastro" name="formulario" class="form-horizontal" method="post" action="./botaoSave.php">
                <fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="id">ID Sistema</label>
                            <div class="col-md-6">
                                <input readonly="readonly" id="id" name="id" placeholder="" class="form-control input-md"
                                       type="text" <?php echo 'value="' . $id_botao . '"' ?>>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="cod_botaoteclado">Cód. Teclado</label>
                            <div class="col-md-6">
                                <input readonly="readonly" onkeyup="eventkeyup(event);" id="cod_botaoteclado" name="cod_botaoteclado" placeholder="" class="form-control input-md"
                                       type="text" <?php echo 'value="' . $cod_botaoteclado . '"' ?>>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="des_descricao">Descrição Teclado</label>
                            <div class="col-md-6">
                                <input readonly="readonly" id="des_descricao" name="des_descricao" placeholder="" class="form-control input-md"
                                       type="text" <?php echo 'value="' . $descricao . '"' ?>>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </fieldset>
                    <!-- Multiple Radios -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="radio_servico">Serviço</label>
                        <div class="col-md-6">
                            <?php
                            if (count($servicos) > 0)
                                foreach ($servicos as $registro) {
                                    $selecteddd = '';
                                    $usedServ = '';
                                    $tchadoIni = "";
                                    $tachadoFim = "";
                                    $textUsed = "";
                                    if ($idServico == $registro["id_servico"]) {
                                        $selecteddd = 'checked="checked"';
                                    }
                                    if ($registro["id_botao"] != null) {
                                        if ($selecteddd == '') {
                                            $usedServ = " disabled ";
                                        }
                                        $textUsed = " (Já Usado)";
//                                        $tchadoIni = "<del>";
//                                        $tachadoFim = "</del>";
                                    }
                                    echo '<div class="radio">';
                                    echo '<label for="radio_servico-' . $registro["id_servico"] . '">';
                                    echo '<input name="radio_servico" id="radio_servico-' . $registro["id_servico"] . '" value="' . $registro["id_servico"] . '" ' . $selecteddd . ' type="radio" ' . $usedServ . '>' . $tchadoIni . $registro["des_descricao"] . $tachadoFim . $textUsed . '</imput>';
                                    echo '</label></div>';
                                }
                            ?>
                        </div>
                        <div class="col-md-4"></div>
                    </div>


                    <!-- Button (Double) -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="btn_confirma"> </label>
                        <div class="col-md-6">
                            <button id="btn_confirma" name="btn_confirma" class="btn btn-success">Confirmar</button>
                            <button id="btn_cancel" name="btn_cancel" class="btn btn-danger">Cancelar</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                </fieldset>
            </form>


        </div>
    </div>
    <script>
        function eventkeyup(event) {
            var x = event.keyCode;

            var element = document.getElementById('cod_botaoteclado');
            var deselement = document.getElementById('des_descricao')
            element.value = x;
            deselement.value = "Referente à tecla: " + String.fromCharCode(x);

        }
    </script>


<?php
include_once('footer.php');
?>