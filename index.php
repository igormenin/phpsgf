<?php
$displayHostName = $_GET['hostname'];

if (!empty($displayHostName))
{
	setcookie('hostname',$displayHostName,strtotime( '+120 days' ));
}
if (!empty($_COOKIE['hostname']))
{
    $displayHostName = $_COOKIE['hostname'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script src="./njs/jquery.min.js"></script>
    <script src="./njs/bootstrap.min.js"></script>
    <script src="./js/funcoes_painel.js"></script>

    <!--Novo Modelo de CSS-->

    <link href="./css/style.css" rel="stylesheet">
    <link href="./ncss/bootstrap.min.css" rel="stylesheet">
    <!--Fim do Novo Modelo de CSS-->


    <title>Painel de Senhas - v2.0</title>


</head>
<body class="bodybody" onload="relogio(); buscaregistrosenha('<?php echo $displayHostName; ?>'); buscaTeclas(); buscaConfigIntervaloTeclas()" onkeyup="eventkeyup(event)">

<div class="container-fluid" id="container_geral">
    <div class="row">
        <div class="col-md-3">
            <img id="img_logo" src="./imagens/secretaria_municipal_de_saude_2018.png"/>
        </div>
        <div class="col-md-9" id="div_data_hora">
            <a id="data_hora"></a>
        </div>
    </div>

    <div class="row" id="divmidle">
        <div class="col-md-9" id="atualgeral">
            <div class="col-md-12" id="atualatual">
                <table class="tblatual">
                    <tr>
                        <th class="senha_atual" id="txt_senhaatual"></th>
                    </tr>
                    <tr>
                        <th class="local_atual" id="txt_localatual"></th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-3" id="anterioresgeral">
            <div class="table-responsive" id="anteriores">
                <table id="tableanteriores" class="table table-striped table-sm">
                    <thead class="cabecalhotable">
                    <tr>
                        <th scope="col">Número/Nome</th>
                        <th scope="col">Guichê</th>
                    </tr>
                    </thead>
                    <tbody class="numnomold" id="tbodyanteriores">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!--    <div class="row" id="fixedRodape">-->
<!---->
<!---->
<!--    </div>-->

</div>

<!--<audio id="audioalert" src="./media/campainha.wav"></audio>-->
<!--<audio id="audioalerttriagem" src="./media/triagem.mp3"></audio>-->
</body>

</html>
