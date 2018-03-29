<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
    <!--    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/> -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Controle de Metas</title>
    </head>

    <body>	
        
        
        <?php

        include_once("includes/conexao.php");
        $id1 = $_GET['idPlano'];
        $id = $_GET['idAction'];
        $sql = "SELECT nomeAction, descricao FROM action WHERE id='$id'";    
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        
        if(mysql_num_rows($rs) > 0) {
    
        while ($action = mysql_fetch_assoc($rs))
        {
         ?>
        
        <h2 align="center">Descrição Detalhada Ação</h2>
            <fieldset id="fsdescAcao" style="width:600px; text-align:center; margin:0 auto">               
                <?php echo $action['descricao']; ?></textarea                
            </fieldset>
        <br />
             
     <?php   
        }
     }
     ?>
           
        <p>
                 <td><input align="center" type="button" name="btnVoltarAction" id="btnVoltarAction" value=" Voltar Para Ação " onclick="location.href='action.php?idPlano=<?php echo $id1;?>'" /></td>
        </p>
    </body>  
</html>