<?php

//Resume o texto e nao corta as palavras
function resumo($texto,$qnt){
    $resumo=substr($texto,'0',$qnt);
    $last=strrpos($resumo," ");
    $resumo=substr($resumo,0,$last);
    return $resumo."...";
}


