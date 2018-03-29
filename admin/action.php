<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Controle de Metas</title>
</head>

<body>	
    <h2 align="center"> Ação </h2>
    <br />
    
    <table width="1000" border="1" align="center" cellpadding="2" cellspacing="2">
        <tr>
            <th>Status</th>
            <th>Plano</th>
            <th>Etapa</th>
            <th>Ação</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Edição</th>
            <th>Excluir</th>
        </tr>
<?php

    include_once("includes/conexao.php");
    $id = $_GET['idPlano'];
    $idEtapa = $_GET['idEtapa'];
    
    
    $sql = "SELECT A.id, A.nomeAction, A.descricao, A.status, B.dataInicio, B.dataFim, C.nomePlano, etapa.nomeEtapa FROM action A  INNER JOIN data B ON A.idDataAcao = B.id INNER JOIN plano C ON A.idPlanoAction = C.id INNER JOIN etapa ON A.idEtapaAcao = etapa.id WHERE etapa.id ='$idEtapa' ";    
    $rs = mysql_query($sql, $conexao) or die(mysql_error());
        
        
    if(mysql_num_rows($rs) > 0) {
    
        while ($action = mysql_fetch_assoc($rs))
    {
            $tituloaction = $action['nomeAction'];
     
    ?>
        <tr>
            <?php 
            $data1 = date('d/m/Y', strtotime( $action['dataFim']));
            $dia_action=substr($data1 ,0,2);
            $mes_action=substr($data1,3,2);
            $ano_action=substr($data1,6,4);
            
            $dataHoje = date('d/m/Y');
            $dia_atual=substr($dataHoje ,0,2);
            $mes_atual=substr($dataHoje ,3,2);
            $ano_atual=substr($dataHoje ,6,4);
                
                if ($ano_atual == $ano_action || $ano_atual > $ano_action){
                    if ($mes_atual < $mes_action || $mes_atual == $mes_action ){
                        if ($dia_atual < $dia_action || $dia_atual < $dia_action){
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
            <td><?php echo $action['nomePlano']; ?></td>
            <td><?php echo $action['nomeAction']; ?></td>
            <td><?php echo $action['nomeEtapa']; ?></td>
            <td><a href="verDescricaoAction.php?idAction=<?php echo $action['id']; ?>&idPlano=<?php echo "$id"; ?>"><?php echo substr($action['descricao'],0, 30); ?></a></td>
            <td><?php echo $action['status']; ?></td>
            <td><?php echo date('d/m/Y', strtotime( $action['dataInicio']));?></td>
            <td><?php echo date('d/m/Y', strtotime( $action['dataFim']));?></td>
            <td align="center"><a href="cadAcao.php?idPlano=<?php $id=$_GET['idPlano']; echo $id;?>&idEtapa=<?php $id=$_GET['idEtapa']; echo $id;?>&idAcao=<?php echo $action['id']; ?>&acao=altera">Editar</a></td>
            <td align="center"><a href="excluirAcao.php?idPlano=<?php $id=$_GET['idPlano']; echo $id;?>&idEtapa=<?php $id=$_GET['idEtapa']; echo $id;?>&idAcao=<?php echo $action['id']; ?>&acao=altera">Excluir</a></td>
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
  
        
        <p align="center"><input align="center" type="button" name="btnCriarAcao" id="btnAcao" value="   Criar Ação  " onclick="location.href='cadAcao.php?idPlano=<?php $id=$_GET['idPlano']; echo $id;?>&idEtapa=<?php $id=$_GET['idEtapa']; echo $id;?>&criar=1'" /> 
            <input align="center" type="button" name="btnVoltaPlano" id="btnAcaoPlano" value="   Voltar Etapa   " onclick="location.href='etapa.php?idEtapa=<?php $idEtapa = $_GET['idEtapa'];echo $idEtapa; ?>&idPlano=<?php $id = $_GET['idPlano']; echo $id;?>'" /> <input align="center" type="button" name="btnMenuAdimin" id="btnpacotes" value="   Voltar Menu   " onclick="location.href='index.php'" /></p>

        
    </p>
    </body>
    </html>
