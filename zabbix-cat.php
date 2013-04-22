<?php
/* Utilizei como base inicial para o desenvolvimento o "hostprofiles.php" e alguns outros arquivos do Frontend do Zabbix
** Objetivo: Relatório de tendência de item
** Adail Horst - http://spinola.net.br/blog

#2012 - Dica de Elton Ferreira - Adicionar exportação CSV

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
	function quotestr($p_texto) { // Função para colocar aspas com mais segurança
		return "'".mysql_real_escape_string($p_texto)."'";
	}
?>
<?php
	require_once('include/config.inc.php');
	require_once('include/hosts.inc.php');
	require_once('include/forms.inc.php');
	require_once('zabbix-translate.php');
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$titulo 			= _ze('Zabbix-CAT-Title');;//'Zabbix-CAT - Capacidade e Tendência';
	$page['title'] 		= $titulo;
	$page['file'] 		= 'zabbix-cat.php';
	$page['hist_arg'] 	= array('hostid','groupid','graphid');
	$page['scripts'] 	= array('class.calendar.js', 'gtlc.js');

	include_once('include/page_header.php');
?>
<?php
	function newComboFilter ($query, $value, $name) {
		$cmbRange 		= new CComboBox($name, $value, 'javascript: submit();');
		$result			= DBselect($query);
		$cmbRange->additem("0", "");
		while($row_extra = DBfetch($result)){
			$cmbRange->additem($row_extra['id'], $row_extra['description']);
		}
		return $cmbRange;
	}
	function descritivo($texto){
		$texto = str_replace("\n",";\n",$texto);
		$arrayDesc = explode("\n",$texto);
		return new CTag('div', 'yes', $arrayDesc, 'text');
	}
	function exibeConteudo ($condicao,$conteudo) {
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
	}
?>
<?php
//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields=array(
		'groupid'=>		array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'hostid'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'applicationid'=>	array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'itemid'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'timeshiftsource'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'timeshiftprojection'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'agregation'=>		array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'num_projection'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null),
		'formato'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null), // Identificador do formato

		'report_timesince'=>	array(T_ZBX_STR, O_OPT,  null,	null,		'isset({filter})'),
		'report_timetill'=>		array(T_ZBX_STR, O_OPT,  null,	null,		'isset({filter})')
/* actions */
		,'filter'=>			array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
	);

	check_fields($fields);
	insert_js_function('add_period');
	insert_js_function('update_period');
?>
<?php

	// Atribuição de variaveis ========================================
	$options = array(
		'groups' => array('monitored_hosts' => 1),
		'hosts' => array('monitored_hosts' => 1),
		'items' => array('templated' => 0),

		'groupid' => get_request('groupid', null),
		'hostid' => get_request('hostid', null),
		'itemid' => get_request('itemid', null),
		'formato' => get_request('itemid', null)
	);
	$pageFilter = new CPageFilter($options);
	$startDateDefault 				= 86400*30;
	$_REQUEST['groupid'] 			= get_request('groupid', 0);
	$hostid = $_REQUEST['hostid']	= get_request('hostid', 0);
	$applicationid = $_REQUEST['applicationid']	= get_request('applicationid', 0);
	$itemid = $_REQUEST['itemid']	= get_request('itemid', 0);
	$formato = $_REQUEST['formato']	= get_request('formato', 'html');
	$_REQUEST['report_timesince'] 	= zbxDateToTime(get_request('report_timesince',date('YmdHis', time()-$startDateDefault)));
	$_REQUEST['report_timetill'] 	= zbxDateToTime(get_request('report_timetill',date('YmdHis')));
	
	$report_timesince 	= get_request('report_timesince',time()-$startDateDefault);
	$report_timetill 	= get_request('report_timetill',time());
	
	$timeShiftSource 	= get_request('timeshiftsource',0);
	$timeShiftProjection	= get_request('timeshiftprojection',0);
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
	$cmbApplications = newComboFilter("select a.applicationid as id, a.name as description from applications a inner join hosts h on h.hostid = a.hostid where a.hostid = ". $hostid . " order by 2 ",$applicationid,'applicationid');

	// Combo com os formatos de exibição
	$cmbFormato		= new CComboBox('formato', $formato, 'javascript: submit();');
	$cmbFormato->additem('html', 'HTML');
	$cmbFormato->additem('csv', 'CSV');

	$cmbItems 		= new CComboBox('itemid', $itemid, 'javascript: submit();');
	$query 			=  "select it.itemid as id, it.name, it.key_ from items it inner join items_applications ia on ia.itemid = it.itemid and applicationid = ". $applicationid . " and it.status < 1 and it.flags <> 2 " . " order by 2 ";
	$result			= DBselect($query);
	$cmbItems->additem("0", "");
	$descItem = "";
	while($row_extra = DBfetch($result)){
		$descricao = $row_extra['name'];
		if (strpos($descricao,"$") !== false) {
			$tmp = explode("[",$row_extra['key_']);
			$tmp = explode(",",str_replace("]","",$tmp[1]));
			for ($i = 0; $i < count($tmp); $i++) {
				$descricao = str_replace("$".($i+1),$tmp[$i],$descricao);
			}
		} 
		$descItem = ($row_extra['id'] == $itemid ? $descricao : $descItem);
		$cmbItems->additem($row_extra['id'], $descricao);
	}
	$tituloGrafico = valorCampo ('select name as id from hosts where hostid = '.$hostid,'id') . " - " . $descItem;
// FIM Combos de filtro =========================================================
	$hostprof_wdgt->addHeader($titulo, array());
	$filter_table = new CTable('', 'filter_config');
	$filter_table->setAttribute('border',0);
	$filter_table->setAttribute('width','100%');

	$cmbTimeSource		= new CComboBox('timeshiftsource', $timeShiftSource, 'javascript: submit();');
	$cmbTimeProjection	= new CComboBox('timeshiftprojection', $timeShiftProjection, 'javascript: submit();');
	
	$completo = get_request('itemid',0) > 0;
	$cmbAgregation	= new CComboBox('agregation', get_request('agregation',0), 'javascript: submit();');
	if ($completo) {
		
		$numrows = new CDiv();
		$numrows->setAttribute('name', 'numrows');
		$hostprof_wdgt->addHeader($numrows);
	
		$paging = getPagingLine($report);
	
		$hostprof_wdgt->addItem($table);
	}
	$hostprof_wdgt->show();
?>
<?php 
	include_once('include/page_footer.php');
?>