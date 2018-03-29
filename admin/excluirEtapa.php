<?php
session_start();
if(isset($_SESSION['idAdmin'])) $idAdmin = $_SESSION['idAdmin']; else header('Location: index.php');
include_once("includes/conexao.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Controle de metas - Administração</title>
</head>

<body>	
    <h2 align="center">Controle de Metas</h2>
    <br />
    <?php
        include_once("includes/conexao.php");
        $idEtapa = $_GET['idEtapa'];
        $idPlano = $_GET['idPlano'];
        
        $sqlData ="SELECT * FROM data INNER JOIN etapa ON etapa.idData = data.id WHERE etapa.id ='$idEtapa'";
        $rsData = mysql_query($sqlData, $conexao) or die(mysql_error());
        $dadosData = mysql_fetch_array($rsData);
        $idData = $dadosData['id'];
        
        $sql = "DELETE FROM data WHERE id='$idData'";
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        
        $sqlDelEtapa = "DELETE FROM etapa WHERE id='$idEtapa'";
        $rsDelEtapa = mysql_query($sqlDelEtapa, $conexao) or die(mysql_error());
   
        echo '<p align="center"> etapa exluída com sucesso</p>';
    ?>
    <p align="center"><a href="etapa.php?idEtapa=<?php $idEtapa = $_GET['idEtapa'];echo $idEtapa; ?>&idPlano=<?php $id = $_GET['idPlano']; echo $id;?>">Voltar</a></p>

    </body>
    </html>