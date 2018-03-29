<?php
session_start();
if (isset($_SESSION['idAdmin'])){
    include_once("includes/conexao.php");
    $logado = $_SESSION['idAdmin']; 
    $nome_logado = $_SESSION['nomeAdmin'];
}else{
    header('Location: index.php');
}


if (isset($_POST['btnCriarControle'])) {

    $nomePlano = $_POST['nomePlano'];
    $descPlano = $_POST['descPlano'];
    $responsavel_plano = $_POST['responsavel_plano'];
    $descricao_controle = $_POST['descricao_controle'];
    $responsavel_controle = $_POST['responsavel_controle'];






    if ($_POST['btnCriarControle'] != 'Alterar') {
            
            $sqlDadosPlano = "SELECT * FROM plano WHERE nomePlano = '$nomePlano'";
            $rsDadosPlano = mysql_query($sqlDadosPlano, $conexao) or die(mysql_error());
            $auxiliarDadosPlano = mysql_fetch_array($rsDadosPlano);
            $idPlanoResult = $auxiliarDadosPlano['id'];

            $sqlCriar_controle = "INSERT INTO controle (idPlanoControle, idPessoaControle, descricao) VALUES ('$idPlanoResult','$logado', '$descricao_controle')";
            $rsCriar_controle = mysql_query($sqlCriar_controle , $conexao) or die(mysql_error());


            if ($rsCriar_controle) {

                $msgCadastro = 'A correção <strong>' . $nome_controle . '</strong> foi criada com sucesso.<br><br><a href="controle.php">Continuar</a>';
            } else {
                $msgCadastro = 'O cadastro não foi efetuado. Favor repetir a operação.<br><br><a href="cadControle.php">Voltar</a>';
            }
        
    } else {
        $id_controle_modifica = $_GET['idControle'];

        $sql = "SELECT A.* FROM plano A INNER JOIN controle B ON A.id = B.idPlanoControle WHERE B.id = '$id_controle_modifica'";
        $rs= mysql_query($sql, $conexao) or die(mysql_error());
        $aux = mysql_fetch_array($rs);
        $idPlano_alt = $aux['id'];
        var_dump($idPlano_alt);

        $sqlAltera = "UPDATE controle SET idPlanoControle ='$idPlano_alt', idPessoaControle ='$logado', descricao ='$descricao_controle' WHERE id='$id_controle_modifica'";
        $rsAltera = mysql_query($sqlAltera, $conexao) or die(mysql_error());

        if ($rsAltera) {
            $msgCadastro = 'A correção foi alterada com sucesso.<br><br><a href="controle.php">Continuar</a>';
        } else {
            $msgCadastro = 'A correção não foi alterada.<br>Favor repetir a operação.<br><br><a href="cadControle.php?acao=altera&idControle=' .$id_controle_modifica . '">Voltar</a>';
        }
    }
} else {
    if (isset($_GET['acao']) && $_GET['acao'] == 'altera' && isset($_GET['idControle'])) {
        $altera = 1;
        $id_controle = $_GET['idControle'];

        $sqlGetDadosPlano = "SELECT A.* FROM plano A INNER JOIN controle B ON A.id = B.idPlanoControle WHERE B.id = '$id_controle'";
        $rsGetDadosPlano = mysql_query($sqlGetDadosPlano, $conexao) or die(mysql_error());
        $auxGetDadosPlano = mysql_fetch_assoc($rsGetDadosPlano);
        $getIdPlano = $auxGetDadosPlano['id'];
        $getNomePlano = $auxGetDadosPlano['nomePlano'];
        $getDescricaoPlano = $auxGetDadosPlano['descricao'];

        $sqlGetDadosPessoa = "SELECT pessoas.* FROM pessoas INNER JOIN plano ON pessoas.id = plano.idPessoa INNER JOIN controle ON plano.id = controle.idPlanoControle  WHERE controle.id='$id_controle'";
        $rsGetDadosPessoa = mysql_query($sqlGetDadosPessoa, $conexao) or die(mysql_error());
        $auxGetPessoa = mysql_fetch_array($rsGetDadosPessoa);
        $getIdPessoa = $auxGetPessoa['id'];
        $getNomePessoa = $auxGetPessoa['nome'];
        
        $sqlGetDadosControle = "SELECT * FROM controle WHERE id='$id_controle'";
        $rsGetDadosControle = mysql_query($sqlGetDadosControle, $conexao) or die(mysql_error());
        $auxGetControle = mysql_fetch_array($rsGetDadosControle);
        $get_desc_controle = $auxGetControle['descricao'];
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
        <h2 align="center">Controle de Metas - Cadastro de Correção</h2>
        <br />
        <?php
        if (isset($msgCadastro)) {
            echo '<p align="center">' . $msgCadastro . '</p>';
        } else {
            ?>
            <form action="" method="post" name="formCadastro" id="formCadastro" enctype="multipart/form-data">

                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">

                    <tr>
                       <td align="right">Título Plano:</td>            
                        <td><select name="nomePlano" id="nomePlano">
                            <?php   $id_controle_Plano = $_GET['idControle'];
                                            $sql = "SELECT * FROM plano";
                                            $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                            
                                                if(isset($_GET['criar'])){
                                                   while($exibe = mysql_fetch_assoc($rs)){ ?>                                       
                                                        <option selected = "<?php echo $exibe['nomePlano']?> value="<?php $exibe['nomePlano']?>" > <?php  echo $exibe['nomePlano'];?> </option></>   
                                            <?php  }
                                                }else{  $sql_edita = "SELECT A.* FROM plano A INNER JOIN controle B ON A.id = B.idPlanoControle WHERE B.id = '$id_controle_Plano'";
                                                        $rs_edita = mysql_query($sql_edita, $conexao) or die(mysql_error());
                                                        while($exibe = mysql_fetch_assoc($rs_edita)){ ?>                                       
                                                            <option selected = "<?php echo $exibe['nomePlano']?> value="<?php $exibe['nomePlano']?>" > <?php  echo $exibe['nomePlano'];?> </option></>
                                        <?php           }    
                                                    }
                                                  
                                        ?>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td align="right">Descrição Correção</td>
                        <td><textarea name="descricao_controle" type="text" id='descricao_controle' cols="45" rows="4">
                            <?php
                            if (isset($altera)) {
                                    echo $get_desc_controle;
                                }
                                ?></textarea><script>CKEDITOR.replace('descricao_controle');</script>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">Responsável pela Correção:</td> 
                        <td><select tabindex="5" name="responsavel_controle" id="responsavel_controle">
                                <option  value=" <?php 
                                                    $sql = "SELECT * FROM pessoas WHERE id='$logado'";
                                                    $rs = mysql_query($sql, $conexao) or die(mysql_error());
                                                    $resultado = mysql_fetch_array($rs);
                                                    $responsavel_correcao = $resultado['nome'];
                                                    echo $responsavel_correcao ?>"> <?php echo $responsavel_correcao; ?> 
                               </option></>
                            </select>
                        </td>
                    </tr>



                </table>
                <?php
                if (isset($altera) && $altera == 1) {
                    $txtBtnCadastrar = 'Alterar';
                    $txtBtnLimpar = 'Redefinir Formulário';
                    ?>
                <input name="idControle " type="hidden" value="<?php echo $id_controle ; ?>" />
        <?php
    } else {
        $txtBtnCadastrar = 'Cadastrar';
    }
    ?>
                <p align="center">
                    <input name="btnCriarControle" type="submit" value="<?php echo $txtBtnCadastrar; ?>" />
                </p>
            </form>

            <p align="center"><a href="controle.php">Voltar</a></p>
            <p align="center"><a href="admin.php">Menu Principal</a></p>
<?php } ?>
    </body>
</html>


