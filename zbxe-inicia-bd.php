<?php

/*
**  Objetivo: Inicializar o banco de dados do Zabbix-Extras
** Adail Horst - http://spinola.net.br/blog
**/

require_once('include/config.inc.php');
require_once('zabbix-translate.php');
try {	
    $query = 'select count(*) as total from zbxe_translation';
    $result     = DBselect($query);
    while($row  = DBfetch($result)){
        $total  = $row['total'];
    }
} catch(Exception $erro) {
    echo "Tabelas ainda não existem..." . $erro->getMessage();
}

if ($total == 0) {
    $query = "CREATE TABLE IF NOT EXISTS `zbxe_translation` (
      `lang` varchar(255) NOT NULL,
      `tx_original` varchar(255) NOT NULL,
      `tx_new` varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
    echo "Criando zbxe_translation...<br>";
    preparaQuery($query);

    $query = "CREATE TABLE IF NOT EXISTS `zbxe_preferences` (
      `userid` int(11) NOT NULL,
      `tx_option` varchar(60) NOT NULL,
      `tx_value` varchar(255) NOT NULL,
      `st_ativo` int(11) NOT NULL DEFAULT '1'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
    echo "Criando zbxe_preferences...<br>";
    preparaQuery($query);

    $query = "INSERT INTO `zbxe_translation` (`lang`, `tx_original`, `tx_new`) VALUES
    ('en_GB', 'Capacity and Trend', 'Capacity and Trend'),
    ('en_GB', 'Day', 'Day'),
    ('en_GB', 'Week', 'Week'),
    ('en_GB', 'Month', 'Month'),
    ('en_GB', 'Year', 'Year'),
    ('en_GB', 'Max', 'Max'),
    ('en_GB', 'Min', 'Min'),
    ('en_GB', 'Avg', 'Avg'),
    ('en_GB', 'Analysis', 'Analysis'),
    ('en_GB', 'Projection', 'Projection'),
    ('en_GB', 'Ammount', 'Ammount'),
    ('en_GB', 'Formatting', 'Formatting'),
    ('en_GB', 'Chart', 'Chart'),
    ('en_GB', 'Update Filter', 'Update Filter'),
    ('en_GB', 'Data from history', 'Data from history'),
    ('en_GB', 'Trend', 'Trend'),
    ('en_GB', 'Instant', 'Instant'),
    ('en_GB', 'Value', 'Value'),
    ('en_GB', 'Type', 'Type'),
    ('pt_BR', 'Capacity and Trend', 'Capacity and Trend'),
    ('pt_BR', 'Analysis', 'Analysis'),
    ('pt_BR', 'Projection', 'Projection'),
    ('pt_BR', 'Ammount', 'Ammount'),
    ('pt_BR', 'Formatting', 'Formatting'),
    ('pt_BR', 'Chart', 'Chart'),
    ('pt_BR', 'Update Filter', 'Update Filter'),
    ('pt_BR', 'Event Management', 'Event Management'),
    ('pt_BR', 'Report generated on', 'Report generated on'),
    ('pt_BR', 'Correlate', 'Correlate'),
    ('pt_BR', 'Not Supported Items', 'Not Supported Items'),
    ('pt_BR', 'Not Supported Items Report', 'Not Supported Items Report'),
    ('pt_BR', 'Storage Costs', 'Storage Costs'),
    ('pt_BR', 'Enter the parameters for research!', 'Enter the parameters for research!'),
    ('pt_BR', 'History Costs', 'History Costs'),
    ('pt_BR', 'Trends Costs', 'Trends Costs'),
    ('pt_BR', 'Format', 'Formato'),
    ('pt_BR', 'View', 'Visão');";
    echo "Populando dados padrões em zbxe_translation...<br>";
    preparaQuery($query);

    // Preferences -----------------------------------------------------------------
    $query = "INSERT INTO `zbxe_preferences` (`userid`, `tx_option`, `tx_value`, `st_ativo`) VALUES
    (0, 'map_title_show', '1', 1),
    (0, 'map_title_color', 'red', 1),
    (0, 'map_border_show', '0', 1),
    (0, 'map_background_color', 'green', 1),
    (0, 'map_border_color', 'red', 1),
    (0, 'map_date_color', 'black', 1),
    (0, 'map_company', 'SERPRO', 1),
    (0, 'logo_company', 'zbxe_logo', 1),
    (3, 'logo_company', 'zbxe_company_2', 1),
    (0, 'menu_01_cat', 'zabbix-cat|Capacity and Trends', 1),
    (0, 'menu_02_em', 'zabbix-em|Event Management', 1),
    (0, 'menu_03_ns', 'zabbix-ns|Not Supported Items', 1),
    (0, 'menu_04_sc', 'zabbix-sc|Storage Custs', 1);";

    echo "Populando dados padrões em zbxe_preferences...<br>";
    preparaQuery($query);

    /*
    $query = "INSERT INTO `zbxe_preferences` (`userid`, `tx_option`, `tx_value`, `st_ativo`) VALUES 
    (0, 'menu_10_geo', 'zbxe-geolocation|Geolocation', 1),
    (0, 'menu_10_arvore', 'zbxe-arvore|Arvore', 1)";
    echo "Inserindo menus para Arvore e Geolocalizacao...<br>";
    preparaQuery($query);

    */
} else {
    echo "Banco já inicializado!<br>";
}
echo "Pronto !!!";
