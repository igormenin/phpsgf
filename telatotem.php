<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link href="css/painel_without_video.css" rel="stylesheet" >
    <script type="text/javascript" src="./js/funcoes_painel.js"></script>
    <script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>

    <title>Painel TOTEM - By Igor Menin (Secretaria Municipal da Saúde)</title>
</head>
<body onload="relogio();buscaTeclas();" onkeyup="eventkeyup(event)">
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
<div id="container_atual">
    <div>
        <span >PAINEL</span>
    </div>
    <div>
        <span >SENHA TOTEM</span>
    </div>
</div>

</body>

</html>