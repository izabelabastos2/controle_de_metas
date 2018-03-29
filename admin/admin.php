<?php 
session_start();
if (isset($_SESSION['idAdmin']))
    $idAdmin = $_SESSION['idAdmin'];
else
    header('Location: index.php');
include_once("includes/conexao.php");
$logado = $_SESSION['IdAdmin']; 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Controle de Metas - Administração</title>
</head>

<body>	
<h2 align="center">Controle de Metas - Administração</h2>
    <fieldset id="fsCadastros" style="width:600px; text-align:center; margin:0 auto">
        <legend><strong>Menu</strong></legend>
        <input type="button" name="btnPessoas" id="btnTarifas" value="        Pessoas        " onclick="location.href='pessoas.php'" />
        <input type="button" name="btnPlanos" id="btnpacotes" value="         Planos         " onclick="location.href='planos.php'" />
        <input type="button" name="btnCorrecao" id="btnTarifas" value="      Correções       " onclick="location.href='controle.php'" />
    </fieldset>


<br /> 
<p align="center">
  <input type="button" name="btnSair" id="btnSair" value="Sair do Gerenciamento" onclick="location.href='index.php?acao=sair'" />
</p>
</body>
</html>