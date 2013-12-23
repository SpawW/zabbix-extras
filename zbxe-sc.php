<?php
/* Utilizei como base inicial para o desenvolvimento o "zabbix-cat.php" e alguns outros arquivos do Frontend do Zabbix
** Objetivo: Relatório de custo de armazenamento da monitoração
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
	require_once('include/hosts.inc.php');
	require_once('include/forms.inc.php');
	require_once('include/zbxe_visual_imp.php');
	
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$page['title'] 		= _zeT('Storage Costs');
	$page['file'] 		= 'zbxe-sc.php';                                    
	$page['hist_arg'] 	= array('hostid','groupid');

	include_once('include/page_header.php');
?>
<?php
	function descritivo($texto){
		$texto = str_replace("\n",";\n",$texto);
		$arrayDesc = explode("\n",$texto);
		return new CTag('div', 'yes', $arrayDesc, 'text');
	}
?>
<?php
//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields=array(
		'groupid'=>		array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'hostid'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'formato'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null), // Identificador do formato
		'view'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null), // Identificador da visão que se deseja
/* actions */
		'filter'=>			array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
	);

	check_fields($fields);
?>
<?php

	// Atribuição de variaveis ========================================
	$options = array(
		'groups' => array('monitored_hosts' => 1),
		'hosts' => array('monitored_hosts' => 1),
		'groupid' => get_request('groupid', null),
		'hostid' => get_request('hostid', null)
	);
	$pageFilter = new CPageFilter($options);
	$startDateDefault 				= 86400*30;
	$groupid = $_REQUEST['groupid'] = get_request('groupid', 0);
	$hostid = $_REQUEST['hostid']	= get_request('hostid', 0);
	$formato = $_REQUEST['formato']	= get_request('formato', 'html');
	$view = $_REQUEST['view']	= get_request('view', 'H');
	
	// Verificação de segurança =========================================

        $groupids = checkAccessGroup ('groupid');
        $hostids = checkAccessHost('hostid');
	$hostprof_wdgt 		= new CWidget();
// Formulario de Filtro =========================================================

// Combos de filtro =========================================================
	$cmbGroups 		= $pageFilter->getGroupsCB(true);
	$cmbHosts 		= $pageFilter->getHostsCB(true);
	// Combo com os formatos de exibição
	$cmbFormato = newComboFilterArray(array(
            'html' => _('HTML'), 'csv' => _('CSV')
        ),'formato',$formato);
	// Combo com os formatos de exibição
	$cmbView = newComboFilterArray(array(
            'H' => _('Item'), 'G' => _('Host')
        ),'view',$view);

// FIM Combos de filtro =========================================================
	$hostprof_wdgt->addHeader($page['title'], array());
	$filter_table = new CTable('', 'filter_config');
	$filter_table->setAttribute('border',0);
	$filter_table->setAttribute('width','100%');

	$reporttimetab2 = new CTable(null,'calendar');
	$reporttimetab2->addRow(array(array(bold("Formato"), ': '), array($cmbFormato)));
/*----------- Implementa o Filtro por período ---------------*/
	$filter_table->addRow(array(
		array(bold(_('Group')), ': ', $cmbGroups),
		array(bold(_('Host')), ': ', $cmbHosts),
		array(bold(_zeT('Format')), ': ', $cmbFormato),
		array(bold(_zeT('View')), ': ', $cmbView),
		array()
	));
	
	$filter_form = new CForm();
	$filter_form->setMethod('get');
	$filter_form->setAttribute('name','zbx_filter');
	$filter_form->setAttribute('id','zbx_filter');

	$reset = new CButton('reset',_('Reset'));
	$reset->onClick("javascript: clearAllForm('zbx_filter');");
	$filter = new CButton('filter',_zeT('Update Filter'));
	$filter->onClick("javascript: submit();");

	$footer_col = new CCol(array($filter, SPACE, $reset), 'center');
	$footer_col->setColSpan(4);

	$filter_table->addRow($footer_col);

	$filter_form->addItem($filter_table);
	$hostprof_wdgt->addFlicker($filter_form, true);

