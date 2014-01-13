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
?>
<?php
	require_once('include/config.inc.php');
	require_once('include/hosts.inc.php');
	require_once('include/forms.inc.php');
	require_once('include/zbxe_visual_imp.php');
	/* Configuração basica do arquivo para o módulo de segurança do Zabbix	*/
	$titulo 		= _zeT('Capacity and Trends');;//'Zabbix-CAT - Capacidade e Tendência';
	$page['title'] 		= $titulo;
	$page['file'] 		= 'zbxe-cat.php';
	$page['hist_arg'] 	= array('hostid','groupid','graphid');
	$page['scripts'] 	= array('class.calendar.js', 'gtlc.js');

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

        $groupids = checkAccessGroup ('groupid');
        $hostids = checkAccessHost('hostid');
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
	$query 			=  "select it.itemid as id, it.name, it.key_ "
        . " from items it inner join items_applications ia on ia.itemid = it.itemid and applicationid = "
        . $applicationid . " and it.status < 1 and it.flags <> 2 " . " order by 2 ";
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
		$intervalDesc 		= array ('',_zeT('Day'),_zeT('Week'),_zeT('Month'),_zeT('Year'));
		$intervalFactor 	= array (0,1,7,30,365);
		$intervalFactor2 	= array (0,'+1 days','+1 week','+1 months','+1 years');
		$sourceAgregator 	= array ('hu.value_max','hu.value_min','hu.value_avg');
//		$sourceAgregator 	= array ('AVG(hu.value_max)','AVG(hu.value_min)','AVG(hu.value_avg)');
		$intervalMask 		= array ('','%d/%m/%Y','%U','%m/%Y','%Y');
		$intervalMask2 		= array ('','d/m/Y','W (d/m/Y)','m/Y','Y');
		$intervalMaskSort 	= array ('','%Y%m%d','%Y%U','%Y%m','%Y');
		for ($i = 0; $i < count($intervalDesc); $i++) {
			$cmbTimeSource->additem($i, $intervalDesc[$i]);
			$cmbTimeProjection->additem($i, $intervalDesc[$i]);	
		}
		$completo = $timeShiftSource > 0 && $timeShiftProjection > 0;
		$cmbAgregation->additem(0, _zeT('Max'));
		$cmbAgregation->additem(1, _zeT('Min'));
		$cmbAgregation->additem(2, _zeT('Avg'));
	}

/*----------- Filtro por período ---------------*/
		$reporttimetab 	= new CTable(null,'calendar');
		$clndr_icon 	= new CImg('images/general/bar/cal.gif','calendar', 16, 12, 'pointer');
		$clndr_icon->addAction('onclick','javascript: var pos = getPosition(this); '.
			'pos.top+=10; pos.left+=16; '.
			"CLNDR['avail_report_since'].clndr.clndrshow(pos.top,pos.left);");
		$reporttimetab->addRow(array(
			_('From'),
			array(new CNumericBox('report_since_day',(($report_timesince>0)?date('d',$report_timesince):''),2),
			'/', new CNumericBox('report_since_month',(($report_timesince>0)?date('m',$report_timesince):''),2),
			'/', new CNumericBox('report_since_year',(($report_timesince>0)?date('Y',$report_timesince):''),4),
			SPACE, new CNumericBox('report_since_hour',(($report_timesince>0)?date('H',$report_timesince):''),2),
			':', new CNumericBox('report_since_minute',(($report_timesince>0)?date('i',$report_timesince):''),2)
			), $clndr_icon
		));
		$clndr_icon->addAction('onclick','javascript: var pos = getPosition(this); '.
			'pos.top+=10; pos.left+=16; '.
			"CLNDR['avail_report_till'].clndr.clndrshow(pos.top,pos.left);");
		$reporttimetab->addRow(array(
			_('Till'), 
			array(new CNumericBox('report_till_day',(($report_timetill>0)?date('d',$report_timetill):''),2),
			'/', new CNumericBox('report_till_month',(($report_timetill>0)?date('m',$report_timetill):''),2),
			'/', new CNumericBox('report_till_year',(($report_timetill>0)?date('Y',$report_timetill):''),4),
			SPACE, new CNumericBox('report_till_hour',(($report_timetill>0)?date('H',$report_timetill):''),2),
			':', new CNumericBox('report_till_minute',(($report_timetill>0)?date('i',$report_timetill):''),2)),
			$clndr_icon
		));
		zbx_add_post_js('create_calendar(null,'.
			'["report_since_day","report_since_month","report_since_year","report_since_hour","report_since_minute"],'.
			'"avail_report_since",'.
			'"report_timesince");');
		zbx_add_post_js('create_calendar(null,'.
			'["report_till_day","report_till_month","report_till_year","report_till_hour","report_till_minute"],'.
			'"avail_report_till",'.
			'"report_timetill");'
		);

		$reporttimetab2 = new CTable(null,'calendar');
		
		$reporttimetab2->addRow(array(array(bold(_zeT('Analysis')), ': '), array($cmbTimeSource,$cmbAgregation)));
		$reporttimetab2->addRow(array(array(bold(_zeT("Projection")), ': '), array($cmbTimeProjection,array(bold(_zeT("Amount")), ': '),new CTextBox('num_projection', get_request('num_projection',7), 2))));
		$reporttimetab2->addRow(array(array(bold(_zeT('Formatting')), ': '), array($cmbFormato)));
