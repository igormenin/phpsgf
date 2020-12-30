<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 29/09/16
 * Time: 14:21
 */
$telaEdicao = 'login';
include("top_html.php");
require_once '../cliente/seguranca.php';
?>

<!--    <link href="./css/login.css" rel="stylesheet">-->

</head>

<body>

<div class="container">

    <form class="form-signin" method="post" action="./valida.php" >
        <img id="img_logo" src="../imagens/logo_sistema.png"/>
        <h3 class="form-signin-heading">SGF - Painel Administrativo</h3>
        <h4 class="form-signin-heading"><?php echo 'Unidade: ' . getLocalServidor(); ?></h4>
        <label for="inputEmail" class="sr-only">Usu&aacuterio</label>
        <input type="text" name="txtusuario" id="inputEmail" class="form-control" placeholder="Usuário" required autofocus>
        <label for="inputPassword" class="sr-only">Senha</label>
        <input type="password" name="txtsenha" id="inputPassword" class="form-control" placeholder="Senha" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><span class="fa fa-sign-in"></span> Entrar</button>
        <?php
         if (liberaPassRecovery() == true){
             $_SESSION['recoverOrigem']='login.php';
             echo '<div class="recoverypass"><a href="./recoverypass.php">Esqueci a Senha</a></div>';
         }
        ?>
    </form>

</div> <!-- /container -->
<?php
include_once('footer.php');
