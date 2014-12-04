<?php

/* Used for inicial development:
** Objective: Common functions used by Zabbix-Extras Module
** Copyright 2014 - Adail Horst - http://spinola.net.br/blog
**
** This file is part of Zabbix-Extras.
** It is not authorized any change that would mask the existence of the plugin. 
** The menu names, logos, authorship and other items identificatory plugin 
** should always be maintained.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
** If not, see http://www.gnu.org/licenses/.
**/
define("ZE_VER", "2.1.2");
define("ZE_COPY", ", ZE ".ZE_VER);

# Recover web paramiter
function parametroWeb ($nome) {
    $parametro = "";    
    return $parametro;
}
# Zabbix-Extras - Global Variables Start
function zbxeFieldValue ($query, $field) {
    $res = DBselect($query);
    while ($row = DBfetch($res)) {
        $return = $row[$field];
    }
    return $return;
}

function descItem ($itemName, $itemKey) {
    if (strpos($itemName,"$") !== false) {
        $tmp = explode("[",$itemKey);
        $tmp = explode(",",str_replace("]","",$tmp[1]));
        for ($i = 0; $i < count($tmp); $i++) {
            $itemName = str_replace("$".($i+1),$tmp[$i],$itemName);
        }
    }
    return $itemName;
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
            global $DB;
            die("Invalid query [$p_query].". ( $DB['TYPE'] == ZBX_DB_POSTGRESQL ? "" : mysql_error())); 
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
        )."'";
    }
    function versaoZabbix () {
        return str_replace(".","",substr(ZABBIX_VERSION,0,5));
    }
    function checkAccessGroup ($p_groupid) {
        if (getRequest($p_groupid) && !API::HostGroup()->isReadable(array($_REQUEST[$p_groupid]))) {
            access_deny();
        } else {
            $groupids = array ($_REQUEST[$p_groupid]);
        }
        return $groupids;
    }
    function checkAccessHost ($p_hostid) {
        if (getRequest($p_hostid) && !API::Host()->isReadable(array($_REQUEST[$p_hostid]))) {
            access_deny();
        } else {
            $hostids = array ($_REQUEST[$p_hostid]);
            if ($hostids[0] == 0) {
                $hostids = array();
            }
        }
        return $hostids;
    }
    
    function zbxeConfigValue ($param, $id = 0) {
        $query = 'select tx_value from zbxe_preferences where userid = '
                . $id . " and tx_option = " . quotestr($param);
        //var_dump("<br>[$query]<br>");
        $retorno = valorCampo($query, 'tx_value');
        return $retorno;
    }

    function zbxeSubMenus ($menu) {
        $query = 'select tx_value from zbxe_preferences where tx_option = ' . quotestr($menu);
        $res = DBselect($query);
        $retorno = array();        
        $i = 0;
        while ($row = DBfetch($res)) {
            $tmp = explode("|", $row['tx_value']);
            $retorno[$i] = array('url' => $tmp[0], 'label' => _zeT($tmp[1]));
            $i += 1;
        }
        return $retorno;
    }
    
global $ZBXE_VAR, $ZBXE_MENU;
$ZBXE_VAR = $ZBXE_SUBMENU = array();
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
        if (strpos ($row['tx_option'], 'submenu_') !== false) {
            $ZBXE_SUBMENU[count($ZBXE_SUBMENU)] = $row['tx_value'];
        } else {
            $tmp = explode("|", $row['tx_value']);
            $ZBXE_MENU['pages'][$i] = array('url' => $tmp[0].'.php', 'label' => _zeT($tmp[1]));
            $i += 1;
        }
    } else {
        $ZBXE_VAR[$row['tx_option']] = $row['tx_value'];
    }
}
$ZBXE_MENU['pages'][0]['sub_pages'] = $ZBXE_SUBMENU;
//var_dump($ZBXE_VAR);
unset($tmp);
// Calcula o tamanho do nome da empresa no grafico
$ZBXE_VAR['map_date_width'] = 120 + round((strlen($ZBXE_VAR['map_company'].' ')*5));
// Configurar o MapElementTitleColor
//$tmp = $ZBXE_VAR['map_element_title_color'] == "" ? ;
$ZBXE_VAR['map_element_title_color_rgb'] = (isset($ZBXE_VAR['map_element_title_color']) ? hex2rgb($ZBXE_VAR['map_element_title_color']) : array(255,255,255) );
//var_dump($ZBXE_VAR['map_element_title_color']);
# Zabbix-Extras - Global Variables End
