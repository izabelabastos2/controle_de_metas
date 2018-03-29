<?php
session_start();
if (isset($_SESSION['idAdmin']))
    $idAdmin = $_SESSION['idAdmin'];
else
    header('Location: index.php');
include_once("includes/conexao.php");
var_dump($idAdmin);
?>