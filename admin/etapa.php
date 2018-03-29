<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Controle de Metas</title>
</head>

<body>	
    <h2 align="center">Etapa</h2>
    <br/>
    
    <table width="1000" border="1" align="center" cellpadding="2" cellspacing="2">
        <tr>
            <th>Status</th>
            <th>Nome Plano</th>
            <th>Nome Etapa</th>
            <th>Descrição</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Responsável</th>
            <th>Status</th>
            <th>Edição</th>
            <th>Ações</th>
            <th>Excluir</th>
        </tr>
<?php

    include_once("includes/conexao.php");
    $id = $_GET['idPlano'];

           $sql = "SELECT A.id, A.nomeEtapa, A.descricao, A.status, B.dataInicio, B.dataFim, C.nomePlano, D.nome FROM etapa A  INNER JOIN data B ON A.idData = B.id INNER JOIN plano C ON A.idPlanoEtapa = C.id INNER JOIN pessoas D ON A.idPessoa = D.id WHERE C.id = '$id' ORDER BY A.id";    
           $rs = mysql_query($sql, $conexao) or die(mysql_error());
       
    if(mysql_num_rows($rs) > 0) {
    
        while ($etapa = mysql_fetch_assoc($rs))
        {     
    ?>
    <tr>
           <?php 
            $data1 = date('d/m/Y', strtotime( $etapa['dataFim']));
            $dia_etapa=substr($data1 ,0,2);
            $mes_etapa=substr($data1,3,2);
            $ano_etapa=substr($data1,6,4);
            
            $dataHoje = date('d/m/Y');
            $dia_atual=substr($dataHoje ,0,2);
            $mes_atual=substr($dataHoje ,3,2);
            $ano_atual=substr($dataHoje ,6,4);
                
                if ($ano_atual == $ano_etapa || $ano_atual > $ano_etapa){
                    if ($mes_atual < $mes_etapa || $mes_atual == $mes_etapa ){
                        if ($dia_atual < $dia_etapa || $dia_atual < $dia_etapa){
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
            <td><?php echo $etapa['nomePlano'];?></td>
            <td><?php echo $etapa['nomeEtapa'];?></td>
            <td><a href="verDescricaoEtapa.php?idEtapa=<?php echo $etapa['id']?>&idPlano=<?php echo "$id"; ?>"><?php echo substr($etapa['descricao'],0, 30);?></a></td>
            <td><?php echo date('d/m/Y', strtotime( $etapa['dataInicio']));?></td>
            <td><?php echo date('d/m/Y', strtotime( $etapa['dataFim']));?></td>
            <td><?php echo $etapa['nome']; ?></td>
            <td><?php echo $etapa['status'];?></td>
            <td align="center"><a href="cadEtapa.php?idEtapa=<?php echo $etapa['id'];?>&idPlano=<?php $id = $_GET['idPlano']; echo "$id"; ?>&acao=altera">Editar</a></td>
            <td align="center"><a href="action.php?idEtapa=<?php echo $etapa['id'];?>&idPlano=<?php echo "$id";?>">Ver Ações</a></td>
            <td align="center"><a href="excluirEtapa.php?idEtapa=<?php echo $etapa['id'];?>&idPlano=<?php echo "$id"; ?>&acao=altera">Excluir</a></td>
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
    ?>
    </table>
    <p>
        
 
         <p align="center"><input align="center" type="button" name="btnCriarEtapa" id="btnCriarEtapa" value="   Criar Etapa   " onclick="location.href='cadEtapa.php?idPlano=<?php $id=$_GET['idPlano']; echo $id;?>&criar=1'" /> <input align="center" type="button" name="btnVoltaPlano" id="btnpacotes" value="   Voltar Planos   " onclick="location.href='planos.php'" /> <input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p>
   
        
    </p>
    </body>
    </html>