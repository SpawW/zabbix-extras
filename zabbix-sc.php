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
	require_once('zabbix-translate.php');
	
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$page['title'] 		= _ze('Zabbix-SC-Title');
	$page['file'] 		= 'zabbix-sc.php';
	$page['hist_arg'] 	= array('hostid','groupid');

	include_once('include/page_header.php');
?>
<?php
/*	function newComboFilter ($query, $value, $name) {
		$cmbRange 		= new CComboBox($name, $value, 'javascript: submit();');
		$result			= DBselect($query);
		$cmbRange->additem("0", "");
		while($row_extra = DBfetch($result)){
			$cmbRange->additem($row_extra['id'], $row_extra['description']);
		}
		return $cmbRange;
	}*/
	function descritivo($texto){
		$texto = str_replace("\n",";\n",$texto);
		$arrayDesc = explode("\n",$texto);
		return new CTag('div', 'yes', $arrayDesc, 'text');
	}
/*	function exibeConteudo ($condicao,$conteudo) {
		if ($condicao) { return $conteudo;} 
		else { return array (""); }
	}
	function preparaQuery ($p_query) {
		$result	= DBselect($p_query);
		if (!$result) { 
			die("Invalid query."//.mysql_error()
			); 
			return 0;
		} else { return $result; } 
	}
	function valorCampo ($p_query, $p_campo) {
		$retorno = "";
		$result = preparaQuery($p_query);
		while($row = DBfetch($result)){
			$retorno = $row[$p_campo];
		}
		return $retorno;
	} */
?>
<?php
//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields=array(
		'groupid'=>		array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'hostid'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'formato'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null), // Identificador do formato
		'report_timesince'=>	array(T_ZBX_STR, O_OPT,  null,	null,		'isset({filter})'),
		'report_timetill'=>		array(T_ZBX_STR, O_OPT,  null,	null,		'isset({filter})')
/* actions */
		,'filter'=>			array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
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
	
	// Verificação de segurança =========================================

	if(get_request('groupid', 0) > 0){
		$groupids = available_groups($_REQUEST['groupid'], 1);
		$params = array(
			'preservekeys' => 1,
			'output' => API_OUTPUT_EXTEND
		);
		if(empty($groupids)) access_deny();
	}

	if(get_request('hostid', 0) > 0){
		$hostids = available_hosts($_REQUEST['hostid'], 1);
		if(empty($hostids)) access_deny();
	}

	$hostprof_wdgt 		= new CWidget();
// Formulario de Filtro =========================================================

// Combos de filtro =========================================================
	$cmbGroups 		= $pageFilter->getGroupsCB(true);
	$cmbHosts 		= $pageFilter->getHostsCB(true);
	// Combo com os formatos de exibição
	$cmbFormato		= new CComboBox('formato', $formato, 'javascript: submit();');
	$cmbFormato->additem('', 'Selecione...');
	$cmbFormato->additem('html', 'HTML');
	$cmbFormato->additem('csv', 'CSV');

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
		array(bold(_('Formato')), ': ', $cmbFormato),
		array()
	));
	
	$filter_form = new CForm();
	$filter_form->setMethod('get');
	$filter_form->setAttribute('name','zbx_filter');
	$filter_form->setAttribute('id','zbx_filter');

	$reset = new CButton('reset',_('Reset'));
	$reset->onClick("javascript: clearAllForm('zbx_filter');");
	$filter = new CButton('filter',_ze2('Update Filter'));
	$filter->onClick("javascript: submit();");

	$footer_col = new CCol(array($filter, SPACE, $reset), 'center');
	$footer_col->setColSpan(4);

	$filter_table->addRow($footer_col);

	$filter_form->addItem($filter_table);
	$hostprof_wdgt->addFlicker($filter_form, true);

// FIM Formulario de Filtro =========================================================
	$completo = (get_request('formato','') != '') && ($groupid > 1 || $hostid > 1);
		
	if ($completo) {
		// Localizar todos os hosts que o usuário selecionou
		$baseQuery = "SELECT hos.name as host_name, it.hostid, it.name as item_name, it.key_ as item_key, it.delay, it.history, it.trends, it.status , 86400 / it.delay * it.history AS history_costs, it.trends * 24 AS trends_costs
FROM items it
INNER JOIN hosts hos
   ON hos.hostid = it.hostid
INNER JOIN hosts_groups hgr
   ON hgr.hostid = it.hostid
". ( $groupid > 0 ? " AND hgr.groupid = ".$groupid: "")
." WHERE it.flags <> 2
" . ($hostid > 0 ? " AND it.hostid = ".$hostid : "")
. "\n order by host_name, item_key " ;
	
		// Construir resumo de historico armazenado por host
		// Construir resumo de trends armazenado por host
		$result			= DBselect($baseQuery);
		$report			= Array();
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
			$report[$cont][9] = convert_units($report[$cont][10],'B');
			$cont++;
		}
		$table = new CTableInfo();
		switch ($formato) {
			case 'csv';
				$table->setHeader(array("Dados"));	
				break;
			case 'html';
				$table->setHeader(array(_("Host"),_("Item"),_("Key"),_("Delay")
                                    ,_("History"),_("Trends"),_("Status")
                                    ,_ze2("History Costs"),_ze2("Trends Costs")
                                    ,_ze2("Storage Costs")
                                ));	
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
			$table->addRow(array($descricao,'Total',$historyTotal.' linhas',$trendTotal.' linhas',convert_units($storageTotal,'B')));
		}
		$numrows = new CDiv();
		$numrows->setAttribute('name', 'numrows');
		$hostprof_wdgt->addHeader($numrows);
	
		$paging = getPagingLine($report);
	
		$hostprof_wdgt->addItem($table);
	} else {
		$hostprof_wdgt->addItem(_ze2('Enter the parameters for research!'));
	}
	$hostprof_wdgt->show();
?>
<?php 
	include_once('include/page_footer.php');
?>
