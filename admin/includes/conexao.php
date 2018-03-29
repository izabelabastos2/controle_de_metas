<?php 

/*---------------------------------------------------------------------
Configuracao de acesso a banco 
---------------------------------------------------------------------*/

/*
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = 'chuchipano7458';
$db['db']   = 'styllus';
*/
$db['host'] = 'localhost';
$db['user'] = 'design_testeMeta';
$db['pass'] = '123mudar';
$db['db']   = 'design_teste2015';


/*---------------------------------------------------------------------
Conexao 
---------------------------------------------------------------------*/

// Montagem do Data Source Name
$dsn = "mysql:host={$db['host']};dbname={$db['db']}";
// Opcao para MySQL - define conexao sempre em UTF8
//$driver_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
//
// Conexao ou erro
try
{
  $conexao = new PDO($dsn, $db['user'], $db['pass']);// $driver_options);
} 
catch (PDOException $e)
{ 
  echo 'Erro: ' . $e->getMessage();
}

$conexao = mysql_pconnect($db['host'],$db['user'],$db['pass']) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($db['db'], $conexao);

mysql_set_charset('UTF8', $conexao);
?>    