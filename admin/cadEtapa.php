<?php 
session_start();
if (isset($_SESSION['idAdmin'])){
    include_once("includes/conexao.php");
    $logado = $_SESSION['idAdmin']; 
    $nome_logado = $_SESSION['nomeAdmin'];
}else{
    header('Location: index.php');
}




if (isset($_POST['btnCriarEtapa'])) {
    //dados para cadastro do plano
    $nomePlano = $_POST['nomePlano'];
    $nomeEtapa = $_POST['nomeEtapa'];
    $descricao = $_POST['descricao'];
    $responsavel = $_POST['responsavel'];
    $status = $_POST['status'];


//dados cadastro das datas
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];


    if ($_POST['btnCriarEtapa'] != 'Alterar') {
        $sql = "SELECT A.id, A.nomeEtapa, A.descricao, A.status, B.dataInicio, B.dataFim, C.nome FROM etapa A  INNER JOIN data B ON A.idData = B.id INNER JOIN pessoas C ON A.idPessoa = C.id where A.nomeEtapa = '$nomeEtapa'";
        $rs = mysql_query($sql, $conexao) or die(mysql_error());

        if (mysql_num_rows($rs) == 0) {            
            $sqlDadosPlano = "SELECT * FROM plano WHERE nomePlano = '$nomePlano'";
            $rsDadosPlano = mysql_query($sqlDadosPlano, $conexao) or die(mysql_error());
            $auxiliarDadosPlano = mysql_fetch_array($rsDadosPlano);
            $idPlanoResult = $auxiliarDadosPlano['id'];

            $sqlDadosPessoa = "SELECT * FROM pessoas WHERE nome='$responsavel'";
            $rsDadosPessoa = mysql_query($sqlDadosPessoa, $conexao) or die(mysql_error());
            $auxiliarDadosPessoa = mysql_fetch_array($rsDadosPessoa);
            $idPessoa = $auxiliarDadosPessoa['id'];

            $sqlInsertData = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
            $rsInsertData = mysql_query($sqlInsertData, $conexao) or die(mysql_error());

            $sqlDadosData = "SELECT * FROM data WHERE dataInicio='$dataInicio' AND dataFim = '$dataFim'  ";
            $rsDadosData = mysql_query($sqlDadosData, $conexao) or die(mysql_error());
            $auxiliarIdData = mysql_fetch_assoc($rsDadosData);
            $idData = $auxiliarIdData['id'];

            $sqlCriarEtapa = "INSERT INTO etapa (idPlanoEtapa, idPessoa, idData, nomeEtapa, descricao, status ) VALUES ('$idPlanoResult','$idPessoa', '$idData','$nomeEtapa', '$descricao', '$status')";
            $rsCriarEtapa = mysql_query($sqlCriarEtapa, $conexao) or die(mysql_error());

            if ($rsCriarEtapa) {

                $msgCadastro = 'O cadastro de etapa <strong>' . $nomeEtapa . '</strong> foi efetuado com sucesso.<br><br><a href="etapa.php?idPlano=' . $idPlanoResult . '">Continuar</a>';
            } else {
                $msgCadastro = 'O cadastro não foi efetuado. Favor repetir a operação.<br><br><a href="cadEtapa.php">Voltar</a>';
            }
        } else {
            $msgCadastro = 'Cadastro não realizado.<br><br>O título do cadastro já consta na base de dados.';
        }
    } else {
        $id = $_GET['idPlano'];
        $idEtapaEditada = $_POST['idEtapa'];

        $sqlInsData = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
        $rsInsData = mysql_query($sqlInsData, $conexao) or die(mysql_error());

        $sqlIdDataAlt = "SELECT * FROM data  WHERE dataInicio = '$dataInicio' AND dataFim = '$dataFim'";
        $rsSqlIdDataAlt = mysql_query($sqlIdDataAlt, $conexao) or die(mysql_error());
        $auxDataAlt = mysql_fetch_array($rsSqlIdDataAlt);
        $idDataAlt = $auxDataAlt['id'];

        $sqlDadoPlanoAlt = "SELECT * FROM plano WHERE id ='$id'";
        $rsDadoPlanoAlt = mysql_query($sqlDadoPlanoAlt, $conexao) or die(mysql_error());
        $auxDadoPlanoAlt = mysql_fetch_array($rsDadoPlanoAlt);
        $idPlanoAlt = $auxDadoPlanoAlt['id'];

        $sqlPessoaAlt = "SELECT * FROM pessoas WHERE nome='$responsavel'";
        $rsPessoaAlt = mysql_query($sqlPessoaAlt, $conexao) or die(mysql_error());
        $auxPessoaAlt = mysql_fetch_array($rsPessoaAlt);
        $idPessoaAlt = $auxPessoaAlt['id'];

        $sqlAltera = "UPDATE etapa SET idPlanoEtapa ='$idPlanoAlt', idPessoa ='$idPessoaAlt', idData ='$idDataAlt', nomeEtapa='$nomeEtapa', descricao ='$descricao', status='$status' WHERE id='$idEtapaEditada'";
        $rsAltera = mysql_query($sqlAltera, $conexao) or die(mysql_error());

        if ($rsAltera) {
            $msgCadastro = 'O cadastro foi alterado com sucesso.<br><br><a href="cadEtapa.php?acao=altera&idEtapa=' . $idEtapaEditada . '&idPlano=' . $idPlanoAlt . '">Continuar</a>';
        } else {
            $msgCadastro = 'O cadastro não foi alterado.<br>Favor repetir a operação.<br><br><a href="cadEtapa.php?acao=altera&idEtapa=' . $idEtapaEditada . '&idPlano=' . $idPlanoAlt . '">Voltar</a>';
        }
    }
} else {
    if (isset($_GET['acao']) && $_GET['acao'] == 'altera' && isset($_GET['idEtapa'])) {
        $altera = 1;
        $id = $_GET['idPlano'];
        $idEtapa = $_GET['idEtapa'];

        $sqlGetDadosPlano = "SELECT * FROM plano WHERE id = '$id'";
        $rsGetDadosPlano = mysql_query($sqlGetDadosPlano, $conexao) or die(mysql_error());
        $auxGetDadosPlano = mysql_fetch_assoc($rsGetDadosPlano);
        $getIdPlano = $auxGetDadosPlano['id'];
        $getNomePlano = $auxGetDadosPlano['nomePlano'];

        $sqlGetDadosData = "SELECT * FROM data INNER JOIN etapa ON data.id = etapa.idData WHERE etapa.id='$idEtapa'";
        $rsGetDadosData = mysql_query($sqlGetDadosData, $conexao) or die(mysql_error());
        $auxGetdata = mysql_fetch_array($rsGetDadosData);
        $getIdData = $auxGetdata['id'];

        $sqlGetDadosPessoa = "SELECT pessoas.* FROM pessoas INNER JOIN etapa ON pessoas.id = etapa.idPessoa  WHERE etapa.id='$idEtapa'";
        $rsGetDadosPessoa = mysql_query($sqlGetDadosPessoa, $conexao) or die(mysql_error());
        $auxGetPessoa = mysql_fetch_array($rsGetDadosPessoa);
        $getIdPessoa = $auxGetPessoa['id'];

        $sqlGetDadosEtapa = "SELECT * FROM etapa WHERE id='$idEtapa'";
        $rsGetDadosEtapa = mysql_query($sqlGetDadosEtapa, $conexao) or die(mysql_error());
        $auxGetDadosEtapa = mysql_fetch_array($rsGetDadosEtapa);
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
        <h2 align="center">Controle de Metas - Cadastro de Etapa</h2>
        <br />
        <?php
        if (isset($msgCadastro)) {
            echo '<p align="center">' . $msgCadastro . '</p>';
        } else {
            ?>
            <form action="" method="post" name="formCadastro" id="formCadastro" enctype="multipart/form-data">

                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">

                    <tr>
                        <td><label>Título Plano</label></td>
                            <td>
                                <select name="nomePlano" id="nomePlano">
                                    <?php   $idPlanoSelect = $_GET['idPlano'];
                                            $sql = "SELECT * FROM plano";
                                            $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                            if(isset($idPlanoSelect)){
                                                if(isset($_GET['criar'])){
                                                   while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nomePlano']?> value="<?php $exibe['nomePlano']?>" > <?php  echo $exibe['nomePlano'];?> </option></>   
                                            <?php  }
                                                }else{  $sql = "SELECT * FROM plano WHERE id='$idPlanoSelect'";
                                                        $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                        while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                            <option selected = "<?php echo $exibe['nomePlano']?> value="<?php $exibe['nomePlano']?>" > <?php  echo $exibe['nomePlano'];?> </option></>
                                        <?php           }    
                                                    }
                                            }      
                                        ?>    
                            </select>
                        </td> 
                    </tr>

                                
                            </select>
                        </td> 
                    </tr>

                    <tr> 
                        <td align="right">Nome Etapa</td>
                        <td><input name="nomeEtapa" type="text" id="nomeEtapa" size="50" maxlength="100" <?php
                            if (isset($altera)) {
                                echo 'value="' . $auxGetDadosEtapa['nomeEtapa'] . '"';
                            }
                            ?> />
                        </td>
                    </tr> 

                    <tr>
                        <td align="right">Descrição</td>
                        <td><textarea name="descricao" type="text" id='descricao' cols="45" rows="4"><?php
                                if (isset($altera)) {
                                    echo $auxGetDadosEtapa['descricao'];
                                }
                                ?></textarea><script>CKEDITOR.replace('descricao');</script>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Responsável</label></td>
                        <td>
                                <select name="responsavel" id="responsavel">
                                    <?php   $idEtapaSelect = $_GET['idEtapa'];
                                            $sql = "SELECT * FROM pessoas";
                                            $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                            if(isset($idPlanoSelect)){
                                                if(isset($_GET['criar'])){
                                                   while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nome']?> value= "<?php $exibe['nome']?>" > <?php  echo $exibe['nome'];?> </option></>   
                                            <?php  }
                                                }else{  $sql = "SELECT * FROM pessoas INNER JOIN etapa ON etapa.idPessoa = pessoas.id  WHERE etapa.id='$idEtapaSelect'";
                                                        $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                        
                                                        while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                            <option selected = "<?php echo $exibe['nome']?> value="<?php $exibe['nome']?>" > <?php  echo $exibe['nome'];?> </option></>
                                        <?php           }    
                                                    }
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
                                        value=" <?php echo $auxGetDadosEtapa['status']; ?>">
        <?php echo $auxGetDadosEtapa['status'];
    }
    ?> </option></>
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
                    <input name="idEtapa" type="hidden" value="<?php echo $idEtapa; ?>" />
        <?php
    } else {
        $txtBtnCadastrar = 'Cadastrar';
    }
    ?>
                <p align="center">
                    <input name="btnCriarEtapa" type="submit" value="<?php echo $txtBtnCadastrar; ?>" />
                </p>
            </form>

            <p align="center"><a href="etapa.php?idEtapa=<?php $idEtapa = $_GET['idEtapa'];echo $idEtapa; ?>&idPlano=<?php $id = $_GET['idPlano']; echo $id;?>">Voltar</a></p>
            <p align="center"><a href="admin.php">Menu Principal</a></p>
<?php } ?>
    </body>
</html>


