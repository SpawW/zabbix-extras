<?php

/* 
 * Objetivo: Gerar dinamicamente o logotipo do zabbix permitindo customizacao por usuario
 * Adail Horst - http://spinola.net.br/blog
 * Parte integrante do zabbix-extras
 * Nao e permitida qualquer alteracao que renomeie ou complique a identificacao do produto
 */

require_once dirname(__FILE__).'/include/config.inc.php';
require_once dirname(__FILE__).'/include/zbxe_visual_imp.php';
header( "Content-type: image/png" );
$query = "SELECT image FROM images WHERE name = '".$ZBXE_VAR['logo_company']."'";
echo  zbxeFieldValue($query,'image');