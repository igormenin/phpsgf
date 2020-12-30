<?php
    /**
     * Created by PhpStorm.
     * User: adminsms
     * Date: 29/09/16
     * Time: 14:21
     */
    $nomeTela = 'Lista de Usuários';
    $telaEdicao = 'usuario';
    include('topo.php');

if (session_status() == PHP_SESSION_NONE){
	session_start();
}
    
    require_once("../cliente/conexao.php");
    require_once("../cliente/seguranca.php");


    
    $checkUser = "";
    $usersAtivos = 1;
    
    if (isset($_SESSION['usuariosInativos'])){
        if ($_SESSION['usuariosInativos'] == 1){
            $checkUser = "checked";
            $usersAtivos = 0;
        }
    }

    $result = buscaUsuarios($_SESSION['id_usuario'], $usersAtivos);

?>
<div id="wrapper">
    <div id="page-wrapper">
        <!-- Default unchecked -->
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="showInativeUser" <?php echo $checkUser; ?> onclick="atualizaLista()" >
            <label class="custom-control-label" for="showInativeUser">Exibir usuários inativos</label>
        </div>
        <div class="table-responsive">
        <?php
            if (isset($_SESSION['tipoRetorno'])) {
                echo '<div class="alert alert-' . $_SESSION['tipoRetorno'] . '" role="alert">' . $_SESSION['mensagemRetorno'] . '</div>';
                unset($_SESSION['tipoRetorno']);
                unset($_SESSION['mensagemRetorno']);
            }
        ?>
        <form method="post" action="./editaUsuario.php">
            <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                <thead>
                <tr>
                    <th >#</th>
                    <th style="width:90px" >Usu&aacuterio</th>
                    <th>Nome</th>
                    <th style="width:300px" >E-mail</th>
                    <th style="width:120px" >Tipo</th>
                    <th style="width:70px" >Situa&ccedil&atildeo</th>
                    <th style="width:100px" ></th>
                </tr>
                </thead>
                <tbody>
                
                    <?php
                        $_SESSION['idsusuarios'] = null;
                        $ids = array();
                        if (count($result) > 0) {
                            $count = 0;
                            foreach ($result as $registro) {
                                $ids[$count] = 'btn_' . $registro['id_usuario'];
                                $count += 1;
                                $ids[$count] = 'bpass_' . $registro['id_usuario'];
                                $count += 1;

                                echo '<tr>';
                                echo '<td><span class="name">';
                                echo $registro['id_usuario'];
                                echo '</span></td>';
                                echo '<td class="hidden-sm">';
                                echo truncate($registro['des_usuario'], 10);
                                echo '</td>';
                                echo '<td class="hidden-sm">';
                                echo truncate($registro['des_nome'], 50);
                                echo '</td>';
                                echo '<td class="hidden-sm">';
                                echo $registro['des_email'];
                                echo '</td>';
                                
                                echo '<td class="hidden-sm tipoUserCenter">';
                                $confAdm="";
                                if ($registro['des_sigla'] == 'adm') {
                                    echo '<b><i>';
                                    $confAdm='</b></i>';
                                }
                                
                                echo ucfirst($registro['des_tipousuario']);
                                echo $confAdm;

                                echo '</td>';
                                
                                echo '<td class="hidden-sm" align="center">';
                                $situ = "danger";
                                if ($registro['ind_situacao'] == '1') {
                                    $situ = "success";
                                }
                                echo '<span class="label label label-' . $situ . '">';
                                echo $registro['des_situacao'];
                                echo '</span>';
                                echo '</td>';
                                echo '<td class="hidden-sm" align="center">';
                                if ($registro['ind_situacao'] == '1') {
                                    echo '<button name="bpass_' . $registro["id_usuario"] . '" class="btn btn-warning btn-xs btn-responsive" data-toggle="tooltip" data-placement="left" title="Envia para e-mail do usuário trocar senha esquecida." >  <span class="fa fa-key"></span> </button>';
                                }
                                echo '  <button name="btn_' . $registro["id_usuario"] . '" class="btn btn-primary btn-xs btn-responsive"> <span class="fa fa-edit"></span> Editar </button>';
                                echo '</div></td></tr>';
                            }
                            $_SESSION['idsusuarios'] = $ids;   
                        } else {
                            echo '<tr><td class="hidden-phone" colspan="7" align="center"><b><i>N&atildeo foi encontrado nenhum registro!</i></b></td> </tr>';
                        }
                    ?>
                
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>