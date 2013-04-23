<?php
/* Utilizei como base inicial para o desenvolvimento o "Zabbix-IS.php" e alguns outros arquivos do Frontend do Zabbix
** Objetivo: Localizar em grupo de hosts determinado item atendendo a um parâmetro específico relativo ao ultimo valor
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
	function quotestr($p_texto) { // Função para colocar aspas com mais segurança
		return "'".mysql_real_escape_string($p_texto)."'";
	}
?>
<?php
	require_once('include/config.inc.php');
	require_once('include/hosts.inc.php');
	require_once('include/forms.inc.php');
	require_once('zabbix-translate.php');
	$baseName = 'Zabbix-PGA-';
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$titulo 			= _ze2('Zabbix-IS - Ranking of Items');;//'Zabbix-IS - Capacidade e Tendência';
	$page['title'] 		= $titulo;
	$page['file'] 		= 'zabbix-is.php';
	$page['hist_arg'] 	= array('hostid','groupid','graphid');
//	$page['scripts'] 	= array('class.calendar.js', 'gtlc.js');

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
	function newComboAPI ($Data, $keyField, $showField, $selected_value, $name, $reloadScript = 'javascript: submit();', $fristBlank = true) {
		$cmbRange 		= new CComboBox($name, $selected_value, $reloadScript);
		if ($fristBlank == true) {
			$cmbRange->additem("0", "");
		}

		for ($i = 0; $i < count($Data); $i++) {
//		while($row_extra = DBfetch($result)){
			$cmbRange->additem($Data[$i][$keyField], $Data[$i][$showField]);
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
/* ------------ Lembrar que parametros de formulários tem que estar aqui -------------- */
//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields=array(
		'groupid'=>		array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'applicationid'=>	array(T_ZBX_INT, O_OPT,  P_SYS,	DB_ID,	null),
		'reportType'=>		array(T_ZBX_STR, O_OPT,  P_SYS,	DB_ID,	null), // Identificador do formato
/* actions */
		'filter'=>			array(T_ZBX_STR, O_OPT, P_SYS|P_ACT,	null,	null)
	);

	check_fields($fields);
?>
<?php

	$options = array(
		'groups' => array('monitored_hosts' => 1),
		'groupids' => get_request('groupid', null),
		'applicationids' => get_request('groupid', null)
	);
	$pageFilter = new CPageFilter($options);
	
	$groupid 		= $_REQUEST['groupid'] 			= get_request('groupid', 0);
	$searchgroupid	= $_REQUEST['searchgroupid']	= get_request('searchgroupid', 0);
	$applicationid 	= $_REQUEST['applicationid']	= get_request('applicationid', 0);
	$reportType 	= $_REQUEST['reportType']		= get_request('reportType', 'html');
	
	// Verificação de segurança =========================================
	if(get_request('groupid', 0) > 0){
		$groupids = available_groups($_REQUEST['groupid'], 1);
		$params = array(
			'preservekeys' => 1,
			'output' => API_OUTPUT_EXTEND
		);
		if(empty($groupids)) access_deny();
	}
	$hostprof_wdgt 		= new CWidget();
// Formulario de Filtro =========================================================

// Combos de filtro =========================================================
	$groupsArray['groups'] = API::HostGroup()->get(array(
		'output' => API_OUTPUT_EXTEND
	));
	order_result($groupsArray['groups'], 'name');

	$cmbGroupSearch = newComboAPI ($groupsArray['groups'], 'groupid', 'name', $searchgroupid, 'searchgroupid',true,'');
	$cmbGroups = newComboAPI ($groupsArray['groups'], 'groupid', 'name', $groupid, 'groupid');
	$appArray = API::Application()->get(array(
		'groupids' => $groupid,
		'sortfield' => 'name',
		'output' => API_OUTPUT_EXTEND
	));
	$cmbApplications = newComboAPI($appArray,'applicationid','name',$applicationid,'applicationid');

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

	// Combo com os formatos de exibição
	$cmbReportType		= new CComboBox('reportType', $reportType, 'javascript: submit();');
	$cmbReportType->additem('html', 'HTML');
	$cmbReportType->additem('csv', 'CSV');

	$tituloGrafico = valorCampo ('select name as id from hosts where hostid = '.$hostid,'id') . " - " . $descItem;
// FIM Combos de filtro =========================================================
	$hostprof_wdgt->addHeader($titulo, array());
	$filter_table = new CTable('', 'filter_config');
 
 	$filter_table->setAttribute('border',0);
	$filter_table->setAttribute('width','100%');

// Idenfifica se o padrão foi informado para pesquisar ==========================================
	$completo = $keyStandard !== '';

