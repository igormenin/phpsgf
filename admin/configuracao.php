<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}

require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");


$nomeTela = 'Configuração do Sistema';
$multiselect = true;
$telaEdicao = 'configuracao';
include('topo.php');

$lastConfig = getLastConfiguracaoGeral();

$id = '';
$des_tipoLocal = '';
$des_nomeUnidade = '';
$des_modeloprint = '';
$ind_tipoprint = '';
$des_enderecoprint = '';
$des_portaprintnetwork = '';
$des_subcabecalho = '';
$des_subcabecalho2 = '';
$int_intervalopresskey = '';
$log_consultMedico = '';
$log_permiterecoverypass = '';
$log_vinculartipoestacapservico = '';
$num_limiterechamar = '';


if (isset($_SESSION['ArrayConfigError'])){
    $lastConfig = $_SESSION['ArrayConfigError'];
    unset($_SESSION['ArrayConfigError']);
}

if (isset($lastConfig)) {
    $id = $lastConfig['id_configuracao'];
    $des_tipoLocal = $lastConfig['des_tipoLocal'];
    $des_nomeUnidade = $lastConfig['des_nomeUnidade'];
    $des_modeloprint = $lastConfig['des_modeloprint'];
    $ind_tipoprint = $lastConfig['ind_tipoprint'];
    $des_enderecoprint = $lastConfig['des_enderecoprint'];
    $des_portaprintnetwork = $lastConfig['des_portaprintnetwork'];
    $des_subcabecalho = $lastConfig['des_subcabecalho'];
    $des_subcabecalho2 = $lastConfig['des_subcabecalho2'];
    $int_intervalopresskey = $lastConfig['int_intervalopresskey'];
    $log_consultMedico = $lastConfig['log_consultMedico'];
    $log_permiterecoverypass = $lastConfig['log_permiterecoverypass'];
    $log_vinculartipoestacapservico = $lastConfig['log_vinculartipoestacapservico'];
    $num_limiterechamar = $lastConfig['num_limiterechamar'];
}



?>


