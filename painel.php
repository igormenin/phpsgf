<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link href="css/painel_without_video.css" rel="stylesheet" >

    <script type="text/javascript" src="./js/funcoes_painel.js"></script>
    <script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
    <!--<script type="text/javascript" src="./js/ajax.js"></script>-->
    <title>Painel de Senhas - By Igor Menin (Secretaria Municipal da Saúde)</title>
</head>
<body onload="relogio();buscaregistrosenha()">
<div id="topo">
<div id="div_logo_sec">
    <img id="img_logo_sec" src="./imagens/secretaria_da_saude.png" />
</div>
<div id="div_logo_pref">
    <img id="img_logo" src="./imagens/logo_vertical.jpg" />
</div>
<div id="div_data_hora">
    <a id="data_hora"></a>
</div>
</div>
<div id="container_atual"  style="visibility: hidden">
    <div>
        <span class="senha_atual" id="txt_senhaatual">.</span>
    </div>
    <div>
        <span class="local_atual" id="txt_localatual">.</span>
    </div>
</div>
<div id="container" style="visibility: hidden">
    <a class="titulo_antigas">Ultimas Senhas Chamadas:</a><br>
    <div id="left" style="visibility: hidden">
        <a id="senha3" class="senhas_antigas">.</a><br>
        <a id="senha3_guiche" class="senha_antigas_guiche">.</a>
    </div>
    <div id="center" style="visibility: hidden">
        <a id="senha2" class="senhas_antigas">0</a><br>
        <a id="senha2_guiche" class="senha_antigas_guiche">.</a>
    </div>
    <div id="right" style="visibility: hidden">
        <a id="senha1" class="senhas_antigas">0</a><br>
        <a id="senha1_guiche" class="senha_antigas_guiche">.</a>
    </div>
</div>
<audio id="audioalert" src="./media/campainha.wav"></audio>
</body>

</html>