/*----------- Implementa o Filtro ---------------*/
 		$filter_table->addItem(new CDiv(_('Wizard'), 'thin_header')); // Search standard
 		$filter_table->addRow(array(
			array(bold(_('Group')), ': ', $cmbGroups),
			array(bold(_('Host')), ': ', $cmbHosts),
			exibeConteudo ($hostid > 0,array(bold(_('Application')), ': ', $cmbApplications)),
			exibeConteudo ($applicationid > 0,array(bold(_('Item')), ': ', $cmbItems)),
			array()
		));
		$filter_table2 = new CTable('', 'filter_config'); // Place to run the search	 
		$filter_table2->setAttribute('border',0);
		$filter_table2->setAttribute('width','100%');
 		$filter_table2->addItem(new CDiv(_('Search definition'), 'thin_header'));
	
		if (($itemid > 0) and ($keyStandard == "")) {
			$keyStandard = valorCampo ('select key_ as id from items where itemid = '.$itemid,'id');
		}
	
		$filter_table2->addRow(array(
			array(bold(_('Search group')), ': ', $cmbGroupSearch),
			array(bold(_('Item key')),' ('._('like').'): ',new CTextBox('keyStandard', $keyStandard, 60)),
			array()
		));
		
		$filter_form = new CForm();
		$filter_form->setMethod('get');
		$filter_form->setAttribute('name','zbx_filter');
		$filter_form->setAttribute('id','zbx_filter');

		$reset = new CButton('reset',_('Reset'));
		$reset->onClick("javascript: clearAllForm('zbx_filter');");
		$grafico = new CButton('grafico',_ze2('Zabbix-IS-Chart'));
		// Habilita o botão de geração de gráfico quando tem host e item selecionado =============================================
		if (($hostid < 1) and ($itemid < 1)) {
			$grafico->setAttribute('disabled', '');
		}
		$grafico->onClick("javascript: fnGrafico();");
		$filter = new CButton('filter',_ze2("Zabbix-IS-UpdateFilter"));
		$filter->onClick("javascript: submit();");

		$footer_col = new CCol(array($filter, SPACE, $reset, SPACE, $grafico), 'center');
		$footer_col->setColSpan(4);
	
		$filter_table2->addRow($footer_col);

		$filter_form->addItem($filter_table);
		$filter_form->addItem($filter_table2);
		$hostprof_wdgt->addFlicker($filter_form, true);