<div id="wrapper">
    <div id="page-wrapper">
        <?php
        if (isset($_SESSION['tipoRetorno'])) {
            echo '<div class="alert alert-' . $_SESSION['tipoRetorno'] . '" role="alert">' . $_SESSION['mensagemRetorno'] . '</div>';
            unset($_SESSION['tipoRetorno']);
            unset($_SESSION['mensagemRetorno']);
        }

        ?>
        <form id="formConfiguracao" name="formulario" class="form-horizontal" method="post" action="configuracaosave.php">
            <fieldset>
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="id">ID Sistema</label>
                        <div class="col-md-4">
                            <input readonly="readonly" id="id" name="id" placeholder="" class="form-control input-md"
                                   type="text" <?php echo 'value="' . $id . '"' ?>>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_tipoLocal">Tipo Local</label>
                        <div class="col-md-4">
                            <input id="des_tipoLocal" name="des_tipoLocal" placeholder="" class="form-control input-md" type="text" <?php echo 'value="' . $des_tipoLocal . '"' ?> >

                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_nomeUnidade">Unidade</label>
                        <div class="col-md-4">
                            <input id="des_nomeUnidade" name="des_nomeUnidade" placeholder=""
                                   class="form-control input-md" required=""
                                   type="text" <?php echo 'value="' . $des_nomeUnidade . '"' ?> >
                        </div>
                    </div>
                    <!-- Text input-->
                    <!-- Multiple Radios -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="radio_tipo_usuario-0">Modelo Impressora</label>
                        <div class="col-md-4">
                            <div class="radio">
                                <label for="radio_tipo_usuario-0">
                                    <input name="des_modeloprint" id="des_modeloprint"
                                           value="tmt20" checked="checked" type="radio">
                                    EPSON TM-T20
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Multiple Radios -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="radio_ind_tipoprint-0">Tipo Conexao Impressora</label>
                        <div class="col-md-4">
                            <div class="radio">
                                <label for="radio_ind_tipoprint-0">
                                    <input name="ind_tipoprint" id="ind_tipoprint" value="ip" checked="checked" type="radio">IP
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_enderecoprint">Endereço Impressora</label>
                        <div class="col-md-4">
                            <input id="des_enderecoprint" name="des_enderecoprint" placeholder="" class="form-control input-md" required=""
                                   type="text" <?php echo 'value="' . $des_enderecoprint . '"' ?> >
                        </div>
                    </div>


                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_portaprintnetwork">Porta Impressora</label>
                        <div class="col-md-4">
                            <input id="des_portaprintnetwork" name="des_portaprintnetwork" required="" placeholder="" class="form-control input-md"
                                   type="text" <?php echo 'value="' . $des_portaprintnetwork . '"' ?> >
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_subcabecalho">Sub-cabeçalho</label>
                        <div class="col-md-4">
                            <input id="des_subcabecalho" name="des_subcabecalho" placeholder="" class="form-control input-md" type="text" <?php echo 'value="' . $des_subcabecalho . '"' ?> >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="des_subcabecalho2">Sub-cabeçalho 2</label>
                        <div class="col-md-4">
                            <input id="des_subcabecalho2" name="des_subcabecalho2" placeholder="" class="form-control input-md" type="text" <?php echo 'value="' . $des_subcabecalho2 . '"' ?> >
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="int_intervalopresskey">Intervalo Emissão de
                            Senha</label>
                        <div class="col-md-2">
                            <select class="form-control input-md custom-select" id="int_intervalopresskey" name="int_intervalopresskey">

                                <?php
                                $textSegundos = ' Segundo';
                                for ($i = 1; $i <= 5; $i++) {
                                    $selected = '';
                                    if ($int_intervalopresskey == $i) {
                                        $selected = ' selected ';
                                    }
                                    echo '<option ' . $selected . ' value="' . $i . '">' . $i . $textSegundos . '</option>';
                                    $textSegundos = ' Segundos';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="log_permiterecoverypass">Recuperar Senha</label>
                        <div class="col-md-2">
                            <select class="form-control input-md custom-select-sm" id="log_permiterecoverypass" name="log_permiterecoverypass">
                                <?php
                                $selectTrue = '';
                                $selectFalse = '';
                                if ($log_permiterecoverypass == '1') {
                                    $selectTrue = ' selected ';
                                } else {
                                    $selectFalse = ' selected ';
                                }
                                echo '<option value="1" ' . $selectTrue . ' >Sim </option>';
                                echo '<option value="0" ' . $selectFalse . ' >Não </option>';
                                ?>

                            </select>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="log_consultMedico">Chama Painel dos Consultórios?</label>
                        <div class="col-md-2">
                            <select class="form-control input-md custom-select" id="log_consultMedico" name="log_consultMedico">
                                <?php
                                $selectTrue = '';
                                $selectFalse = '';
                                if ($log_consultMedico == '1') {
                                    $selectTrue = ' selected ';
                                } else {
                                    $selectFalse = ' selected ';
                                }
                                echo '<option value="1" ' . $selectTrue . ' >Sim</option>';
                                echo '<option value="0" ' . $selectFalse . ' >Não</option>';
                                ?>

                            </select>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="log_vinculartipoestacapservico">Vincular Estação ao Tipo de Serviço</label>
                        <div class="col-md-2">
                            <select class="form-control input-md custom-select" id="log_vinculartipoestacapservico" name="log_vinculartipoestacapservico">
                                <?php
                                $selectTrue = '';
                                $selectFalse = '';
                                if ($log_vinculartipoestacapservico == '1') {
                                    $selectTrue = ' selected ';
                                } else {
                                    $selectFalse = ' selected ';
                                }
                                echo '<option value="1" ' . $selectTrue . ' >Sim</option>';
                                echo '<option value="0" ' . $selectFalse . ' >Não</option>';
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="id">Limite Rechamar</label>
                        <div class="col-md-2">
                            <input id="num_limiterechamar" name="num_limiterechamar" placeholder="" class="form-control input-md" min="1" max="5" step="1"
                                   type="number" <?php echo 'value="' . $num_limiterechamar . '"' ?>>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="btn_confirma"> </label>
                        <div class="col-md-8">
                            <button id="btn_confirma" name="btn_confirma" class="btn btn-success"
                                    onsubmit="return validarFormulario(this)"><i class="fa fa-save"></i> Confirmar
                            </button>
                            <button id="btn_cancel" name="btn_cancel" class="btn btn-danger"><i class="fa fa-close"></i>
                                Cancelar
                            </button>
                        </div>
                    </div>
                </fieldset>
        </form>
    </div>
</div>

<?php include_once('footer.php') ?>