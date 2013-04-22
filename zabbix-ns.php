<?php
/*

** Objetivo: Auditoria de Conformidade em ambientes distribuidos
** Adail Horst - http://spinola.net.br/blog

** ZABBIX
** Copyright (C) 2000-2009 SIA Zabbix
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
** This report is for locate not suported items ========================
** Adail Horst - the.spaww@gmail.com ===================================
**
**/
?>
<?php

	require_once 'include/config.inc.php';
	require_once 'include/hosts.inc.php';
	require_once 'include/reports.inc.php';
	require_once('zabbix-translate.php');
	$page['title']	= _ze('Zabbix-NS-Title');
	$page['file']	= 'zabbix-ns.php';
	$page['hist_arg'] = array('groupid', 'hostid','graphid');
	$page['scripts'] = array('class.calendar.js', 'scriptaculous.js?load=effects');
	$page['type'] = detect_page_type(PAGE_TYPE_HTML);

include_once 'include/page_header.php';

?>
<?php
//		VAR				TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
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
/*		'groupid'		=>	array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'hostid'		=>	array(T_ZBX_INT, O_OPT,	 P_SYS,	DB_ID,	null),
		'filter_groupid'=>	array(T_ZBX_INT, O_OPT,	P_SYS,	DB_ID,			NULL),
		'filter_hostid'	=>	array(T_ZBX_INT, O_OPT,	P_SYS,	DB_ID,			NULL),
		'tpl_triggerid'	=>	array(T_ZBX_INT, O_OPT,	P_SYS,	DB_ID,			NULL),
		'triggerid'		=>	array(T_ZBX_INT, O_OPT,	P_SYS|P_NZERO,	DB_ID,		NULL),
// filter
        'select'		=>  array(T_ZBX_STR, O_OPT, NULL,   NULL,           NULL),
        'filter_rst'	=>  array(T_ZBX_INT, O_OPT, P_SYS,  IN(array(0,1)), NULL),
        'filter_set'	=> 	array(T_ZBX_STR, O_OPT, P_SYS,  null,   NULL),

// filter
		'filter_rst'	=>	array(T_ZBX_INT, O_OPT,	P_SYS,	IN(array(0,1)),	NULL),
		'filter_set'	=>	array(T_ZBX_STR, O_OPT,	P_SYS,	null,	NULL),
		'filter_timesince'=>array(T_ZBX_INT, O_OPT,	P_UNSET_EMPTY,	null,	NULL),
		'filter_timetill'=>	array(T_ZBX_INT, O_OPT,	P_UNSET_EMPTY,	null,	NULL),

//ajax
		'favobj'=>		array(T_ZBX_STR, O_OPT, P_ACT,	NULL,			NULL),
		'favid'=>		array(T_ZBX_STR, O_OPT, P_ACT,	NOT_EMPTY,		'isset({favobj})'),
		'state'=>		array(T_ZBX_INT, O_OPT, P_ACT,	NOT_EMPTY,		'isset({favobj})'),
*/	);

	check_fields($fields);

/* AJAX */
//--------
/* FILTER */

// --------------------------- Filtro ---------------------------------------------------------------------
	$options = array(
		'groups' => array('monitored_hosts' => 1),
		'hosts' => array('monitored_hosts' => 1),
		'groupid' => get_request('groupid', null),
		'hostid' => get_request('hostid', null)
	);
	$hostid = $_REQUEST['hostid']	= get_request('hostid', 0);
	$groupid = $_REQUEST['groupid']	= get_request('groupid', 0);
	$pageFilter = new CPageFilter($options);

	$filter_table = new CTable('', 'filter_config');
	$filter_table->setAttribute('border',0);
	$filter_table->setAttribute('width','100%');
	$cmbGroups 		= $pageFilter->getGroupsCB(true);
	$cmbHosts 		= $pageFilter->getHostsCB(true);

	$filter_table->addRow(array(
		array(bold(_('Group')), ': ', $cmbGroups),
		array(bold(_('Host')), ': ', $cmbHosts),
		array()
	));
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

// --------------------------- Fim Filtro ---------------------------------------------------------------------
?>
<?php
	$rep2_wdgt = new CWidget();

	$rep2_wdgt->addPageHeader(_ze('Zabbix-NS-TitleBig'));

		$filter_form = new CForm();
		$filter_form->setMethod('get');
		$filter_form->setAttribute('name','zbx_filter');
		$filter_form->setAttribute('id','zbx_filter');

		$filter = new CButton('filter',_("Filter"));
		$filter->onClick("javascript: submit();");
		$reset = new CButton('reset',_('Reset'));
		$reset->onClick("javascript: clearAllForm('zbx_filter');");

		$footer_col = new CCol(array($filter, SPACE, $reset), 'center');
		$footer_col->setColSpan(4);
	
		$filter_table->addRow($footer_col);

		$filter_form->addItem($filter_table);

//		$filterForm = get_report2_filter($config, $PAGE_GROUPS, $PAGE_HOSTS);
		$rep2_wdgt->addFlicker($filter_form, true);

	if(isset($hostid)){
		$sql_from = '';
		$sql_where = '';

		if(0 == $config){
			if($_REQUEST['groupid'] > 0){
				$sql_from .= ',hosts_groups hg ';
				$sql_where.= ' AND hg.hostid=h.hostid AND hg.groupid='.$_REQUEST['groupid'];
			}

			if($_REQUEST['hostid'] > 0){
				$sql_where.= ' AND h.hostid='.$hostid;
			}
		}
		else{
			if($hostid > 0){
				$sql_from.=',hosts_templates ht ';
				$sql_where.=' AND ht.hostid=h.hostid AND ht.templateid='.$hostid;
			}
		}
		$sql =
			'select hos.host, ite.name, ite.itemid, hos.hostid, ite.error, ite.flags ' .
			'  from items ite ' .
			'  inner join hosts hos ' .
			'     on (hos.hostid = ite.hostid) ' .
			' where ite.status = 3 ' .
			(isset($hostids) ? '   and ' .DBcondition('hos.hostid',$hostids ) : '').
			' order by hos.host, ite.name'
			;
		$result = DBselect($sql);

		$table = new CTableInfo();
		$table->setHeader(
			array(is_show_all_nodes() ? _('Node') : null,
			(($hostid == 0) || (1 == $config))? _('Host') : NULL,
			_('Name'),
			_('Error'),
		));

		while($row = DBfetch($result)){
			$table->addRow(array(
				get_node_name_by_elid($row['hostid']),
				(($hostid == 0) || (1 == $config)) ? $row['host'] : NULL,
				($row['flags'] == 4 ? $row['name']
				: new CLink($row['name'], 'items.php?form=update&itemid='.$row['itemid']))
				, $row['error']
			));
		}

		$rep2_wdgt->addItem($table);
	}
	$rep2_wdgt->show();

include_once 'include/page_footer.php';
?>
