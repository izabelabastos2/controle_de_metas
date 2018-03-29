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
    <h2 align="center"> Controle de Metas - Planos</h2>
    <br />
    
    <table width="1000" border="1" align="center" cellpadding="2" cellspacing="2">
        <tr>
            <th>Status</th>
            <th>Título</th>
            <th>Descrição</a></th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Resposável</th>
            <th>Etapas</th>
            <th>Edição</th>
            <th>Encerrar</th>
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
        $sql = "SELECT A.id, A.nomePlano, A.descricao, A.status, B.dataInicio, B.dataFim, C.nome FROM plano A  INNER JOIN data B ON B.id = A.idData INNER JOIN pessoas C ON A.idPessoa = C.id  ORDER BY $ordenacao";    
        $rs = mysql_query($sql, $conexao) or die(mysql_error());
        }
    if(mysql_num_rows($rs) > 0) {
    
        while ($plano = mysql_fetch_assoc($rs))
    {
     
    ?>
        <tr>
            <?php 
            $data1 = date('d/m/Y', strtotime( $plano['dataFim']));
            $dia_plano=substr($data1 ,0,2);
            $mes_plano=substr($data1,3,2);
            $ano_plano=substr($data1,6,4);
            
            $dataHoje = date('d/m/Y');
            $dia_atual=substr($dataHoje ,0,2);
            $mes_atual=substr($dataHoje ,3,2);
            $ano_atual=substr($dataHoje ,6,4);
                
                if ($ano_atual == $ano_plano || $ano_atual > $ano_plano){
                    if ($mes_atual < $mes_plano || $mes_atual == $mes_plano ){
                        if ($dia_atual < $dia_plano || $dia_atual < $dia_plano){
                        ?>            
                     <td bgcolor="#ACFA58"><?php echo "No Prazo"; ?></td>
                        <?php }else{ ?>
                                <td bgcolor="#F78181"><?php echo "Atrasado" ?></td>
                    <?php }
                       }else{ ?>
                     <td bgcolor="#F78181"><?php echo "Atrasado" ?></td>
                <?php }}else{ ?>
                     <td bgcolor="#F78181"><?php echo "Atrasado" ?></td>
            <?php } ?>            
            <td align="center"><?php echo $plano['nomePlano']; ?></td>
            <td><a href="verDescricaoPlano.php?idPlano=<?php echo $plano['id']; ?>"><?php echo substr($plano['descricao'],0, 30); ?></a></td>
            <td><?php echo date('d/m/Y', strtotime( $plano['dataInicio']));?></td>
            <td><?php echo date('d/m/Y', strtotime( $plano['dataFim'])); ?></td>
            <td><?php echo $plano['nome']; ?></td>
            <td align="center"><a href="etapa.php?idPlano=<?php echo $plano['id'];?>">ver Etapa</a></td>
            <td align="center"><a href="cadPlanos.php?idPlano=<?php echo $plano['id']; ?>&acao=altera">Editar</a></td>
            <td align="center"><a href="excluir.php?idPlano=<?php echo $plano['id']; ?>&acao=altera">Encerrar</a></td>
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
            $sql = "SELECT A.id, A.nomePlano, A.descricao, A.status, B.dataInicio, B.dataFim, C.nome FROM plano A  INNER JOIN data B ON B.id = A.idData INNER JOIN pessoas C ON A.idPessoa = C.id  WHERE C.id = '$logado' ORDER BY $ordenacao";    
            $rs = mysql_query($sql, $conexao) or die(mysql_error());
            }
        if(mysql_num_rows($rs) > 0) {

            while ($plano = mysql_fetch_assoc($rs)){
                
        ?>
        <tr>
            <td align="center"><?php echo $plano['nomePlano']; ?></td>
            <td><a href="verDescricaoPlano.php?idPlano=<?php echo $plano['id']; ?>"><?php echo substr($plano['descricao'],0, 30); ?></a></td>
            <td><?php echo $plano['status']; ?></td>
            <td><?php echo date('d/m/Y', strtotime( $plano['dataInicio']));?></td>
            <td><?php echo date('d/m/Y', strtotime( $plano['dataFim'])); ?></td>
            <td><?php echo $plano['nome']; ?></td>
            <td align="center"><a href="etapa.php?idPlano=<?php echo $plano['id']; ?>">ver Etapa</a></td>
            <td align="center"><a href="cadPlanos.php?idPlano=<?php echo $plano['id']; ?>&acao=altera">Editar</a></td>
            <td align="center"><a href="excluir.php?idPlano=<?php echo $plano['id']; ?>&acao=altera">Excluir</a></td>
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
        ?>
        
        </table>
         <?php if($verifica_root == '1'){  ?>
        <p align="center"><input align="center" type="button" name="btnCriarPlano" id="btnCriarPlano" value="   Criar Plano   " onclick="location.href='cadPlanos.php'" /> <input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p>
        <p align="center"></p> 
            <?php }else {?>
                    <p align="center"><input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p>
           <?php   }?>

    </body>
    </html>
