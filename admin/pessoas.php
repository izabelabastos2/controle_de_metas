<?php 
session_start();
if (isset($_SESSION['idAdmin'])){
    include_once("includes/conexao.php");
    $logado = $_SESSION['idAdmin']; 
    $nome_logado = $_SESSION['nomeAdmin'];
}else{
    header('Location: index.php');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 
    <title>Controle de Metas</title>
</head>

<body>	
    <h2 align="center"> Pessoas </h2>
    <br />
    
    <table width="1000" border="1" align="center" cellpadding="2" cellspacing="2">
        <tr>
            <th>nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Celular</th>
            <th>Data de Nascimento</th>
            <th>Endereço</th>
            <th>cargo</th>
            <th>Login</th>
            <th>Editar</th>
        </tr>
<?php 
    $sql_root = "SELECT privilegio FROM pessoas WHERE id='$logado'";    
    $rs_root = mysql_query($sql_root, $conexao) or die(mysql_error());
    $verifica_root_rs = mysql_fetch_array($rs_root);
    $verifica_root = $verifica_root_rs['privilegio'];
    
        
    if($verifica_root == '1'){
        $sql = "SELECT * FROM pessoas";    
        $rs = mysql_query($sql, $conexao) or die(mysql_error());

            if(mysql_num_rows($rs) > 0) {

                while ($pessoa = mysql_fetch_assoc($rs))
            {

            ?>
                <tr>
                    <td><?php echo $pessoa['nome']; ?></td>
                    <td><?php echo $pessoa['cpf']; ?></td>
                    <td><?php echo $pessoa['email']; ?></td>
                    <td><?php echo $pessoa['celular']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime( $pessoa['datanasc']));?></td>
                    <td><?php echo $pessoa['endereco']; ?></td>
                    <td><?php echo $pessoa['cargo']; ?></td>
                    <td><?php echo $pessoa['login']; ?></td>
                   
                    <td align="center"><a href="cadPessoas.php?idPessoa=<?php echo $pessoa['id']; ?>&acao=altera">Editar</a></td>
                </tr>


            <?php
                    }
            }
    }
    
    
    else {
        $sql_usuario = "SELECT * FROM pessoas WHERE id = '$logado'";    
        $rs_usuario = mysql_query($sql_usuario , $conexao) or die(mysql_error());
        $pessoa = mysql_fetch_array($rs_usuario);
        
        if(mysql_num_rows($rs_usuario) > 0) { 
            ?>
        <tr>
                    <td><?php echo $pessoa['nome']; ?></td>
                    <td><?php echo $pessoa['cpf']; ?></td>
                    <td><?php echo $pessoa['email']; ?></td>
                    <td><?php echo $pessoa['celular']; ?></td>
                    <td><?php echo $pessoa['datanasc']; ?></td>
                    <td><?php echo $pessoa['endereco']; ?></td>
                    <td><?php echo $pessoa['cargo']; ?></td>
                    <td><?php echo $pessoa['login']; ?></td>
                    <td align="center"><a href="cadPessoas.php?idPessoa=<?php echo $pessoa['id']; ?>&acao=altera">Editar</a></td>
        </tr>
        <?php 
        
            
        }else{ ?>
        <tr>
            <td align="center" colspan="5">Nenhum registro encontrado</td>
        </tr>
    <?php
        }
    }
    ?>

    </table>
    <p align="center">
  
     <?php if($verifica_root == '1'){  ?>
        
        <p align="center"><input align="center" type="button" name="btnCadastrarPessoas" id="btnCadastrarPessoas" value="   Cadastrar Pessoas  " onclick="location.href='cadPessoas.php?criar=1'" /> <input align="center" type="button" name="btnVolta" id="btnVoltarPessoas" value="   Voltar   " onclick="location.href='admin.php'" /></p>
     <?php }else {?>
        <p align="center"><input align="center" type="button" name="btnVolta" id="btnVoltarPessoas" value="   Voltar   " onclick="location.href='admin.php'" /></p> 
    <?php   }?>
        
    </p>
    </body>
    </html>

