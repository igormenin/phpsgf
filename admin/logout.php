<?php
/**
 * Created by PhpStorm.
 * User: adminsms
 * Date: 17/10/16
 * Time: 16:37
 */
if (session_status() == PHP_SESSION_NONE){
	session_start();
}
session_destroy();
header('Location: login.php');

?>