// FIM Formulario de Filtro =========================================================
	$completo = (get_request('formato','') != '') && ($groupid > 1 || $hostid > 1);
		
	if ($completo) {

// Localizar todos os hosts que o usuário selecionou -------------------------------------
		$baseQuery = 
"SELECT hos.name as host_name, it.hostid, it.name as item_name, it.key_ as item_key, it.delay, it.history, it.trends, it.status , 86400 / it.delay * it.history AS history_costs, it.trends * 24 AS trends_costs
FROM items it
INNER JOIN hosts hos
   ON hos.hostid = it.hostid
INNER JOIN hosts_groups hgr
   ON hgr.hostid = it.hostid
". ( $groupid > 0 ? " AND hgr.groupid = ".$groupid: "")
." WHERE it.flags <> 2
" . ($hostid > 0 ? " AND it.hostid = ".$hostid : "")
. "\n order by host_name, item_key " ;
                $report		= Array();
                if ($view == "G") {
                    $baseQuery = "SELECT hitem.host_name, hitem.hostid"
                            . ", SUM( history_costs ) AS history_costs, SUM( trends_costs ) AS trends_costs"
                            . " FROM (" . $baseQuery . ") hitem "
                            . " Group by hitem.hostid, hitem.host_name "
                            . " order by hitem.host_name, hitem.hostid";
                    $result = DBselect($baseQuery);
                    $cont = $historyTotal = $trendTotal = $storageTotal	= 0;
                    while($row = DBfetch($result)){
                        $report[$cont][0] = $row['host_name'];
                        $report[$cont][1] = round(floatval($row['history_costs']),2);
                        $historyTotal += $report[$cont][1];
                        $report[$cont][2] = round(floatval($row['trends_costs']),2);
                        $trendTotal += $report[$cont][2];
                        $report[$cont][4] = ($report[$cont][1]*50)+($report[$cont][2]*128);
                        $storageTotal += $report[$cont][4];
                        $report[$cont][3] = convert_units(array ('value' => $report[$cont][4], 'units' => 'B'));
                        $cont++;
                    }
                } else {
                    $result			= DBselect($baseQuery);
                    $cont = $historyTotal = $trendTotal = $storageTotal	= 0;
                    while($row = DBfetch($result)){
                            $report[$cont][0] = $row['host_name'];
                            $report[$cont][1] = $row['item_name'];
                            $report[$cont][2] = $row['item_key'];
                            $report[$cont][3] = $row['delay'];
                            $report[$cont][4] = $row['history'];
                            $report[$cont][5] = $row['trends'];
                            $report[$cont][6] = $row['status'];
                            $report[$cont][7] = round(floatval($row['history_costs']),2);
                            $historyTotal += $report[$cont][7];
                            $report[$cont][8] = round(floatval($row['trends_costs']),2);
                            $trendTotal += $report[$cont][8];
                            $report[$cont][10] = ($report[$cont][7]*50)+($report[$cont][8]*128);
                            $storageTotal += $report[$cont][10];
                            $report[$cont][9] = convert_units(array ('value' => $report[$cont][10], 'units' => 'B'));
                            $cont++;
                    }
                }
 // Monta o relatório ----------------------------------------------------------
		$table = new CTableInfo();
		switch ($formato) {
                    case 'csv';
                        $table->setHeader(array("Dados"));	
                        break;
                    case 'html';
                        if ($view == "G") {
                            $table->setHeader(array(_("Host"),_zeT("History Costs"),_zeT("Trends Costs")
                                ,_zeT("Storage Costs")
                            ));	
                        } else {
                            $table->setHeader(array(_("Host"),_("Item"),_("Key"),_("Delay")
                                ,_("History"),_("Trends"),_("Status")
                                ,_zeT("History Costs"),_zeT("Trends Costs")
                                ,_zeT("Storage Costs")
                            ));	
                        }
                        break;			
		}
		$linha = array();
		$cont2 = count($report[0])-1;
		for ($i = 0; $i < $cont; $i++) {		
                    switch ($formato) {
                        case 'csv';
                            $linhaCSV = "";
                            for ($x = 0; $x < $cont2; $x++) {
                                $linhaCSV .= quotestr($report[$i][$x].($x == 7 || $x == 8 ? " linhas" : " ")).";";
                            }
                            $table->addRow(array($linhaCSV));
                            break;
                        case 'html';
                            for ($x = 0; $x < $cont2; $x++) {
                                $linha[$x] = new CCol($report[$i][$x].($x == 7 || $x == 8 ? " linhas" : " "),1);
                            }
                            $table->addRow($linha);
                            break;			
                    }
		}
		$descricao = new CCol ('');
		$descricao->setAttribute('colspan','6');
		$descricao->setAttribute('align','right');
		if ($formato !== 'csv') {
                    if ($view == "G") {
                        $table->addRow(array('Total',$historyTotal.' linhas',$trendTotal.' linhas'
                            ,convert_units(array('value' => $storageTotal,'units' => 'B'))));
                    } else {
                        $table->addRow(array($descricao,'Total',$historyTotal.' linhas',$trendTotal.' linhas'
                            ,convert_units(array('value' => $storageTotal,'units' => 'B'))));
                    }
		}
		$numrows = new CDiv();
		$numrows->setAttribute('name', 'numrows');
		$hostprof_wdgt->addHeader($numrows);
	
		$paging = getPagingLine($report);
	
		$hostprof_wdgt->addItem($table);
	} else {
		$hostprof_wdgt->addItem(_zeT('Enter the parameters for research!'));
	}
	$hostprof_wdgt->show();
?>
<?php 
	include_once('include/page_footer.php');
?>
