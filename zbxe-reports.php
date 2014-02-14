<?php
/* Menu de acesso a relatorios do Zabbix-Extras
 * A ideia é consolidar relatorios e simplificar chamadas com sub-menus
** Adail Horst - http://spinola.net.br/blog

** Comentários do arquivo original -------------------------------------------
** ZABBIX
** Copyright (C) 2000-2010 SIA Zabbix
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
** Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
** 

**/
?>
<?php
	require_once('include/config.inc.php');
	require_once('include/forms.inc.php');
	require_once('include/zbxe_visual_imp.php');
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$titulo 		= _zeT('Reports');//'Zabbix-IS - Capacidade e Tendência';
	$page['title'] 		= $titulo;
	$page['file'] 		= 'zbxe-reports.php';
	#$page['hist_arg'] 	= array('hostid','groupid','graphid');

	include_once('include/page_header.php');
?>
<?php
//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields=array(
 		 'formato'  =>array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null) // Identificador do formato
/* actions */
		,'submenu'   =>array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
		,'filter'   =>array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
	);
        $formato = $_REQUEST['formato']	= get_request('formato', 'html');
        $subMenu = $_REQUEST['submenu']	= get_request('submenu', 'html');
	check_fields($fields);
?>
<?php

    $options = array(
        'formato' => get_request('formato', null)
        , 'submenu' => get_request('submenu', null)
    );

    $pageFilter = new CPageFilter($options);
    $filter_table = new CTable('', 'filter_config');
    $filter_table->setAttribute('border',0);
    $filter_table->setAttribute('width','100%');
    
    // Verificação de segurança ============================================
    //$groupids = checkAccessGroup ('groupid');
    //$hostids = checkAccessHost('hostid');

    $hostprof_wdgt 		= new CWidget();
// Formulario de Filtro comuns =================================================

// FIM Combos de filtro =========================================================
    $filter_form = new CForm();
    $filter_form->setMethod('get');
    $filter_form->setAttribute('name','zbx_filter');
    $filter_form->setAttribute('id','zbx_filter');
// Botões do formulario de filtro ----------------------------------------------
    $reset = new CButton('reset',_('Reset'));
    $reset->onClick("javascript: clearAllForm('zbx_filter');");
    $filter = new CButton('filter',_zeT("Update Filter"));
    $filter->onClick("javascript: submit();");
    $footer_col = new CCol(array($filter, SPACE, $reset), 'center');
    $footer_col->setColSpan(4);
    $filter_table->addRow($footer_col);
    $filter_form->addItem($filter_table);
//    $filter_form->addVar('report_timesince', date('YmdHis', $report_timesince));
//    $filter_form->addVar('report_timetill', date('YmdHis', $report_timetill));
    $hostprof_wdgt->addFlicker($filter_form, true);
// FIM Formulario de Filtro =========================================================
    insert_js ($script);
    $numrows = new CDiv();
    $numrows->setAttribute('name', 'numrows');
    $report			= Array();

    $hostprof_wdgt->addHeader($numrows);
    $r_form = new CForm();

    // Combo com os formatos de exibição ---------------------------------------
    $cmbFormato= new CComboBox('formato', $formato, 'javascript: submit();');
    $cmbFormato->additem('html', 'HTML');
    $cmbFormato->additem('csv', 'CSV');
    // Combo com as opções de relatorios possiveis -----------------------------
    $cmbMenus= new CComboBox('submenu', $subMenu, 'javascript: submit();');
//    var_dump (zbxeSubMenus ('submenu_05'));
    foreach (zbxeSubMenus ('sub_report') as $row) {
        $cmbMenus->additem($row['url'], $row['label']);
    }
    $r_form->addItem(array(array(bold(_zeT('Formatting')), ': '), array($cmbFormato)));
    $r_form->addItem(array(array(bold(_zeT('Report')), ': '), array($cmbMenus)));
         
    $hostprof_wdgt->addPageHeader(_zeT('Zabbix-Extras Reports'));
    $hostprof_wdgt->addHeader(_('Report'), $r_form);
//    $hostprof_wdgt->addItem(BR());
    
    $paging = getPagingLine($report);
    //$hostprof_wdgt->addItem($table);
    $hostprof_wdgt->show();
?>
<?php 
	include_once('include/page_footer.php');
?>