/*----------- Implementa o Filtro por período ---------------*/
		$filter_table->addRow(array(
			array(bold(_('Group')), ': ', $cmbGroups),
			array(bold(_('Host')), ': ', $cmbHosts),
			exibeConteudo ($hostid > 0,array(bold(_('Application')), ': ', $cmbApplications)),
			array()
		));
		$filter_table->addRow(array(
			exibeConteudo ($applicationid > 0,array(bold(_('Item')), ': ', $cmbItems)),
			exibeConteudo ($itemid > 0,$reporttimetab),
			exibeConteudo ($itemid > 0,$reporttimetab2)			
		));
		
		$filter_form = new CForm();
		$filter_form->setMethod('get');
		$filter_form->setAttribute('name','zbx_filter');
		$filter_form->setAttribute('id','zbx_filter');

		$reset = new CButton('reset',_('Reset'));
		$reset->onClick("javascript: clearAllForm('zbx_filter');");
		$grafico = new CButton('grafico',_zeT('Chart'));
		// Habilita o botão de geração de gráfico quando tem host e item selecionado =============================================
		if (($hostid < 1) and ($itemid < 1)) {
			$grafico->setAttribute('disabled', '');
		}
		$grafico->onClick("javascript: fnGrafico();");
		$filter = new CButton('filter',_zeT("Update Filter"));
		$filter->onClick("javascript: submit();");

		$footer_col = new CCol(array($filter, SPACE, $reset, SPACE, $grafico), 'center');
		$footer_col->setColSpan(4);
	
		$filter_table->addRow($footer_col);

		$filter_form->addItem($filter_table);
		$filter_form->addVar('report_timesince', date('YmdHis', $report_timesince));
		$filter_form->addVar('report_timetill', date('YmdHis', $report_timetill));
		$hostprof_wdgt->addFlicker($filter_form, true);
function campoPadrao ($p_campo,$p_mascara) {
	
}
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
where hu.clock between ".$report_timesince." and  ".$report_timetill." AND hu.itemid = " . $itemid . "
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
//		$query = str_replace('group by','group by it.units, it.description, hu.clock, ',$query);
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
			$report[$cont]['tipo'] = _zeT('Data from history');
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
				$report[$cont]['tipo'] = _zeT('Trend');
				$cont++;
			}
		}
		$table = new CTableInfo();
		switch ($formato) {
			case 'csv';
				$table->setHeader(array(_zeT("Data")));	
				break;
			case 'html';
				$table->setHeader(array(_zeT('Instant'),_zeT('Value'),_zeT('Type')));	
				break;			
		}
		$points = "";
		$descUnidade="";
		for ($i = 0; $i < $cont; $i++) {		
			switch ($formato) {
				case 'csv';
					$table->addRow(array(quotestr($report[$i]['momento']).";".quotestr($report[$i]['valor']).";".quotestr($report[$i]['tipo']).";"));
					break;
				case 'html';
					$momento = new CCol($report[$i]['momento'],1);
                                        $valor = convert_units(array(
                                            'value' => $report[$i]['valor'],
                                            'units' => $unidade));
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
		"window.open(\"zbxe-cat-chart-builder.php?p_title=".
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