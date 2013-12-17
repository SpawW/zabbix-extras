<?php

/* 
 * Objetivo: Gestao de melhorias e customizacoes visuais no zabbix -------------
 * Adail Horst - http://spinola.net.br/blog
 * Parte integrante do zabbix-extras
 * Nao e permitida qualquer alteracao que renomeie ou complique a identificacao do produto
 */

# Zabbix-Extras - Global Variables Start
function zbxeFieldValue ($query, $field) {
    $res = DBselect($query);
    while ($row = DBfetch($res)) {
        $return = $row[$field];
    }
    return $return;
}
global $ZBXE_VAR, $ZBXE_MENU;
$ZBXE_VAR = array();
$ZBXE_MENU = array(
		'label'				=> _('Extras'),
		'user_type'			=> USER_TYPE_ZABBIX_ADMIN,
		'node_perm'			=> PERM_READ,
		'default_page_id'	=> 0,
                'pages' => array('')
                );
// Busca em banco de dados todas as variaveis;
$res = DBselect('SELECT userid, tx_option, tx_value from zbxe_preferences zpre '
        .' WHERE userid in (0,'.CWebUser::$data['userid'].') and st_ativo = 1 '
        .' order by userid, tx_option');
$i=0;
while ($row = DBfetch($res)) {
    if (strpos ($row['tx_option'], 'menu_') !== false) {
        $tmp = explode("|", $row['tx_value']);
        $ZBXE_MENU['pages'][$i] = array('url' => $tmp[0].'.php', 'label' => _($tmp[1]));
        $i += 1;
        //array("file" => $tmp[0],"title" => $tmp[1]);
    } else {
        $ZBXE_VAR[$row['tx_option']] = $row['tx_value'];
    }
}
//var_dump($ZBXE_MENU);
unset($tmp);
// Calcula o tamanho do nome da empresa no grafico
$ZBXE_VAR['map_date_width'] = 120 + round((strlen($ZBXE_VAR['map_company'].' ')*5));
# Zabbix-Extras - Global Variables End
