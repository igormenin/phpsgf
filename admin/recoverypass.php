<?php
$divContainer = 'class="container"';
$telaEdicao = 'login';
include("top_html.php");
require_once '../cliente/seguranca.php';
if (isset($_GET['origem'])) {
        $_SESSION['http_origem_cliente'] = $_GET['origem'];
}

if (liberaPassRecovery() == false) {
    header('Location: login.php');
}
$actionRedir = './recoverypass.php';
$mensagem = '';
$msgmSucesso = '';
$typeMensagem = 'alert-info';
$recohash = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['txtusuario'])) {
        $mensagem = enviarecuperacaosenha($_POST['txtusuario']);
    } else {
        $mensagem = 'Usuário inválido!';
    }
} else {
    if (isset($_GET['recohash'])) {
        $recohash = $_GET['recohash'];
        $actionRedir = './validapassrecovery.php';
        $_SESSION['recohash'] = $recohash;
        if (isset($_GET['sucesso'])) {
            $msgmSucesso = 'Senha atualizada com sucesso!';
            $typeMensagem = 'alert-success';
        } else {
            if (validaTempoUsoHash($recohash) == false) {
                $typeMensagem = 'alert-danger';
                $mensagem = 'Tempo limite para recuperação de senha expirado!';
            } else {
                if (validaHashJaUsado($recohash) == true) {
                    $typeMensagem = 'alert-danger';
                    $mensagem = 'Link de recuperação já foi usado!';
                }
            }
        }
    } elseif (isset($_GET['recouser'])) {
        $mensagem = enviarecuperacaosenha($_GET['recouser']);
        $_SESSION['tipoRetorno'] = 'success';
        $_SESSION['mensagemRetorno'] = $mensagem;
        header('Location: usuarios.php');
    }
}


$msgmSenha = '';
if (isset($_SESSION['msgmSenha'])) {
    $msgmSenha = $_SESSION['msgmSenha'];
    unset($_SESSION['msgmSenha']);
}
$linkRedir = '';
if (isset($_SESSION['http_origem_cliente']) && $_SESSION['http_origem_cliente'] == 'cliente' && $mensagem != '') {
    $linkRedir = 'cliente';
    $actionRedir = '../cliente/limpasessao.php';
    $divContainer = '';
}
?>
    </head>

    <body>

<div <?php echo $divContainer; ?>>
    <form class="form-signin" method="post" action="<?php echo $actionRedir; ?>">
        <img id="img_logo" src="../imagens/logo_sistema.png"/>
        <h3 class="form-signin-heading">SGF - Recuperação de Senha</h3>
        <?php
        if ($mensagem != '') {
            echo '<div class="alert ' . $typeMensagem . '" role="alert"><p class="text-center">' . $mensagem . '</p></div>';
            $fechaJanela = 'href="./index.php" ';

            if ($linkRedir != '') {
                echo '<button class="btn btn-lg btn-warning btn-block" type="submit"><span class="fa fa-close"></span> Fechar</button>';
            } else {
                echo '<div class="recoverypass"><a href="./index.php">Acessar tela de Login</a></div>';
            }
        } else {
            if ($msgmSucesso != '') {
                echo '<div class="alert ' . $typeMensagem . '" role="alert"><p class="text-center">' . $msgmSucesso . '</p></div>';
                echo '<div class="recoverypass"><a href="./index.php" >Ir para Tela de Login</a></div>';
            } else {
                if ($recohash != '') {
                    if ($msgmSenha != '') {
                        echo '<div class="alert alert-danger" role="alert">' . $msgmSenha . '</div>';
                    }
                    echo '<input type="password" name="txtsenha" id="inputPassword" class="form-control" placeholder="Senha" required>';
                    echo '<input type="password" name="txtresenha" id="inputRePassword" class="form-control" placeholder="Repetir Senha" required>';
                    echo '<button class="btn btn-lg btn-primary btn-block" type="submit"><span class="fa fa-check-circle "></span> Atualizar Senha</button>';
                } else {
                    echo '<label for="inputEmail" class="sr-only">Usu&aacuterio</label>';
                    echo '<input type="text" name="txtusuario" id="inputEmail" class="form-control" placeholder="Usuário" required autofocus>';
                    echo '<button class="btn btn-lg btn-primary btn-block" type="submit"><span class="fa fa-undo"></span> Recuperar</button>';
                }
            }
        }
        ?>
    </form>
</div>
<?php

include_once('footer.php');
