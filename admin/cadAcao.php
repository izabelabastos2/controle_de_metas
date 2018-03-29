<?php
session_start();

if (isset($_SESSION['idAdmin']))
    $idAdmin = $_SESSION['idAdmin'];
else
    header('Location: index.php');
include_once("includes/conexao.php");

$idEtapaGeral = $_GET['idEtapa'];
$id = $_GET['idPlano'];
if (isset($_POST['btnCriarAcao'])) {
    
    $nomeEtapa = $_POST['nomeEtapa'];
    $nomeAcao = $_POST['nomeAcao'];    
    $descricao = $_POST['descricao'];
    $responsavel = $_POST['responsavel'];
    $status = $_POST['status'];
 
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];


    if ($_POST['btnCriarAcao'] != 'Alterar') {
        $sql = "SELECT A.nomeAction, A.descricao, A.status, B.dataInicio, B.dataFim, C.nome, D.nomePlano, E.nomeEtapa FROM action A INNER JOIN data B ON A.idDataAcao = B.id INNER JOIN pessoas C ON A.idPessoaAcao = C.id INNER JOIN plano D ON A.idPlanoAction = D.id INNER JOIN etapa E ON A.idEtapaAcao = E.id WHERE A.nomeAction = '$nomeAcao'";
        $rs = mysql_query($sql, $conexao) or die(mysql_error());

        if (mysql_num_rows($rs) == 0) {            
       

            $sqlDadosPessoa = "SELECT * FROM pessoas WHERE nome='$responsavel'";
            $rsDadosPessoa = mysql_query($sqlDadosPessoa, $conexao) or die(mysql_error());
            $auxiliarDadosPessoa = mysql_fetch_array($rsDadosPessoa);
            $idPessoa = $auxiliarDadosPessoa['id'];

            $sqlDadosEtapa="SELECT * FROM etapa WHERE nomeEtapa= '$nomeEtapa'";
            $rsDadosEtapa = mysql_query($sqlDadosEtapa, $conexao) or die(mysql_error());
            $auxiliarDadosEtapa= mysql_fetch_array($rsDadosEtapa);
            $idEtapaResult = $auxiliarDadosEtapa['id'];        
                    
            $sqlInsertData = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
            $rsInsertData = mysql_query($sqlInsertData, $conexao) or die(mysql_error());

            $sqlDadosData = "SELECT * FROM data WHERE dataInicio='$dataInicio' AND dataFim = '$dataFim'  ";
            $rsDadosData = mysql_query($sqlDadosData, $conexao) or die(mysql_error());
            $auxiliarIdData = mysql_fetch_assoc($rsDadosData);
            $idData = $auxiliarIdData['id'];

            $sqlCriarAcao = "INSERT INTO action (idPlanoAction, idPessoaAcao, idDataAcao, idEtapaAcao, nomeAction, descricao, status ) VALUES ('$id', '$idPessoa', '$idData', '$idEtapaResult','$nomeAcao', '$descricao', '$status')";
            $rsCriarAcao = mysql_query($sqlCriarAcao, $conexao) or die(mysql_error());

            if ($rsCriarAcao) {

                $msgCadastro = 'O cadastro de Ação <strong>' . $nomeAcao . '</strong> foi efetuado com sucesso.<br><br><a href="action.php?idPlano=' . $id .'&idEtapa=' . $idEtapaGeral. ' ">Continuar</a>';
            } else {
                $msgCadastro = 'O cadastro não foi efetuado. Favor repetir a operação.<br><br><a href="cadAcao.php?idPlano=' . $id .'&idEtapa=' . $idEtapaGeral. ' ">Voltar</a>';
            }
        } else {
            $msgCadastro = 'Cadastro não realizado.<br><br>O título do cadastro já consta na base de dados.';
        }
    } else {
        $idAcaoEditada = $_POST['idAcao'];

        $sqlDadosPlano = "SELECT * FROM plano WHERE nomePlano = '$id'";
        $rsDadosPlano = mysql_query($sqlDadosPlano, $conexao) or die(mysql_error());
        $auxiliarDadosPlano = mysql_fetch_array($rsDadosPlano);
        $idPlanoResult = $auxiliarDadosPlano['id'];
        
        $sqlInsData = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
        $rsInsData = mysql_query($sqlInsData, $conexao) or die(mysql_error());

        $sqlIdDataAlt = "SELECT * FROM data  WHERE dataInicio = '$dataInicio' AND dataFim = '$dataFim'";
        $rsSqlIdDataAlt = mysql_query($sqlIdDataAlt, $conexao) or die(mysql_error());
        $auxDataAlt = mysql_fetch_array($rsSqlIdDataAlt);
        $idDataAlt = $auxDataAlt['id'];
        
        $sqlDadoEtapaAlt = "SELECT * FROM etapa WHERE id ='$id'";
        $rsDadoEtapaAlt = mysql_query($sqlDadoEtapaAlt, $conexao) or die(mysql_error());
        $auxDadoEtapaAlt = mysql_fetch_array($rsDadoEtapaAlt);
        $idEtapaAlt = $auxDadoEtapaAlt['id'];

        $sqlPessoaAlt = "SELECT * FROM pessoas WHERE nome='$responsavel'";
        $rsPessoaAlt = mysql_query($sqlPessoaAlt, $conexao) or die(mysql_error());
        $auxPessoaAlt = mysql_fetch_array($rsPessoaAlt);
        $idPessoaAlt = $auxPessoaAlt['id'];

        $sqlAltera = "UPDATE action SET idPlanoAction ='$idPlanoResult', idPessoaAcao ='$idPessoaAlt', idDataAcao ='$idDataAlt', idEtapaAcao='$idEtapaAlt', nomeAction='$nomeAcao', descricao ='$descricao', status='$status' WHERE id='$idAcaoEditada'";
        $rsAltera = mysql_query($sqlAltera, $conexao) or die(mysql_error());

        if ($rsAltera) {
            $msgCadastro = 'O cadastro foi alterado com sucesso.<br><br><a href="cadEtapa.php?acao=altera&idEtapa=' . $idAcaoEditada . '&idPlano=' . $idPlanoAlt . '">Continuar</a>';
        } else {
            $msgCadastro = 'O cadastro não foi alterado.<br>Favor repetir a operação.<br><br><a href="cadEtapa.php?acao=altera&idEtapa=' . $idAcaoEditada . '&idPlano=' . $idPlanoAlt . '">Voltar</a>';
        }
    }
} else {
    if (isset($_GET['acao']) && $_GET['acao'] == 'altera' && isset($_GET['idEtapa'])) {
        $altera = 1;
        $id = $_GET['idPlano'];
        $idAcao = $_GET['idAcao'];

        $sqlGetDadosPlano = "SELECT * FROM plano WHERE id = '$id'";
        $rsGetDadosPlano = mysql_query($sqlGetDadosPlano, $conexao) or die(mysql_error());
        $auxGetDadosPlano = mysql_fetch_assoc($rsGetDadosPlano);
        $getIdPlano = $auxGetDadosPlano['id'];

        $sqlGetDadosData = "SELECT * FROM data INNER JOIN action ON data.id = action.idDataAcao WHERE action.id='$idAcao'";
        $rsGetDadosData = mysql_query($sqlGetDadosData, $conexao) or die(mysql_error());
        $auxGetdata = mysql_fetch_array($rsGetDadosData);
        $getIdData = $auxGetdata['id'];

        $sqlGetDadosPessoa = "SELECT pessoas.* FROM pessoas INNER JOIN action ON pessoas.id = action.idPessoaAcao  WHERE action.id='$idAcao'";
        $rsGetDadosPessoa = mysql_query($sqlGetDadosPessoa, $conexao) or die(mysql_error());
        $auxGetPessoa = mysql_fetch_array($rsGetDadosPessoa);
        $getIdPessoa = $auxGetPessoa['id'];

        $sqlGetDadosEtapa = "SELECT etapa.* FROM etapa INNER JOIN  action ON action.idEtapaAcao = etapa.id WHERE action.id='$idAcao'";
        $rsGetDadosEtapa = mysql_query($sqlGetDadosEtapa, $conexao) or die(mysql_error());
        $auxGetDadosEtapa = mysql_fetch_array($rsGetDadosEtapa);
        
        $sqlGetDadosAcao = "SELECT * FROM action WHERE id='$idAcao' ";
        $rsGetDadosAcao = mysql_query($sqlGetDadosAcao, $conexao) or die(mysql_error());
        $auxGetDadosAcao = mysql_fetch_array($rsGetDadosAcao);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Controle de Metas</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="../includes/jquery.maskMoney.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $("#valor").maskMoney({symbol: 'R$ ', showSymbol: false, thousands: '.', decimal: ',', symbolStay: false, allowZero: true, allowNegative: false, defaultZero: false});
            });
        </script>
        <script src="includes/ckeditor/ckeditor.js"></script>
    </head>

    <body>
        <h2 align="center">Controle de Metas - Cadastro de Ações</h2>
        <br />
        <?php
        if (isset($msgCadastro)) {
            echo '<p align="center">' . $msgCadastro . '</p>';
        } else {
            ?>
            <form action="" method="post" name="formCadastro" id="formCadastro" enctype="multipart/form-data">

                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">
                    
                    <tr>
                        <td><label>Título Etapa</label></td>
                        <td><select name="nomeEtapa" id="nomeEtapa">
                                    <?php   $idEtapaSelect = $_GET['idEtapa'];
                                            $sql = "SELECT * FROM etapa";
                                            $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                if(isset($_GET['criar'])){
                                                   while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nomeEtapa']?> value="<?php $exibe['nomeEtapa']?>" > <?php  echo $exibe['nomeEtapa'];?> </option></>   
                                            <?php  }
                                                }else{  $sql = "SELECT * FROM etapa WHERE id='$idEtapaSelect'";
                                                        $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                        while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                            <option selected = "<?php echo $exibe['nomeEtapa']?> value="<?php $exibe['nomeEtapa']?>" > <?php  echo $exibe['nomeEtapa'];?> </option></>
                                        <?php           }    
                                                }   
                                        ?>    
                            </select>
                        </td> 
                    </tr>

                    <tr> 
                        <td align="right">Nome Ação</td>
                        <td><input name="nomeAcao" type="text" id="nomeEtapa" size="50" maxlength="100" <?php
                            if (isset($altera)) {
                                echo 'value="' . $auxGetDadosAcao['nomeAction'] . '"';
                            }
                            ?> />
                        </td>
                    </tr> 

                    <tr>
                        <td align="right">Descrição</td>
                        <td><textarea name="descricao" type="text" id='descricao' cols="45" rows="4"><?php
                                if (isset($altera)) {
                                    echo $auxGetDadosAcao['descricao'];
                                }
                                ?></textarea><script>CKEDITOR.replace('descricao');</script>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Responsável</label></td>
                        <td><select name="responsavel" id="responsavel">
                                    <?php   
                                            $sql = "SELECT * FROM pessoas";
                                            $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                            
                                                if(isset($_GET['criar'])){
                                                   while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nome']?>  value= "<?php $exibe['nome']?>" > <?php  echo $exibe['nome'];?> </option></>   
                                            <?php  }
                                                }else{  
                                                        $idAcaoSelect = $_GET['idAcao'];
                                                        $sql = "SELECT * FROM pessoas INNER JOIN action ON action.idPessoaAcao = pessoas.id  WHERE action.id='$idAcaoSelect'";
                                                        $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                        
                                                        while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                            <option selected = "<?php echo $exibe['nome']?> value="<?php $exibe['nome']?>" > <?php  echo $exibe['nome'];?> </option></>
                                        <?php           }    
                                                    }
                                                 
                                        ?> 
                            </select>
                        </td> 
                    </tr>

                    <tr>
                        <td align="right">Status</td>            
                        <td><select tabindex="5" name="status" id="status">
                                <option                                    
                                    <?php if (isset($altera)) { ?> 
                                        value=" <?php echo $auxGetDadosAcao['status']; ?>"><?php echo $auxGetDadosAcao['status'];
                                          }?>
                                </option></>
                                <option value="Concluido">Concluido</option></>
                                <option value="Em andamento">Em andamento</option></>
                                <option value="Cancelado">Cancelado</option>
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Data Início</td>
                        <td><input name="dataInicio" type="date" id="dataInicio" size="50" maxlength="100" <?php
    if (isset($altera)) {
        echo 'value="' . $auxGetdata['dataInicio'] . '"';
    }
    ?> />
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Data Fim</td>
                        <td><input name="dataFim" type="date" id="dataFim" size="50" maxlength="100" <?php
    if (isset($altera)) {
        echo 'value="' . $auxGetdata['dataFim'] . '"';
    }
    ?> />
                        </td>
                    </tr>

                </table>
                <?php
                if (isset($altera) && $altera == 1) {
                    $txtBtnCadastrar = 'Alterar';
                    $txtBtnLimpar = 'Redefinir Formulário';
                    ?>
                    <input name="idAcao" type="hidden" value="<?php echo $idAcao; ?>" />
        <?php
    } else {
        $txtBtnCadastrar = 'Cadastrar';
    }
    ?>
                <p align="center">
                    <input name="btnCriarAcao" type="submit" value="<?php echo $txtBtnCadastrar; ?>" />
                </p>
            </form>

            <p align="center"><a href="action.php?idPlano=<?php $id = $_GET['idPlano'];echo $id;?>&idEtapa=<?php echo $idEtapaGeral;?>">Voltar</a></p>
            <p align="center"><a href="admin.php">Menu Principal</a></p>
<?php } ?>
    </body>
</html>


