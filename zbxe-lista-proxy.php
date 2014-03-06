<?php
/*
**  Objetivo: Listar os proxyes existentes no ambiente para possibilitar auto-registro
** Adail Horst - http://spinola.net.br/blog
** 
**/

require_once('include/config.inc.php');
require_once('include/zbxe_visual_imp.php');

try {	
    $query = 'SELECT host FROM `hosts` hos WHERE hos.status IN ( 5, 6, 7 ) ';
    $result     = DBselect($query);
    $json = array();
    while($row  = DBfetch($result)){
        $json[count($json)]['{#NOME}'] = $row['host'];
    }
    echo json_encode(array('data'=>$json));
} catch(Exception $erro) {
    echo "Erro ao buscar proxys..." . $erro->getMessage();
}
