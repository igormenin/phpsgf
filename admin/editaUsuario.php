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
$nomeTela = 'Cadastro/Edição de Usuários';
$multiselect = true;
$telaEdicao = 'usuario';
include('topo.php');


$id_usuario = '';
$id_tipousuario = '';
$des_usuario = '';
$des_nome = '';
$des_email = '';
$ind_situacao = '';
$des_senha = '';
$des_tipoUsuario = '';
$des_olostechuser='';

$tagCheckADM = '';
$tagCheckUSR = 'checked="checked"';

$tagSelectedAtivo = ' selected ';
$tagSelectedInativo = '';

$servicos = getServicos();
$servicosUsuario[] = null;

if (!isset($tipoEntrada)) {

    $retBtn = get_post_action($_SESSION['idsusuarios']);
    $btnpress = '';
//    echo '<br>' . $retBtn . '<br>';
    if (strpos($retBtn, 'btn_') !== false) {
        $btnpress = 'btn_';
    } elseif (strpos($retBtn, 'bpass_') !== false) {
        $btnpress = 'bpass_';
    }
//    echo '<script>alert("' . $btnpress . '");</script>';
    $id_usuariobusca = str_replace($btnpress, '', $retBtn);


    $usuarioSelec = getUsuario($id_usuariobusca);

    $id_usuario = $usuarioSelec[0]['id_usuario'];
    $id_tipousuario = $usuarioSelec[0]['id_tipousuario'];
    $des_usuario = $usuarioSelec[0]['des_usuario'];
    $des_nome = $usuarioSelec[0]['des_nome'];
    $des_email = $usuarioSelec[0]['des_email'];
    $ind_situacao = $usuarioSelec[0]['ind_situacao'];
    $des_senha = $usuarioSelec[0]['des_senha'];
    $des_tipoUsuario = $usuarioSelec[0]['des_sigla'];
    $des_olostechuser = $usuarioSelec[0]['des_olostechuser'];


    $servicosUsuario = getServicosUsuario($id_usuario);

    if ($des_tipoUsuario == 'adm') {
        $tagCheckADM = 'checked="checked"';
        $tagCheckUSR = '';
    } else {
        $tagCheckUSR = 'checked="checked"';
        $tagCheckADM = '';
    }
    if ($ind_situacao == '1') {
        $tagSelectedAtivo = ' selected ';
        $tagSelectedInativo = '';
    } else {
        $tagSelectedAtivo = '';
        $tagSelectedInativo = ' selected ';
    }
    // echo '<br>' . $btnpress . '<br>';
    if ($btnpress == 'bexcl_') {
        $textQuest = 'Deseja excluir o usuário ' . $des_usuario . ' ?';
        echo '<script>';
        echo ' x = confirm("' . $textQuest . '");';
        echo ' if(x == true){';
        echo ' window.location.replace("./deletaUser.php?idUser=' . $id_usuario . '");';
        echo ' }else{';
        echo ' window.location.replace("./index.php");';
        echo ' }';
        echo '</script>';
    }
    if ($btnpress == 'bpass_') {
        header('Location: ./recoverypass.php?recouser=' . $des_usuario );
    }

}


?>

<div id="wrapper">
    <div id="page-wrapper">
        <form id="formCadastro" name="formulario" class="form-horizontal" method="post" action="./saveUser.php">
            <fieldset>
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="id">ID Sistema</label>
                        <div class="col-md-6">
                            <input readonly="readonly" id="id" name="id" placeholder="" class="form-control input-md"
                                   type="text" <?php echo 'value="' . $id_usuario . '"' ?>>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="radio_tipo_usuario">Tipo Usuário</label>
                    <div class="col-md-6">
                        <div class="radio">
                            <label for="radio_tipo_usuario-0">
                                <input name="radio_tipo_usuario" id="radio_tipo_usuario-0"
                                       value="usr" <?php echo $tagCheckUSR; ?> type="radio">
                                Usuário
                            </label>
                        </div>
                        <div class="radio">
                            <label for="radio_tipo_usuario-1">
                                <input name="radio_tipo_usuario" id="radio_tipo_usuario-1"
                                       value="adm" <?php echo $tagCheckADM; ?> type="radio">
                                Administrador
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="des_usuario">Usuário</label>
                    <div class="col-md-6">
                        <input id="des_usuario" name="des_usuario" placeholder="" class="form-control input-md"
                               required="" type="text" <?php echo 'value="' . $des_usuario . '"' ?> >

                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="des_nome">Nome</label>
                    <div class="col-md-6">
                        <input id="des_nome" name="des_nome" placeholder="" class="form-control input-md" required=""
                               type="text" <?php echo 'value="' . $des_nome . '"' ?> >
                        <span class="help-block">Nome Completo</span>
                    </div>
                </div>

                <!-- Appended Input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="des_email">E-mail</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input id="des_email" name="des_email" class="form-control" placeholder="" required=""
                                   type="text" <?php echo 'value="' . str_replace('@jaraguadosul.sc.gov.br', '', $des_email) . '"' ?> >
                            <span class="input-group-addon">@jaraguadosul.sc.gov.br</span>
                        </div>
                        <p class="help-block">E-mail interno da prefeitura</p>
                    </div>
                </div>
                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="des_senha">Senha</label>
                    <div class="col-md-6">
                        <input id="des_senha" name="des_senha" placeholder="" class="form-control input-md"
                               type="password">

                    </div>
                </div>

                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="redes_senha">Confirmar Senha</label>
                    <div class="col-md-6">
                        <input id="redes_senha" name="redes_senha" placeholder="" class="form-control input-md"
                               type="password">

                    </div>
                </div>

                <!-- Text input-->
                <!-- <div class="form-group">
                    <label class="col-md-4 control-label" for="des_des_olostechuser">Usuário Olostech</label>
                    <div class="col-md-6">
                        <input id="des_des_olostechuser" name="des_des_olostechuser" placeholder="" class="form-control input-md" type="text" <?php echo 'value="' . $des_olostechuser . '"' ?> >
                    </div>
                </div> -->

                <!-- Select Basic -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="ind_situacao">Situação</label>
                    <div class="col-md-6">
                        <select id="ind_situacao" name="ind_situacao" class="form-control">
                            <option value="1" <?php echo $tagSelectedAtivo; ?> >Ativo</option>
                            <option value="0" <?php echo $tagSelectedInativo; ?> >Inativo</option>
                        </select>
                    </div>
                </div>
                <!-- Select Multiple -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="servicos_selecteds">Serviços</label>
                    <div class="col-md-5">
                        <select id="servicos_selecteds" name="servicos_selecteds[]" class="form-control" multiple="multiple">
                            <?php
                            if (count($servicos) > 0)
                                foreach ($servicos as $registro) {
                                    $selecteddd = '';
                                    if(validaExisteServicoUsuario($servicosUsuario,$registro["id_servico"])){
                                        $selecteddd = 'selected';
                                    }
                                    echo '<option ' . $selecteddd . ' value="' . $registro["id_servico"] . '">' . $registro["des_descricao"] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <!-- Button (Double) -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="btn_confirma"> </label>
                    <div class="col-md-8">
                        <button id="btn_confirma" name="btn_confirma" class="btn btn-success"
                                onsubmit="return validarFormulario(this)">Confirmar
                        </button>
                        <button id="btn_cancel" name="btn_cancel" class="btn btn-danger">Cancelar</button>
                    </div>
                </div>

            </fieldset>
        </form>


    </div>
</div>
