<?php 
session_start();
if (isset($_SESSION['idAdmin'])){
    include_once("includes/conexao.php");
    $logado = $_SESSION['idAdmin']; 
    $nome_logado = $_SESSION['nomeAdmin'];
}else{
    header('Location: index.php');
}


if (isset($_POST['btnCadastrarPessoa'])) {


    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $datanasc = $_POST['datanasc'];
    $endereco = $_POST['endereco'];
    $cargo = $_POST['cargo'];
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);
    
    



    if ($_POST['btnCadastrarPessoa'] != 'Alterar') {
        $sql0 = " SELECT cpf, email, nome, datanasc, login, senha, cargo, endereco, celular FROM pessoas WHERE cpf = '$cpf' ";
        $rs0 = mysql_query($sql0, $conexao) or die(mysql_error());
        
        if (mysql_num_rows($rs0) == 0) {


            $sql_cadastro_pessoa = "INSERT INTO pessoas (cpf, email, nome, datanasc, login, senha, cargo, endereco, celular  ) VALUES ('$cpf', '$email', '$nome' , '$datanasc', '$login', '$senha', '$cargo', '$endereco','$celular')";
            $rs_cadastro_pessoa = mysql_query($sql_cadastro_pessoa, $conexao) or die(mysql_error());
            
            if ($sql_cadastro_pessoa) {
                $msgCadastro = 'O cadastro de <strong>' . $nome . '</strong> foi efetuado com sucesso.<br><br><a href="pessoas.php">Continuar</a>';
            } else {
                $msgCadastro = 'O cadastro não foi efetuado. Favor repetir a operação.<br><br><a href="cadPessoas.php">Voltar</a>';
            }
        } else {
            $msgCadastro = 'Cadastro não realizado.<br><br>O CPF cadastrado já consta na base de dados.';
        }
    } else {
        $id_pessoa = $_POST['idPessoa'];
        
        $sql_pessoa_altera = "SELECT * FROM pessoas WHERE id = '$id_pessoa'";
        $rs_pessoa_altera = mysql_query($sql_pessoa_altera, $conexao) or die(mysql_error());
        
        $aux_pessoa_altera = mysql_fetch_array($rs_pessoa_altera);
        
        $pessoa_altera_nome = $aux_pessoa_altera['nome'];
        $pessoa_altera_email = $aux_pessoa_altera['email'];
        $pessoa_altera_celular = $aux_pessoa_altera['celular'];
        $pessoa_altera_datanasc = $aux_pessoa_altera['datanasc'];
        $pessoa_altera_endereco = $aux_pessoa_altera['endereco'];
        $pessoa_altera_cargo = $aux_pessoa_altera['cargo'];
        $pessoa_altera_login = $aux_pessoa_altera['login'];
        $pessoa_altera_senha = md5($aux_pessoa_altera['senha']);
        
        $sql_atualizar_pessoas = " UPDATE pessoas SET cpf='$cpf', email='$email', nome = '$nome', datanasc='$datanasc',login ='$login', senha = '$senha', cargo='$cargo', endereco='$endereco',   celular ='$celular'  WHERE id = '$id_pessoa'";
        $rs_atualizar_pessoas = mysql_query($sql_atualizar_pessoas, $conexao) or die(mysql_error());
        
        if ($rs_atualizar_pessoas) {
            $msgCadastro = 'O cadastro foi alterado com sucesso.<br><br><a href="pessoas.php">Continuar</a>';
        } else {
            $msgCadastro = 'O cadastro não foi alterado.<br>Favor repetir a operação.<br><br><a href="cadPessoa.php?acao=altera&idPessoa=' . $id_pessoa . '">Voltar</a>';
        }
    }
} else {
    if (isset($_GET['acao']) && $_GET['acao'] == 'altera' && isset($_GET['idPessoa'])) {
        $altera = 1;
        $id_pessoa = $_GET['idPessoa'];

        $sql_dados_pessoa = "SELECT * FROM pessoas WHERE id='$id_pessoa'";
        $rs_dados_pessoa= mysql_query($sql_dados_pessoa, $conexao) or die(mysql_error());
        $dados_pessoa = mysql_fetch_array($rs_dados_pessoa);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Controle de Metas</title>
        <script type="text/javascript">
        /* Máscaras ER */
            function mascara(o,f){
                v_obj=o
                v_fun=f
                setTimeout("execmascara()",1)
            }
            function execmascara(){
                v_obj.value=v_fun(v_obj.value)
            }
            function mtel(v){
                v=v.replace(/\D/g,""); 
                v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); 
                v=v.replace(/(\d)(\d{4})$/,"$1-$2"); 
                return v;
            }
            function id( el ){
                return document.getElementById( el );
            }
            window.onload = function(){
                id('celular').onkeypress = function(){
                                                mascara( this, mtel );
                                            }
                            }
        </script>
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
        <h2 align="center">Controle de Metas - Cadastro de Pessoas</h2>
        <br />
     <?php
        
        if (isset($msgCadastro)) {
            echo '<p align="center">' . $msgCadastro . '</p>';
        } else {
            ?>
            <form action="" method="post" name="formCadastro" id="formCadastro" enctype="multipart/form-data">

                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">

                    <tr>
                        <td align="right">Nome Completo:</td>
                        <td><input name="nome" type="text" id="nome" size="50" maxlength="100" <?php if (isset($altera)) echo 'value="'.$dados_pessoa['nome'].'"';?> /></td>
                    </tr>
                    <tr>
                        <td align="right">CPF:</td>
                        <td><input name="cpf" type="number" id="cpf" size="11" <?php if (isset($altera)) echo 'value="' . $dados_pessoa['cpf'] . '"';?> /></td>
                    </tr>
                    <tr>
                        <td align="right">E-mail:</td>
                        <td><input name="email" type="email" id="email" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['email'] . '"';} ?> /></td>
                    </tr>
                    <tr>
                        <td align="right">Celular:</td>
                        <td><input name="celular" id="celular" size="50" maxlength="15" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['celular'] . '"';} ?> /></td>
                    </tr>
                    <tr>
                        <td align="right">Data de Nascimento:</td>
                        <td><input name="datanasc" type="date" id="datanasc" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['datanasc'] . '"';} ?> /></td>
                    </tr>
                    <tr>
                        <td align="right">Endereço:</td>
                        <td><input name="endereco" type="text" id="endereco" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['endereco'] . '"';} ?> /></td>
                    </tr>
                     <tr>
                        <td align="right">Cargo:</td>
                        <td><input name="cargo" type="text" id="cargo" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['cargo'] . '"';} ?> /></td>
                    </tr>
                     <tr>
                        <td align="right">Login:</td>
                        <td><input name="login" type="text" id="login" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['login'] . '"';} ?> /></td>
                    </tr>
                     <tr>
                        <td align="right">Senha:</td>
                        <td><input name="senha" type="password" id="senha" size="50" maxlength="100" <?php if (isset($altera)) { echo 'value="' . $dados_pessoa['senha'] . '"';} ?> /></td>
                    </tr>
                </table>
                
    <?php
        
    if (isset($altera) && $altera == 1) {
        $txtBtnCadastrar = 'Alterar';
        $txtBtnLimpar = 'Redefinir Formulário';
    ?>
        <input name="idPessoa" type="hidden" value="<?php echo $id_pessoa; ?>" />
    <?php
    } else {
        $txtBtnCadastrar = 'Cadastrar';
    }

        
    ?>
                <p align="center">
                    <input name="btnCadastrarPessoa" type="submit" value="<?php echo $txtBtnCadastrar; ?>" />
                </p>
            </form>
        <p align="center"><a href="pessoas.php">Voltar</a></p>
<?php } ?>
        <p align="center"><a href="admin.php">Menu Principal</a></p>

    </body>
</html>
