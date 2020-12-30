<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 14/10/16
 * Time: 12:29
 */
include('top_html.php');
require_once('criamenu.php');
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
if (!isset($_SESSION['id_usuario'])) {
    session_destroy();
    header('Location: index.php');
}

?>
</head>
<body>


<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <a href="index.php" class="navbar-brand">SGF - Sist. Ger. de Filas</a>
        <a class="navbar-brand"><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></a>
    </div>
    <div class="navbar-header">
        <ul class="nav navbar-navsb top-nav">
            <?php
            echo montaMenu();
            ?>
        </ul>
    </div>

    <ul class="nav navbar-right top-nav">
        <li class="navbar-header">
            <a class="navbar-brand">Administração <i class="fa fa-angle-double-right"></i> <?php echo $nomeTela; ?>
                <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
            </a>
        </li>
        <li class="navbar-header">
            <a>Restante: <b id="countdown">10m 00s</b></a>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i> <?php echo $_SESSION['des_nome']; ?> <b class="caret"> </b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="logout.php">
                        <i class="fa fa-fw fa-power-off"></i>
                        Sair do Sistema
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>