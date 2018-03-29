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
        $id = $_GET['idPlano'];
        $sql = "SELECT nomePlano, descricao FROM plano WHERE id='$id'";    
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        
        if(mysql_num_rows($rs) > 0) {
    
        while ($plano = mysql_fetch_assoc($rs))
        {
         ?>
        
        <h2 align="center">Descrição Detalhada Plano</h2>
            <fieldset id="fsdescPlano" style="width:600px; text-align:center; margin:0 auto">               
                <?php echo $plano['descricao']; ?></textarea                
            </fieldset>
        <br />
             
     <?php   
        }
     }
     ?>
           
        <p>
                 <td><input align="center" type="button" name="btnVoltarPlano" id="btnVoltarPlano" value="   Voltar Plano  " onclick="location.href='planos.php'" /></td>
        </p>
    </body>  
</html>