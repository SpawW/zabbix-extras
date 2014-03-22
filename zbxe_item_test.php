<?php

/* Used for inicial development: 
** Objective: Test a key from a Zabbix Agent host
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
    # Includes 
    require_once dirname(__FILE__).'/include/config.inc.php';
    require_once dirname(__FILE__).'/include/classes/server/CZabbixAgent.php';
    require_once dirname(__FILE__).'/include/hosts.inc.php';
    require_once dirname(__FILE__).'/include/items.inc.php';
    require_once dirname(__FILE__).'/include/zbxe_visual_imp.php';
    # Permission and visual configuration
    $page['file'] = 'zbxe_item_test.php';
    $page['title'] = _zeT('Realtime item test');
    $min_user_type = USER_TYPE_ZABBIX_ADMIN;
    define('ZBX_PAGE_NO_MENU', 1);
    require_once dirname(__FILE__).'/include/page_header.php';
    if (isset($error)) {
            invalid_url();
    }
    if ($min_user_type > CWebUser::$data['type']) {
            access_deny();
    }
    // Verify host access permissions
    if (!API::Host()->isReadable(array($_REQUEST['hostid']))) {
            access_deny();
    }

    # Code -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    $tmp = explode(':',get_request('interface'));
    $HOST = trim($tmp[0]);
    $PORT = trim($tmp[1]);
    $TIMEOUT = get_request('timeout',3);
 /*
 * Display table header
 */
    $key = get_request('itemkey',"");
    $zabbixAgent = new CZabbixAgent($HOST, $PORT, $TIMEOUT, 0);
    if ($zabbixAgent->isRunning()) {
        $testValue = substr($zabbixAgent->getKey($key),13);
        //substr($testValue,9,16)
        $testResult = $testValue == "ZBX_NOTSUPPORTED" ? _('Not supported') : _("Supported");
    } else {
        //echo "Fora do ar.<br>[". $zabbixAgent->getError()."]";
        $testResult = $zabbixAgent->getError();
    }
    // Show results
    $item_wdgt = new CWidget();
    $table = new CTableInfo(_('No item keys found.'));
    $table->addClass('latest-details');
    $table->setHeader(array(new CSpan(_('Key')), new CSpan(_('Last value')),new CSpan(_('Status'))));
    // Formating results
    $testValue = new CDiv($testValue,'txt');
    $testValue->setAttribute('style','width: 200px; white-space: pre-wrap; ');
    $testValue = new CCol ($testValue);
    $testValue->setAttribute('valign','top');
    
    $key = new CCol ($key); 
    $key->setAttribute('valign','top');
    
    $testResult = new CCol ($testResult); 
    $testResult->setAttribute('valign','top');
    
    $table->addRow(array($key, $testValue,$testResult));
    
    
    // Combo for select timeout -=-=-=-
    $form = new CForm();
    $form->setName('itemkeyform');
    $form->setAttribute('id', 'itemkey');
    $cmbTimeout	= new CComboBox('timeout', $TIMEOUT, 'javascript: submit();');
//    $cmbTimeout->additem("1", "1 " .  _('second') );
    for ($i = 3; $i < 31; $i++) {
        $cmbTimeout->additem($i, $i . " " .  _('seconds') );
    }
    $form->addItem($cmbTimeout);
    $form->addItem(array(
        new CInput('hidden', "hostid", get_request('hostid'))
        , new CInput('hidden', "itemid", get_request('itemid'))
        , new CInput('hidden', "interface", get_request('interface'))
        , new CInput('hidden', "itemkey", get_request('itemkey'))
    ));
    $form->setMethod('get');

    $item_wdgt->addPageHeader(
            _zeT('Moment of test ').SPACE.'['.zbx_date2str(_('d M Y H:i:s')).']',
            array(
                    "Timeout"
                    , SPACE
                    , $form
            )
    );
    $item_wdgt->addItem($table);
    $item_wdgt->show();
?>