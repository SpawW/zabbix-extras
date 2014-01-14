<?php

/* Interface de manutenção de configurações do zabbix Extras com o foco no usuário 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Inicia Variaveis -------------------------------------------------------------
//MUDEI    
function zbxeFields() {
    global $fields, $ZBXE_VAR;
    foreach ($ZBXE_VAR as $key => $value) {
       $fields[$key] = array(T_ZBX_STR, O_OPT, null, null, null);
       $fields[$key."_adm"] = array(T_ZBX_STR, O_OPT, null, null, null);
    }
    $fields['xbxe_clean']  = array(T_ZBX_STR, O_OPT, null, null, null);
    $fields['translate']  = array(T_ZBX_STR, O_OPT, P_SYS, null, null);    
//    var_dump($fields);
}
function zbxeControler() {
    global $fields, $ZBXE_VAR, $CAMPOS;
    global $_SERVER;
    if (strpos($_SERVER["REQUEST_URI"],"users.php") > 0 && get_request('userid',-1) > -1) {
        $userid = get_request('userid', 0);
//        var_dump("id de usuario". $userid);
    } else {
        $userid = CWebUser::$data['userid'];
    }
    
    // Salvando tradução ------------==================--------------===========
    $translation = get_request('translate', array());
    foreach ($translation as $number => $curString) {
//        var_dump($curString);
        $query = "update zbxe_translation set tx_new = " . quotestr($curString['new'])
           . " where tx_original = " . quotestr($curString['original'])
           . " and lang = " . quotestr(CWebUser::$data['lang']);
        preparaQuery($query);
    }

    // Garante que todas os registros alterados estão no local correto ---------
    $update = "";
    // Salvando preferencias ===================================================
    if (isset($_REQUEST['save'])) {
//var_dump($_REQUEST);
        if (isset ($_REQUEST['xbxe_clean']) && $_REQUEST['xbxe_clean'] == "yes") {
            $query = "delete from zbxe_preferences  where userid = " . $userid;
//var_dump($query);
            preparaQuery($query);
        } else {
            $_REQUEST['xbxe_clean'] = get_request('xbxe_clean');
        }
        foreach ($ZBXE_VAR as $key => $value) {
//var_dump ("<br>--".$key);
            if (strpos($key,'_show') == 0 && $_REQUEST['xbxe_clean'] != "yes") {
                $tmp = get_request($key);
                // Atualizando dados de usuario ------------------------------------
//                var_dump("$key - $tmp - " . zbxeConfigValue($key,$userid ) . "<br>");
                if (zbxeConfigValue($key,$userid ) != $tmp && strlen($tmp) > 0) {
//                    var_dump("oi<br>");
                    // Verifica se ja existe registro para o usuario, se nao existir insere
                    if (zbxeConfigValue($key,$userid ) == "") {
                        $query = "insert into zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES ("
                          . $userid . ", " . quotestr($key). ", ". quotestr($tmp)
                          . " ,1 ) " ;
                    } else {
                        $query = "update zbxe_preferences set tx_value = " . quotestr($tmp)
                           . " where userid = ". $userid ." and tx_option = " . quotestr($key) . " " ;
                    }
//                    var_dump($query);
                    preparaQuery($query);
                }
            }
            // Atualizando dados default ---------------------------------------
            if (uint_in_array(CWebUser::$data['type'], array(USER_TYPE_SUPER_ADMIN))) {
                
                $tmp = get_request($key."_adm");
                if (strpos($key,'_show') > 0 && $tmp == "") {
                    $tmp = '0';
                }
//                var_dump("<br>--> Config Admin: $key ". zbxeConfigValue($key,0) . " - novo valor [$tmp]");
                if (zbxeConfigValue($key,0) != $tmp && strlen($tmp) > 0) {                    
                    $query = "update zbxe_preferences set tx_value = " . quotestr($tmp)
                       . " where userid = 0 and tx_option = " . quotestr($key) . " " ;
//                var_dump($query);
                    preparaQuery($query);
                }
            }
        }
    }
}

function zbxeView($userTab) {
    global $_SERVER;
    // -----------------------------------------------------------------------------
    zbxeControler();

    $userFormExtra = zbxeShowPreferences ("");
    $userFormExtraAdmin = zbxeShowPreferences ("_adm");
    // Personalização específica do usuário ------------------------------------
    $userTab->addTab('zeTab', _zeT('Extras'), $userFormExtra);
    // Personalizações globais -------------------------------------------------
    if (uint_in_array(CWebUser::$data['type'], array(USER_TYPE_SUPER_ADMIN))) {
        if (strpos($_SERVER["REQUEST_URI"],"users.php?form=update&userid=") < 1) {
            $userTab->addTab('zeTabAdmin', _zeT('Extras - Default'), $userFormExtraAdmin);
            // Personalização de traduções ---------------------------------------------
            $userTab->addTab('zeTabTrans', _zeT('Translate'), zbxeShowTranslation());
        }
    }
    return $userTab;
}

function zbxeShowTranslation () {
    //global $ZBXE_VAR;
    //$userid = ($id != "" ? 0 : CWebUser::$data['userid']);
    
    $userFormExtra = new CFormList('userFormExtraTra');
    
    $userFormExtra->addRow(_zeT('English String'), _zeT('Translation for') . " " . CWebUser::$data['lang']);
    $res = DBselect('select tx_original, tx_new from zbxe_translation where lang='
            .quotestr(CWebUser::$data['lang']).' order by tx_original');
    $i=0;
    while ($row = DBfetch($res)) {
        $tx_new = new CTextBox("translate[$i][new]", $row['tx_new'], ZBX_TEXTBOX_STANDARD_SIZE);
        $tx_original = new CInput('hidden', "translate[$i][original]", $row['tx_original']);
        $userFormExtra->addRow($row['tx_original'],array($tx_new,$tx_original));
        $i++;
    }
    return $userFormExtra;
}

function zbxeShowPreferences ($id) {
    //global $ZBXE_VAR;
    global $_SERVER;
    if (strpos($_SERVER["REQUEST_URI"],"users.php?form=update&userid=") > 0) {
        $userid = get_request('userid', 0);
    } else {
        $userid = ($id != "" ? 0 : CWebUser::$data['userid']);
    }
    
    $userFormExtra = new CFormList('userFormExtra'.$id);
    // Interface Web
    $companyTable = new CTable();
    $companyTable->setAttribute('id', 'extrasTab'.$id);
    $companyTable->addRow(array(SPACE, SPACE, _('Name'), _zeT('Color'), SPACE, SPACE));
//var_dump ('map_company'.$id . " - [$userid]" . zbxeConfigValue('map_company',$userid) . "<br>");
    $mapCompany = new CTextBox('map_company'.$id, zbxeConfigValue('map_company',$userid), ZBX_TEXTBOX_STANDARD_SIZE);
    $mapCompany->attr('autofocus', 'autofocus');
    $mapDateColor = new CColor('map_date_color'.$id, zbxeConfigValue('map_date_color',$userid));
    $companyTable->addRow(array(SPACE, SPACE, $mapCompany, $mapDateColor, SPACE, SPACE));

    $userFormExtra->addRow(_zeT('Company'), new CDiv($companyTable, 'objectgroup inlineblock border_dotted ui-corner-all'));

    $mapBackColor = new CColor('map_background_color'.$id, zbxeConfigValue('map_background_color',$userid));

    $mapTable = new CTable();
    $mapTable->setAttribute('id', 'borderTable');
    $mapTable->addRow(array(SPACE, SPACE, _('Background'), SPACE, _zeT('Border'), SPACE, _('Title'), SPACE, SPACE));
    $mapTable->addRow(array(SPACE, SPACE, $mapBackColor, SPACE, array(
        new CColor('map_border_color'.$id, zbxeConfigValue('map_border_color',$userid)),
        ($userid == 0 ? new CCheckBox('map_border_show'.$id, zbxeConfigValue('map_border_show',$userid), null, 1) : "")
        ), SPACE, array(
        new CColor('map_title_color'.$id, zbxeConfigValue('map_title_color',$userid)),
        ($userid == 0 ? new CCheckBox('map_title_show'.$id, zbxeConfigValue('map_title_show',$userid), null, 1) : "")
        ), SPACE, SPACE));

    $userFormExtra->addRow(_zeT('Maps'), new CDiv($mapTable, 'objectgroup inlineblock border_dotted ui-corner-all'));
    if (CWebUser::$data['userid'] > 0) { 
        $userFormExtra->addRow(_zeT('Delete User Personalization'), new CCheckBox('xbxe_clean'));
    }
    return $userFormExtra;
}

