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

function _zeT ($p_msg) {
    $lang = quotestr(CWebUser::$data['lang']);
    $p_msg2 = quotestr($p_msg);
    $return = valorCampo('select tx_new from zbxe_translation where tx_original = '
            .  $p_msg2 . ' and lang = ' . $lang
            ,'tx_new');
    if ($return == "") {
        $sql = "insert into zbxe_translation values (".$lang.",".$p_msg2.",".$p_msg2.")";
        preparaQuery ($sql);
        $return = $p_msg;
    }
    return $return;
}

    global $VG_DEBUG;
    $VG_DEBUG = (isset($_REQUEST['p_debug']) && $_REQUEST['p_debug'] == 'S' ? TRUE : FALSE );
    global $zeMessages, $zeLocale, $baseName;
    function exibeConteudo ($condicao,$conteudo) {
            if ($condicao) { return $conteudo;} 
            else { return array (""); }
    } 
    function newComboFilter ($query, $value, $name) {
            $cmbRange 	= new CComboBox($name, $value, 'javascript: submit();');
            $result     = DBselect($query);
            $cmbRange->additem("0", "");
            while($row_extra = DBfetch($result)){
                    $cmbRange->additem($row_extra['id'], $row_extra['description']);
            }
            return $cmbRange;
    }
    function newComboFilterArray ($array, $name, $value) {
            $cmbRange 	= new CComboBox($name, $value, 'javascript: submit();');
            $cmbRange->additem('', 'Selecione...');
            foreach ($array as $k => $v) {
                $cmbRange->additem($k, $v);
            }
            return $cmbRange;
    }
    function valorCampo ($p_query, $p_campo) {
        $retorno = "";
        $result = preparaQuery($p_query);
        while($row = DBfetch($result)){
            $retorno = $row[$p_campo];
        }
        return $retorno;
    }
    function preparaQuery ($p_query) {
        $result	= DBselect($p_query);
        if (!$result) { 
            die("Invalid query [$p_query].".mysql_error()); 
            return 0;
        } else { return $result; } 
    }
    function getBetweenStrings($start, $end, $str){
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }
    function debugInfo ($p_mensagem, $p_debug = false, $p_cor = "gray") {
        global $VG_DEBUG;
        if ($p_debug == true || $VG_DEBUG == true) {
                echo '<div style="background-color:'.$p_cor.';">'.$p_mensagem."</div>";
        }
    }
    function array_sort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    function quotestr($p_texto) { // Função para colocar aspas com mais segurança
        global $DB;
            return "'".($DB['TYPE'] == ZBX_DB_POSTGRESQL ? 
              pg_escape_string($p_texto) :
                addslashes($p_texto)
              //mysql_real_escape_string($p_texto)
                )."'";
            //pg_escape_string
    }
    function versaoZabbix () {
        return substr(ZABBIX_VERSION,0,3);
    }
    function checkAccessGroup ($p_groupid) {
        if (get_request($p_groupid) && !API::HostGroup()->isReadable(array($_REQUEST[$p_groupid]))) {
            access_deny();
        } else {
            $groupids = array ($_REQUEST[$p_groupid]);
        }
        return $groupids;
    }
    function checkAccessHost ($p_hostid) {
        if (get_request($p_hostid) && !API::Host()->isReadable(array($_REQUEST[$p_hostid]))) {
            access_deny();
        } else {
            $hostids = array ($_REQUEST[$p_hostid]);
            if ($hostids[0] == 0) {
                $hostids = array();
            }
        }
        return $hostids;
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
        $ZBXE_MENU['pages'][$i] = array('url' => $tmp[0].'.php', 'label' => _zeT($tmp[1]));
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
