<?php
    
    $nomeTela = 'Configuração - Botões';
    $telaEdicao = 'botoes';
    
    include('topo.php');

if (session_status() == PHP_SESSION_NONE){
	session_start();
}
    
    require_once("../cliente/conexao.php");
    require_once("../cliente/seguranca.php");
    
    $result = buscaBotoes();
$alerta = '';
if ($_SESSION['retornoSalvoBotao'] != ''){
    if ($_SESSION['retornoSalvoBotao'][0] == 'erro' ) {
        $alerta = '<div class="alert alert-danger" role="alert">' . $_SESSION['retornoSalvoBotao'][1] . '</div>';
    } else {
        $alerta = '<div class="alert alert-success" role="alert">' . $_SESSION['retornoSalvoBotao'][1] . '</div>';
    }
    unset($_SESSION['retornoSalvoBotao']);
}
?>
<div id="wrapper">
    <div id="page-wrapper">
        <?php if ($alerta != ''){ echo $alerta; unset($alerta);}?>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                <thead>
                <tr>
                    <th style="width:5%">ID</th>
                    <th style="width:10%">Código</th>
                    <th style="width:35%" class="hidden-phone">Refere à Tecla</th>
                    <th style="width:40%" class="hidden-phone">Serviço</th>
                    <th style="width:10%" class="hidden-phone"></th>
                </tr>
                </thead>
                <tbody>
                <form method="post" action="./editaBotao.php">
                    <?php
                        $_SESSION['idsbotoes'] = null;
                        $ids = array();
                        if (count($result) > 0) {
                            $count = 0;
                            foreach ($result as $registro) {
                                $ids[$count] = 'btn_' . $registro['id_botao'];
                                $count += 1;
                                echo '<tr>';
                                echo '<td><span class="name">' . $registro['id_botao'] . '</span></td>';
                                echo '<td class="hidden-sm">' . $registro['cod_botaoteclado'] . '</td>';
                                echo '<td class="hidden-sm">' . $registro['des_descricao'] . '</td>';
                                echo '<td class="hidden-sm">' . $registro['des_servico'] . '</td>';
                                echo '<td class="hidden-sm" align="center">';
                                echo '<button name="btn_' . $registro["id_botao"] . '" class="btn btn-primary btn-xs btn-responsive"> <span class="fa fa-edit"></span> Editar </button>';
                                echo '</div></td></tr>';
                            }
                            $_SESSION['idsbotoes'] = $ids;
                        } else {
                            echo '<tr><td class="hidden-phone" colspan="5" align="center"><b><i>N&atildeo foi encontrado nenhum registro!</i></b></td> </tr>';
                        }
                    ?>

                </form>

                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>



<?php
include_once('footer.php');