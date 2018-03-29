<?php
session_start();
if(isset($_SESSION['idAdmin'])) $idAdmin = $_SESSION['idAdmin']; else header('Location: index.php');
include_once("includes/conexao.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Controle de Metas</title>
</head>

<body>	
    <h2 align="center">Controle de Metas</h2>
    <br />
    <?php
        include_once("includes/conexao.php");
        $id = $_GET['idPessoa'];
        
        $sql = "SELECT * FROM pessoas WHERE id='$id'";
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        $dados_pessoas=mysql_fetch_array($rs);
        
        $sqlDelData = "DELETE FROM pessoas WHERE id='$id'";
        $rsDelData = mysql_query($sqlDelData, $conexao) or die(mysql_error());
   
        echo '<p align="center"> Cadastro exluído com sucesso</p>';
    ?>

    <p align="center"><a href="pessoas.php">Voltar</a></p>

    </body>
    </html>