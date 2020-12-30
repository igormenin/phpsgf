<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Igor C. Menin - 09/2016">
    <link rel="icon" href="../favicon.ico">
    <title>Painel Administrativo - SGF - Sistema de Gerenciamento de Filas - Secretaria Municipal da Sa&uacutede de
        Jaragu&aacute do Sul</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
    <script src="./js/jquery.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <?php 
    if ($telaEdicao != "login")
        echo '<script src="./js/funcoesadm.js"></script>';
    ?>
    <?php
        
        if (isset($telaEdicao)) {
            if ($telaEdicao != 'login') {
                echo '<link href="./css/sb-admin.css" rel="stylesheet">';
                echo '<script src="./js/bootstrap_submenu.js"></script>';
                echo '<link href="./css/bootstrap_submenu.css" rel="stylesheet">';
                echo '<link href="./css/footer.css" rel="stylesheet">';
            }
            switch ($telaEdicao) {
                case 'usuario':
                    echo '<script src="./js/editaUsuario.js"></script>';
                    echo '<link href="./css/usuario.css" rel="stylesheet">';
                    break;
                case 'botoes':
//                    echo '<script src="./js/editaBotao.js"></script>';
                    break;
                case 'login':
                    echo '<link href="./css/login.css" rel="stylesheet">';
                    echo '<link href="./css/footer.css" rel="stylesheet">';
                    break;
            }
        }
        if (isset($multiselect) && $multiselect == true) {
            echo '<script src="./js/bootstrap_multiselect.js"></script>';
            echo '<link href="./css/bootstrap_multiselect.css" rel="stylesheet">';
        }
    ?>
