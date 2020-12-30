<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
require_once('./conexao.php');
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php?hostNameCliente=' . $_SESSION['hostNameClient']);
}
if (!isset($_SESSION['hostNameClient']) && $_SESSION['hostNameClient'] == '') {
    header('Location: bloqueioacesso.php');
}

$conexao = Conexao::getInstance();
$sql = 'SELECT *,(select des_tipoLocal from configuracao ORDER BY id_configuracao DESC LIMIT 1) as des_tipoLocal from estacoes where hostname="' . $_SESSION['hostNameClient'] . '" LIMIT 1';
$stm = $conexao->prepare($sql);
$stm->execute();
$resultado = $stm->fetchAll(PDO::FETCH_ASSOC);
if (isset($resultado[0]["hostname"])) {
    $_SESSION['localAtual']= $resultado[0]["des_tipoLocal"] . ' ' . $resultado[0]["numero"];
}

$id_senha = '';
$numSenha = '';
$tipoSenha = '';

$btnChamarVisible = '';
$btnRechamarVisible = '';
$btnNaoCompareceuVisible = '';
$btnEncerrarAtendimentoVisible = '';
$btnSairVisible = '';
//$visibleCode = 'disabled="disabled"';
$visibleCode = 'disabled';
if (isset($_SESSION['id_senha']) && $_SESSION['id_senha'] != null) {
    $id_senha = $_SESSION['id_senha'];
    $tipoSenha = (isset($_SESSION['des_descricao'])) ? $_SESSION['des_descricao'] : '';
    $numSenha = (isset($_SESSION['num_sequencia'])) ? $_SESSION['num_sequencia'] : '';
    $btnSairVisible = $visibleCode;
} else {
    $btnEncerrarAtendimentoVisible = $visibleCode;
    $btnNaoCompareceuVisible = $visibleCode;
    $btnRechamarVisible = $visibleCode;
}
$controlaBuscaSenhasNovas = ' onload="monitoraBotaoChamar();"';
if (isset($id_senha) && $id_senha != '') {
    $btnChamarVisible = $visibleCode;
    $controlaBuscaSenhasNovas = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/cliente.css" type="text/css" media="screen">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/kickstart.js"></script>
    <?php echo '<script id="jscliente" src="./js/cliente.js" idsenhaValue="' . $id_senha . '"></script>'; ?>
    <!-- KICKSTART -->
    <link rel="stylesheet" href="./css/kickstart.css" media="all"/>
    <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
    <!-- KICKSTART -->
    <title>Cliente de Senhas - By Igor Menin (Secretaria Municipal da Saúde)</title>
</head>
<body<?php echo($controlaBuscaSenhasNovas); ?> >
<form action="./validaCliente.php" method="post">
    <div class="container">
        <div class="contents">
            <h3><b>SCS - Sistema de Controle de Senhas<br>Secretaria Municipal da Saúde - PMJS</b></h3>

            <div class="h3 tooltip"><b>Usuário: </b><?= ' ' . $_SESSION['des_usuario'] ?>
                <span class="tooltiptext"><?= $_SESSION['des_nome'] ?></span>
            </div>
            <div class="h3"><b>Estação: </b><?= $_SESSION['hostNameClient']; ?> ( <b><i><?= $_SESSION['localAtual'] ?></i></b> )</div>
        </div>
        <div class="senhas">
            <h6>SENHA ATUAL</h6>

            <h4><?= $tipoSenha; ?></h4>

            <h3><?= $numSenha; ?></h3>
        </div>
        <div class="divButtons">
            <?php
            if ($btnChamarVisible == '') {
                echo '<button id="btnChamar" type="submit" name="btnChamar" class="col_12 fa fa-plus" tabindex="1"> Chamar</button>';
            }
            if ($btnRechamarVisible == '') {
                echo '<button id="btnRechamar" type="submit" name="btnRechamar" class="col_12 fa fa-refresh" tabindex="1"> Re-Chamar</button>';
            }
            if ($btnNaoCompareceuVisible == '') {
                echo '<button id="btnNaoCompareceu" type="submit" name="btnNaoCompareceu" class="col_12 fa fa-ban" tabindex="2" > Não Compareceu!</button>';
            }
            if ($btnEncerrarAtendimentoVisible == '') {
                echo '<button id="btnEncerrar" type="submit" name="btnEncerrar" class="col_12 fa fa-times" aria-hidden="true" tabindex="3"  > Encerrar Atendimento</button>';
            }

            ?>
        </div>
        <div class="divSair">
            <?php
            if ($btnSairVisible == '') {
                echo '<button id="btnSair" type="submit" name="btnSair" class="col_12 column fa fa-sign-out" aria-hidden="true" tabindex="4" > Sair</button>';
            }
            ?>
        </div>
    </div>
</form>
</body>
</html>