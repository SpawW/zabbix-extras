<?php header("Content-Type: text/html; charset=utf-8", true); 

/*
 * Arquivo de exportação de dados do zabbix-extras
 */
require_once('include/config.inc.php');
require_once('include/zbxe_visual_imp.php');
$campos = "lang, tx_original, tx_new";
$res = DBselect("SELECT $campos FROM zbxe_translation");
$i=0;
while ($row = DBfetch($res)) {
    echo "preparaQuery(\"INSERT INTO zbxe_translation ($campos) VALUES("
       .quotestr($row['lang']).", "
       .quotestr($row['tx_original']).", "
       .quotestr($row['tx_new']).")\");<br>";
}

$campos = "userid, tx_option, tx_value, st_ativo";
$res = DBselect("SELECT $campos FROM zbxe_preferences");
$i=0;
while ($row = DBfetch($res)) {
    echo "preparaQuery(\"INSERT INTO zbxe_preferences ($campos) VALUES("
       .quotestr($row['userid']).", "
       .quotestr($row['tx_option']).", "
       .quotestr($row['tx_value']).", "
       .quotestr($row['st_ativo']).")\");<br>";
}

