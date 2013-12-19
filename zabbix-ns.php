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
	require_once('include/zbxe_visual_imp.php');
	$page['title']	= _zeT('Not Supported Items');
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
	);

	check_fields($fields);

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
        $groupids = checkAccessGroup ('groupid');
        $hostids = checkAccessHost('hostid');
// --------------------------- Fim Filtro ---------------------------------------------------------------------
?>
<?php
    $options = array();
    $options['templated_hosts'] = 1;
    $hosts = API::Host()->get($options);
    $filtroSegHosts = " hos.status <> 1 AND " . dbConditionInt('hos.hostid',zbx_objectValues($hosts, 'hostid'));

    $rep2_wdgt = new CWidget();

    $rep2_wdgt->addPageHeader(_zeT('Not Supported Items Report'));

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
        $sql =  'select hos.host, ite.name, ite.itemid, hos.hostid, ite.error, ite.flags ' .
                '  from items ite ' .
                '  inner join hosts hos ' .
                '     on (hos.hostid = ite.hostid) and ' . $filtroSegHosts . 
                ' where ite.state = 1 ' .
                (count($hostids) > 0 ? '   and ' .dbConditionInt('hos.hostid',$hostids ) : '').
                ' order by hos.host, ite.name'
                ;
//        var_dump($sql);
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
