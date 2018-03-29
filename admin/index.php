<?php

session_start();
/* //verificando sucesso na conexão
 session_start();
include_once("includes/conexao.php");
$sql="SELECT id FROM pessoas";
$rs = mysql_query($sql, $conexao) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)) {
echo $row['id'];}*/

if(isset($_GET['acao']) && $_GET['acao'] == 'sair') {
	unset($_SESSION['idAdmin']);
	unset($_SESSION['nomeAdmin']);
	header("Location: index.php?msg=sair");
}

if(isset($_SESSION['idAdmin'])){?>
      <script language= "JavaScript"> location.href="admin.php"</script>
<?php          
}

if(isset($_POST['btnLogin'])) {   
    
  include_once("includes/conexao.php");
  $usuario = $_POST['user'];
  $senha = md5($_POST['pass']);
  $sql="SELECT id,nome,login, senha FROM pessoas WHERE login='$usuario'AND senha='$senha'";
  $rs = mysql_query($sql, $conexao) or die(mysql_error());

  if(mysql_num_rows($rs) > 0) {
     

        $_SESSION['idAdmin'] = mysql_result($rs, 0, 'id');
        $_SESSION['nomeAdmin'] = mysql_result($rs, 0, 'nome');
        ?>
        <script>
            window.location = "http://designtec.com.br/teste/testeI/controleMetas/admin/admin.php";
        </script>';
 <?php }
  else {
  unset($_SESSION['idAdmin']);
	unset($_SESSION['nomeAdmin']);
        echo'
        <script>
            window.location = "index.php?msg=erroLogin";
        </script>';
  }
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Controle de metas</title>
    </head>

    <body>
        <form action="" method="post" id="frmLoginAdmin">
                <table width="300" border="0" align="center" cellpadding="5" cellspacing="2">
                    <tr><th colspan="2">Administração - Controle de Metas</th></tr>
                        <?php
                            if(isset($_GET['msg'])) {
				    switch($_GET['msg']) {
					case 'sair' : $msg = '<span>Sa&iacute;da efetuada com sucesso</span>'; break;
					case 'erroLogin' : $msg = '<span>Login inv&aacute;lido</span>'; break;
					default : $msg = '&nbsp;';
                                    }
                        ?>
                            <tr>
                                <th colspan="2" style="color:#9BDEFF"><?php echo $msg; ?></th>
                            </tr>
                        <?php
                            }
                        ?>
                    <tr>
                        <td align="right">Usuário</td>
                        <td><input name="user" type="text" size="20" maxlength="20" id="user" value="" /></td>
                    </tr>
                    <tr>
                        <td align="right">Senha</td>
                        <td><input name="pass" type="password" size="20" maxlength="20" id="pass" value="" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input name="btnLogin" id="btnLogin" type="submit" value="Entrar" /></td>
                    </tr>
                </table>
        </form>
    </body>
</html>