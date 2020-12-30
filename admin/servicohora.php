<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}

require_once("../cliente/conexao.php");
require_once("../cliente/seguranca.php");


$nomeTela = 'Hora dos Serviços';
//$multiselect = true;
$telaEdicao = 'servicohora';
include('topo.php');

$listaServicos = getServicos();


?>

    <div id="wrapper">
        <div id="page-wrapper">
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Serviço</th>
                        <th class="hidden-phone">Dia/Horas</th>
                        <!--                        <th class="hidden-phone"></th>-->
                    </tr>
                    </thead>
                    <form method="post" action="./servicohoraedita.php">
                        <?php
                        $_SESSION['idservicohora'] = null;
                        $ids = array();
                        $countID = 0;
                        foreach ($listaServicos as $servico) {
                            $ids[$countID] = 'btn_' . $servico['id_servico'];
                            $countID += 1;
//                        $ids[$countID] = 'bexcl_' . $registro['id_servicohora'];
//                        $countID += 1;

                            echo '<tr>';
                            echo '<td><span class="name">';
                            echo $servico['id_servico'];
                            echo '</span></td>';
                            echo '<td class="hidden-sm">';
                            echo $servico['des_descricao'];
                            echo '</td>';
                            echo '<td class="hidden-sm">';
                            $listaservicoshoras = array();
                            try {
                                $listaservicoshoras = getServicoHora(null, $servico['id_servico']);
                            } catch (Exception $e) {
                                echo "Erro: " . $e->getMessage() . "<br>Line: " . $e->getLine();
                            }
                            $count = 0;
                            $lastDiaSemana = "";

                            $echoar = '';
                            if (count($listaservicoshoras) > 0) {
                                foreach ($listaservicoshoras as $row) {
//                                    echo $row['diasemana'] . ' -> ' . $row['horainicio'] . ' <-> ' . $row['horafim'] .'<br>';
                                    if ($lastDiaSemana != $row['diasemana']) {
                                        if ($lastDiaSemana != '') {
                                            $echoar = '</pre>';
                                        }
                                        $echoar .= retornaDiaSemana($row['diasemana']) . ': <pre>';
                                        echo $echoar . date_format(date_create($row['horainicio']), 'H:i') . ' às ' . date_format(date_create($row['horafim']), 'H:i');
                                        $lastDiaSemana = $row['diasemana'];
                                        $count = 0;
                                    } else {
                                        echo ' | ' . date_format(date_create($row['horainicio']), 'H:i') . ' às ' . date_format(date_create($row['horafim']), 'H:i');
                                    }
//                                    if ($count > 0) {
//                                        $echoar = ' | ';
//                                    }
//                                    $count += 1;
                                }
                                echo '</pre>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        $_SESSION['idservicohora'] = $ids;
                        ?>
                    </form>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
include_once('footer.php');
?>