// FIM Formulario de Filtro =========================================================
	if ($completo) {
		$result			= DBselect("select value_type from items it where it.itemid = ".$_REQUEST['itemid']);
		$cmbItems->additem("0", "");
		while($row_extra = DBfetch($result)){ $tipo = $row_extra['value_type']; }
		$tabela_log = ($tipo == 0 ? "trends" : "trends_uint");
		$casasDecimais = ($tipo == 0 ? 4 : 0);
// =================== Query Base (MySQL e não o lixo do postgres =====================================
$query = "
select ".($DB['TYPE'] == ZBX_DB_POSTGRESQL ? " DISTINCT ON(momento) ": "" )."it.units, it.description, ano, mes, dia, momento, AVG(valor) as valor
  from items it 
 inner join 
(select 
hu.itemid,
DATE_FORMAT(FROM_UNIXTIME(hu.clock), '%Y') as ano, DATE_FORMAT(FROM_UNIXTIME(hu.clock), '%m') as mes, DATE_FORMAT(FROM_UNIXTIME(hu.clock), '%d') as dia, 
DATE_FORMAT(FROM_UNIXTIME(hu.clock), '".$intervalMask[$timeShiftSource]."') as momento, "
. $sourceAgregator[get_request('agregation',0)]." as valor
from ".$tabela_log." hu 
where hu.clock between ".$report_timesince." and  ".$report_timetill."
) a 
on a.itemid = it.itemid 
where it.itemid = ".$itemid."
group by ".($DB['TYPE'] == ZBX_DB_POSTGRESQL ? "units, ano, mes, dia, description, ": "" )." momento
order by momento
";
	if ($DB['TYPE'] == ZBX_DB_POSTGRESQL) {
		$query = str_replace('DATE_FORMAT','to_char',$query);
		$query = str_replace('FROM_UNIXTIME','to_timestamp',$query);
		$query = str_replace('%Y%m%d','YYYYMMDD',$query);
		$query = str_replace('%m','MM',$query);
		$query = str_replace('%d','DD',$query);
		$query = str_replace('%Y','YYYY',$query);
		$query = str_replace('%U','WW',$query);
	}
		$result			= DBselect($query);
		$report			= Array();
		$cont 			= 0;
function week2date($year, $week, $weekday=7) {
	global $timeShiftSource;
	if ($timeShiftSource == 2) {
$time = mktime(0, 0, 0, 1, (4 + ($week-1)*7), $year);
$this_weekday = intval(date('N', $time));
$tmp = $weekday - $this_weekday;
return " - " . date('d/m/y',mktime(0, 0, 0, 1, (4 + ($week-1) * 7 + ($tmp)), $year));
	} else {
		return "";
	}
}
		while($row = DBfetch($result)){
			if ($cont == 0) {
				$maximo = $primeiro = $ultimo = $minimo = floatval($row['valor']);
				$unidade = $row['units'];
			}
			$report[$cont]['momento'] = $row['momento'] . week2date($row['ano'],$row['momento']);
			$report[$cont]['valor'] = round(floatval($row['valor']),$casasDecimais);
			$report[$cont]['tipo'] = _ze2('Zabbix-IS-HistoryData');
			$dia = $row['dia'];
			$mes = $row['mes'];
			$ano = $row['ano'];
			$minimo = floatval(($minimo >= $report[$cont]['valor'] ? $report[$cont]['valor'] : $minimo ));
			$maximo = floatval(($maximo <= $report[$cont]['valor'] ? $report[$cont]['valor'] : $maximo ));
			$cont++;
		}
		if ($cont > 0 ) { 
			$ultimo = $report[($cont-1)]['valor'];
			$tendencia = (($maximo - $minimo)/round($cont*1))*($primeiro < $ultimo ? 1 : -1) / ($intervalFactor[$timeShiftSource]);
	
			$dataAtual = mktime(0,0,0,$mes,$dia,$ano);
			if ($timeShiftProjection > $timeShiftSource) {
				$dataAtual = strtotime($intervalFactor2[$timeShiftSource], $dataAtual);
			}
			if ($timeShiftProjection >= $timeShiftSource || $timeShiftSource == 2 ) {
				$dataAtual = strtotime($intervalFactor2[$timeShiftProjection], $dataAtual);
			}
			// Aplicando o fator de tendência tendência --------------------------
			for ($i = 0; $i < intval(get_request('num_projection',0)); $i++) {		
				$format = "d/m/Y";
				$proximoDia   = date($intervalMask2[$timeShiftProjection], $dataAtual);
				$dataAtual = strtotime($intervalFactor2[$timeShiftProjection], $dataAtual);
				$report[$cont]['momento'] = $proximoDia;
				$report[$cont]['valor'] = round(floatval($ultimo)+$tendencia*($intervalFactor[$timeShiftProjection]),$casasDecimais);
				
				$ultimo = $report[$cont]['valor'];
				$report[$cont]['tipo'] = _ze2('Zabbix-IS-Trend');
				$cont++;
			}
		}
		$table = new CTableInfo();
		switch ($reportType) {
			case 'csv';
				$table->setHeader(array(_ze2("Zabbix-IS-Data")));	
				break;
			case 'html';
				$table->setHeader(array(_ze2('Zabbix-IS-Instant'),_ze2('Zabbix-IS-Value'),_ze2('Zabbix-IS-Type')));	
				break;			
		}
		$points = "";
		$descUnidade="";
		for ($i = 0; $i < $cont; $i++) {		
			switch ($reportType) {
				case 'csv';
					$table->addRow(array(quotestr($report[$i]['momento']).";".quotestr($report[$i]['valor']).";".quotestr($report[$i]['tipo']).";"));
					break;
				case 'html';
					$momento = new CCol($report[$i]['momento'],1);
					$valor = convert_units($report[$i]['valor'],$unidade);
					$valor = new CCol($valor,1);
					$tipo = new CCol($report[$i]['tipo'],1);
					$table->addRow(array($momento,$valor,$tipo));
					break;			
			}
			// Tratando o valor para o gráfico caso seja formatação de Byte
			if ($unidade == 'B') {
				$maximo = $maximo-1;
				if ($maximo < 1024) {
					$fator = 1;
					$descUnidade = 'B';
				} else if ($maximo < 1048576) {
					$fator = 1024;
					$descUnidade = 'KB';
				} else if ($maximo < 1073741824) {
					$fator = 1048576;
					$descUnidade = 'MB';
				} else if ($maximo < 1099511627776) {
					$fator = 1073741824;
					$descUnidade = 'GB';
				} else {
					$fator = 1099511627776;
					$descUnidade = 'TB';
				}
				$descUnidade = " em " . $descUnidade;
			} else { $fator = 1; $descUnidade = ""; }
			$valor = round($report[$i]['valor'] / $fator,2);
			$points .= "'".$report[$i]['momento']."',".$valor."[;]";
		}
		$tituloGrafico .= $descUnidade;
		$script = "function fnGrafico () { ".
		"window.open(\"Zabbix-IS-chart-builder.php?p_title=".
		$tituloGrafico."&p_points=".$points."'\",\"graficoZabbixCat\",\"width=720,height=350,top=130,left=150,scrollbars=yes,resizable=no\");"
		.'};';
		insert_js ($script);
//		var_dump($urlGrafico);
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