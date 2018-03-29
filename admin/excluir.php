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
        $id = $_GET['idPlano'];
        
        //deleta dados de data relacionados ao Plano 
        $sqlIdData = "SELECT * FROM data INNER JOIN plano ON data.id = plano.idData WHERE plano.id='$id'";
        $rsIdData = mysql_query($sqlIdData, $conexao) or die(mysql_error());
        $auxIdData=mysql_fetch_array($rsIdData);
        $idDataResult = $auxIdData['id'];
        
        $sqlDelData = "DELETE FROM data WHERE id='$idDataResult'";
        $rsDelData = mysql_query($sqlDelData, $conexao) or die(mysql_error());
        
        
        //Deleta dados de Etapas relacionadas ao Plano
        $sqlIdEtapa = "SELECT * FROM etapa INNER JOIN plano ON plano.id = etapa.idPlanoEtapa WHERE plano.id='$id'";
        $rsIdEtapa = mysql_query($sqlIdEtapa, $conexao) or die(mysql_error());
        $auxIdEtapa=mysql_fetch_array($rsIdEtapa);
        $idEtapaResult = $auxIdEtapa['id'];

        $sqlData ="SELECT * FROM data INNER JOIN etapa ON etapa.idData = data.id WHERE etapa.id ='$idEtapaResult'";
        $rsData = mysql_query($sqlData, $conexao) or die(mysql_error());
        $dadosData = mysql_fetch_array($rsData);
        $idData = $dadosData['id'];
        
        $sqlDelDataEtapa = "DELETE FROM data WHERE id='$idData'";
        $rs = mysql_query($sqlDelDataEtapa, $conexao) or die(mysql_error());
        
        $sqlDelEtapa = "DELETE FROM etapa WHERE id='$idEtapaResult'";
        $rsDelEtapa = mysql_query($sqlDelEtapa, $conexao) or die(mysql_error());
        
        //Deleta dados de ações relacionadas a etapas que estão relacionadas ao plano
   
        

        //Deleta Plano
        
        $sql = "DELETE FROM plano WHERE id='$id'";
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        

   
        echo '<p align="center"> Plano exluído com sucesso</p>';
    ?>

    <p align="center"><a href="planos.php">Voltar</a></p>

    </body>
    </html>