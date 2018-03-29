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
    <h2 align="center"> Controle de Metas - Correções</h2>
    <br />
    
    <table width="1000" border="1" align="center" cellpadding="2" cellspacing="2">
        <tr>
            <th>Título Plano</th>
            <th>Descrição</th>
            <th>Responsável</th>
            <th>Correção</th>
            <th>Responsável Correção</th>
            <th>Editar</th>

        </tr>
<?php
  $sql_root = "SELECT privilegio FROM pessoas WHERE id='$logado'";    
  $rs_root = mysql_query($sql_root, $conexao) or die(mysql_error());
  $verifica_root_rs = mysql_fetch_array($rs_root);
  $verifica_root = $verifica_root_rs['privilegio'];
    
   if($verifica_root == '1'){
    
    if(isset($_GET['ordenacao'])) {
            switch($_GET['ordenacao']) {
                    case 'id' : $ordenacao = 'id'; break;
                    case 'nome' : $ordenacao = 'nome'; break;
                    case 'descricao' : $ordenacao = 'descricao'; break;
                    case 'status' : $ordenacao = 'status'; break;
                    case 'dataInicio' : $ordenacao = 'dataInicio'; break;
                    case 'dataFim' : $ordenacao = 'dataFim'; break;
                    case 'responsavel' : $ordenacao = 'responsavel'; break;

                    default : $ordenacao = 'id';
            }
    }else{ $ordenacao = 'id';
        $sql = "SELECT A.id id_controle, A.idPessoaControle, A.descricao descricao_controle, B.id, B.nomePlano, B.descricao, C.nome, D.nome nome_pessoa_controle FROM controle A INNER JOIN plano B ON A.idPlanoControle=B.id  INNER JOIN pessoas C ON B.idPessoa = C.id INNER JOIN pessoas D ON A.idPessoaControle = D.id WHERE A.idPessoaControle ='$logado' OR B.idPessoa = '$logado'  ORDER BY $ordenacao";    
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        }
    if(mysql_num_rows($rs) > 0) {
        
        while ($plano = mysql_fetch_assoc($rs))
    {            
     
    ?>
        <tr>
            <td align="center"><?php echo $plano['nomePlano']; ?></td>
            <td><a href="verDescricaoPlano.php?idPlano=<?php echo $plano['id']; ?>"><?php echo substr($plano['descricao'],0, 30); ?></a></td>
            <td align="center"><?php echo $plano['nome']; ?></td>
            <td><a href="verDescricaoControle.php?idControle=<?php echo $plano['id_controle']; ?>"><?php echo substr($plano['descricao_controle'],0, 30); ?></a></td>
            <td><?php echo $plano['nome_pessoa_controle']; ?></td>
            
            <?php if($plano['idPessoaControle'] == $logado){  ?>
            <td align="center"><a href="cadControle.php?idControle=<?php echo $plano['id_controle']; ?>&acao=altera">Editar</a></td>
            <?php } else{?>
            <td align="center">&nbsp;</td>   
           <?php } ?>
        </tr>
        

    <?php
        }   
    }
   }
    if($verifica_root != '1'){
        if(isset($_GET['ordenacao'])) {
                switch($_GET['ordenacao']) {
                        case 'id' : $ordenacao = 'id'; break;
                        case 'nome' : $ordenacao = 'nome'; break;
                        case 'descricao' : $ordenacao = 'descricao'; break;
                        case 'status' : $ordenacao = 'status'; break;
                        case 'dataInicio' : $ordenacao = 'dataInicio'; break;
                        case 'dataFim' : $ordenacao = 'dataFim'; break;
                        case 'responsavel' : $ordenacao = 'responsavel'; break;

                        default : $ordenacao = 'id';
                }
        }else{ $ordenacao = 'id';
            $sql = "SELECT A.id id_controle, A.idPessoaControle, A.descricao descricao_controle, B.id, B.nomePlano, B.descricao, C.nome FROM controle A INNER JOIN plano B ON A.idPlanoControle=B.id  INNER JOIN pessoas C ON B.idPessoa = C.id WHERE B.idPessoa = '$logado' ORDER BY $ordenacao";    
            $rs = mysql_query($sql, $conexao) or die(mysql_error());
            }
        if(mysql_num_rows($rs) > 0) {

            while ($plano = mysql_fetch_assoc($rs)){
                
        ?>
        <tr>
            <td align="center"><?php echo $plano['nomePlano']; ?></td>
            <td><a href="verDescricaoPlano.php?idPlano=<?php echo $plano['id']; ?>"><?php echo substr($plano['descricao'],0, 30); ?></a></td>
            <td><?php echo $plano['nome']; ?></td>
            <td><a href="verDescricaoControle.php?idControle=<?php echo $plano['id_controle']; ?>"><?php echo substr($plano['descricao_controle'],0, 30); ?></a></td>
            <td><?php echo $plano['nome_pessoa_controle']; ?></td>

        </tr>
        

    <?php
         }   
        }
    else {
    ?>
        <tr>
            <td align="center" colspan="5">Nenhum registro encontrado</td>
        </tr>
    <?php
    }
    }
    
    if($verifica_root == '1'){
        ?>
        
        </table>
    
    <p align="center"><input align="center" type="button" name="btnCriarPlano" id="btnCriarPlano" value="   Criar Correção   " onclick="location.href='cadControle.php?criar=1'" /> <input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p> <?php } else{ ?> <input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p> <?php }?>
        <p align="center"></p>  
            
     

    </body>
    </html>
