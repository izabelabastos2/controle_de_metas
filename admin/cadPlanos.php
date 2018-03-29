<?php
session_start();
if (isset($_SESSION['idAdmin']))
    $idAdmin = $_SESSION['idAdmin'];
else
    header('Location: index.php');
include_once("includes/conexao.php");

if (isset($_POST['btnCriarPlano'])) {

    //dados para cadastro do plano
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];
    $responsavel = $_POST['responsavel'];
    $status = $_POST['status'];


    //dados cadastro das datas
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];


    if ($_POST['btnCriarPlano'] != 'Alterar') {
        $sql0 = "SELECT A.id, A.nomePlano, A.descricao, A.status, B.dataInicio, B.dataFim, C.nome FROM plano A  INNER JOIN data B ON B.id = A.idData INNER JOIN pessoas C ON A.idPessoa = C.id where A.nomePlano = '$titulo'";
        $rs0 = mysql_query($sql0, $conexao) or die(mysql_error());

        if (mysql_num_rows($rs0) == 0) {

            //crio registro necessário pela restrição de FK do BD
            $sql3 = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
            $rs3 = mysql_query($sql3, $conexao) or die(mysql_error());

            $sqlIdData = "SELECT id FROM data  WHERE dataInicio = '$dataInicio' AND dataFim = '$dataFim'";
            $rsSqlIdData = mysql_query($sqlIdData, $conexao) or die(mysql_error());
            $auxiliarIdData = mysql_fetch_array($rsSqlIdData);
            $idDataResult = $auxiliarIdData['id'];

            $sql = "SELECT * FROM pessoas WHERE nome='$responsavel'";
            $rs = mysql_query($sql, $conexao) or die(mysql_error());
            $i = mysql_fetch_assoc($rs);
            $idResponsavel = $i['id'];

            $sqlCriarPlano = "INSERT INTO plano (idPessoa,idData, nomePlano, descricao, status) VALUES ('$idResponsavel','$idDataResult','$titulo', '$descricao', '$status')";
            $rsCriarPlano = mysql_query($sqlCriarPlano, $conexao) or die(mysql_error());

            if ($rsCriarPlano) {
                $msgCadastro = 'O cadastro do Plano <strong>' . $titulo . '</strong> foi efetuado com sucesso.<br><br><a href="planos.php">Continuar</a>';
            } else {
                $msgCadastro = 'O cadastro não foi efetuado. Favor repetir a operação.<br><br><a href="cadPlanos.php">Voltar</a>';
            }
        } else {
            $msgCadastro = 'Cadastro não realizado.<br><br>O título do cadastro já consta na base de dados.';
        }
    } else {
        $idPlanoModificar = $_POST['idPlano'];
        $sqlInsData = "INSERT INTO data (dataInicio, dataFim) VALUES ('$dataInicio', '$dataFim')";
        $rsInsData = mysql_query($sqlInsData, $conexao) or die(mysql_error());

        $sqlIdDataAtualizar = "SELECT id FROM data  WHERE dataInicio = '$dataInicio' AND dataFim = '$dataFim'";
        $rsSqlIdDataAtualizar = mysql_query($sqlIdDataAtualizar, $conexao) or die(mysql_error());
        $auxiliarIdDataAtualizar = mysql_fetch_array($rsSqlIdDataAtualizar);
        $idDataAtuResult = $auxiliarIdDataAtualizar['id'];

        $sqlIdPessoaModificarPlano = "SELECT * FROM pessoas WHERE nome = '$responsavel'";
        $rsIdPesModPlano = mysql_query($sqlIdPessoaModificarPlano, $conexao) or die(mysql_error());
        $auxiliarIdPesModPlano = mysql_fetch_array($rsIdPesModPlano);
        $resultIdPesModPlano = $auxiliarIdPesModPlano['id'];
        $sqlAtualizarPlano = "UPDATE plano SET idPessoa ='$resultIdPesModPlano', idData='$idDataAtuResult', nomePlano='$titulo', descricao ='$descricao', status='$status' WHERE id='$idPlanoModificar'";
        $rsAtualizarPlano = mysql_query($sqlAtualizarPlano, $conexao) or die(mysql_error());
        if ($rsAtualizarPlano) {
            $msgCadastro = 'O cadastro foi alterado com sucesso.<br><br><a href="planos.php">Continuar</a>';
        } else {
            $msgCadastro = 'O cadastro não foi alterado.<br>Favor repetir a operação.<br><br><a href="cadPlanos.php?acao=altera&idPlano=' . $idPlanoModificar . '">Voltar</a>';
        }
    }
} else {
    if (isset($_GET['acao']) && $_GET['acao'] == 'altera' && isset($_GET['idPlano'])) {
        $altera = 1;
        $idPlano = $_GET['idPlano'];

        $sqlDadosPlano = "SELECT * FROM plano WHERE id='$idPlano'";
        $rsDadosPlano = mysql_query($sqlDadosPlano, $conexao) or die(mysql_error());
        $DadosPlano = mysql_fetch_array($rsDadosPlano);

        $sqlDadosPessoas = "SELECT pessoas.* FROM pessoas INNER JOIN plano ON pessoas.id = plano.idPessoa  WHERE plano.id='$idPlano'";
        $rsDadosPessoas = mysql_query($sqlDadosPessoas, $conexao) or die(mysql_error());
        $DadosPessoas = mysql_fetch_array($rsDadosPessoas);

        $sqlDadosData = "SELECT * FROM data A INNER JOIN plano B ON B.idData = A.id WHERE B.id='$idPlano'";
        $rsDadosData = mysql_query($sqlDadosData, $conexao) or die(mysql_error());
        $DadosData = mysql_fetch_array($rsDadosData);
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
        <h2 align="center">Controle de Metas - Cadastro de Planos</h2>
        <br />
        <?php
        if (isset($msgCadastro)) {
            echo '<p align="center">' . $msgCadastro . '</p>';
        } else {
            ?>
            <form action="" method="post" name="formCadastro" id="formCadastro" enctype="multipart/form-data">

                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">

                    <tr>
                        <td><label>Responsável</label></td>
                        <td>
                            <select name="responsavel" id="responsavel">
                                    <?php 
                                                if(isset($altera)){?>
                                                  <option selected = "<?php echo $DadosPessoas['nome']; ?>" value= " <?php echo  $DadosPessoas['nome']; ?>" > <?php echo $DadosPessoas['nome'];?> </option></>
                                       
                                         <?php  }else{ $sql = "SELECT * FROM pessoas";
                                                       $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                       while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nome']?>" value= "<?php echo $exibe['nome']?>" > <?php  echo $exibe['nome'];?> </option></>   
                                                    
                                                    
                                        <?php           }    
                                                    }   
                                        ?>    
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Título</td>
                        <td><input name="titulo" type="text" id="titulo" size="50" maxlength="100" <?php
    if (isset($altera)) {
        echo 'value="' . $DadosPlano['nomePlano'] . '"';
    }
    ?> />
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Descrição</td>
                        <td><textarea name="descricao" type="text" id='descricao' cols="45" rows="4"><?php
    if (isset($altera)) {
        echo $DadosPlano['descricao'];
    }
    ?></textarea><script>CKEDITOR.replace('descricao');</script>
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Status</td>            
                        <td><select tabindex="5" name="status" id="status">
                                <option                                    
    <?php if (isset($altera)) { ?> 
                                        value=" <?php echo $DadosPlano['status']; ?>">
        <?php echo $DadosPlano['status'];
    } ?> </option>
                                <option value="Concluido">Concluido</option></>
                                <option value="Em andamento">Em andamento</option></>
                                <option value="Cancelado">Cancelado</option>
                        </td>

                    </tr>

                    <tr>
                        <td align="right">Data Início</td>
                        <td><input name="dataInicio" type="date" id="dataInicio" size="50" maxlength="100" <?php
                                   if (isset($altera)) {
                                       echo 'value="' . $DadosData['dataInicio'] . '"';
                                   }
                                   ?> />
                        </td>
                    </tr>

                    <tr>
                        <td align="right">Data Fim</td>
                        <td><input name="dataFim" type="date" id="dataFim" size="50" maxlength="100" <?php
                                   if (isset($altera)) {
                                       echo 'value="' . $DadosData['dataFim'] . '"';
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
                    <input name="idPlano" type="hidden" value="<?php echo $idPlano; ?>" />
        <?php
    } else {
        $txtBtnCadastrar = 'Cadastrar';
    }
    ?>
                <p align="center">
                    <input name="btnCriarPlano" type="submit" value="<?php echo $txtBtnCadastrar; ?>" />
                </p>
            </form>
            <p align="center"><a href="planos.php">Voltar</a></p>

<?php } ?>

        <p align="center"><a href="admin.php">Menu Principal</a></p>

    </body>
</html>

