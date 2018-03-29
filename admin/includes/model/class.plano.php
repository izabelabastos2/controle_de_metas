<?php
   include '../conexao.php';
class plano{
/*seleciona os planos*/
    

	function selectPlano($id){
            include '../conexao.php';
		if($id){
			$query = "SELECT * FROM plano WHERE idPlano='$id'";
		}else{
			$query = "SELECT * FROM campanha";
		}
		
		$dados = mysql_query($query);
		$i=0;
		$arrayReturn=array();
		while ($result = mysql_fetch_object($dados)){
			$i++;			
			$arrayReturn[$i]['nomePlano']	=	$result->nomePlano;
		}
		return $arrayReturn;
        }
}