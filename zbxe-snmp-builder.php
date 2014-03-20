<?php
// Copyright (c) 2009 GiapNguyen
// Permission is hereby granted, free of charge, to any person obtaining
// a copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to
// permit persons to whom the Software is furnished to do so, subject to
// the following conditions:
// 
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
// LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
// OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//
// This is distributed under Free-BSD licence.
?>
<?php
require_once dirname(__FILE__).'/include/config.inc.php';
require_once dirname(__FILE__).'/include/js.inc.php';
require_once dirname(__FILE__).'/include/forms.inc.php';
require_once dirname(__FILE__).'/include/html.inc.php';
require_once dirname(__FILE__).'/include/items.inc.php';
require_once dirname(__FILE__).'/include/graphs.inc.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$page['title'] = _('SNMP Builder');
$page['file'] = 'zbxe-snmp-builder.php';
$page['scripts'] = array('DynTable.js', 'snmp_builder.js', 'jquery.js', 'jquery.cookie.js', 'jquery.jstree.js');
#$page['scripts'] = array('js/snmp_builder/DynTable.js', 'js/snmp_builder/snmp_builder.js', 'js/jquery/jquery.js', 'js/jquery/jquery.cookie.js', 'js/jquery/jquery.jstree.js');
$page['hist_arg'] = array();
$page['type'] = detect_page_type(PAGE_TYPE_HTML);

require_once dirname(__FILE__).'/include/page_header.php';
?>
<?php
// $left_widget_width = '40%'; // was'300px'
$left_widget_width = '300px';
// $right_widget_width = '60%'; // was '800px'
$right_widget_width = '800px';
//$mibs_dir = dirname(__FILE__).'/snmp_builder/mibs';
$mibs_dir = '/usr/share/snmp/mibs';
define('MIBS_ALL_PATH', "/usr/share/snmp/mibs");
ini_set("upload_tmp_dir", "/tmp");

//---------------------------------- CHECKS ------------------------------------

//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
	$fields = array(
		'msg'=>			array(T_ZBX_STR, O_OPT,	 null,	null ,NULL),
// ajax
        	'favobj'=>              array(T_ZBX_STR, O_OPT, P_ACT,  NULL,           NULL),
                'favref'=>              array(T_ZBX_STR, O_OPT, P_ACT,  NOT_EMPTY,      'isset({favobj})'),
                'favstate'=>    	array(T_ZBX_INT, O_OPT, P_ACT,  NOT_EMPTY,      'isset({favobj})&&("filter"=={favobj})'),

//action
		'select'=> 		array(T_ZBX_INT, O_OPT, NULL,	NULL,		NULL),
		'save'=> 		array(T_ZBX_INT, O_OPT, NULL,	NULL,		NULL),
		'viewtype'=> 		array(T_ZBX_INT, O_OPT, NULL,	NULL,		NULL),
		'oid' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'oids' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'idx' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'mib' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'templateid' =>		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'server_ip' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'server_port' =>	array(T_ZBX_STR, O_OPT, NULL,	BETWEEN(1,65535),	NULL),
		'community' => 		array(T_ZBX_STR, O_OPT, NULL,	NULL,		NULL),
		'snmp_version' =>	array(T_ZBX_INT, O_OPT, NULL,	NULL,		NULL),
		'history' =>            array(T_ZBX_INT, O_OPT, null,  BETWEEN(0,65535),'isset({save})'),
		'trends' =>             array(T_ZBX_INT, O_OPT, null,  BETWEEN(0,65535),'isset({save})'),
                'delay' =>              array(T_ZBX_INT, O_OPT, null,  BETWEEN(1,86400),'isset({save})'),
                'graph_create' =>       array(T_ZBX_STR, O_OPT, null,  NULL,		'isset({save})'),
                'graph_name' =>		array(T_ZBX_STR, O_OPT, null,  NULL,		'isset({save})'),
                'graph_width' =>	array(T_ZBX_INT, O_OPT, NULL,  BETWEEN(20,65535),'isset({save})'),
                'graph_height' =>	array(T_ZBX_INT, O_OPT, NULL,  BETWEEN(20,65535),'isset({save})'),
                'graph_type' =>		array(T_ZBX_INT, O_OPT, NULL,  IN('0,1,2,3'),	'isset({save})'),
                'graph_func' =>		array(T_ZBX_INT, O_OPT, NULL,  IN('1,2,4,7'),	'isset({save})'),
                'draw_type' =>		array(T_ZBX_INT, O_OPT, NULL,  IN('0,1,2,3,4,5,6'),'isset({save})'),
                'yaxisside' =>		array(T_ZBX_INT, O_OPT, NULL,  IN('0,1'),'isset({save})'),
// import
		'form' => 		array(T_ZBX_STR, O_OPT, P_SYS, NULL, NULL),
		'import' =>             array(T_ZBX_STR, O_OPT, P_SYS|P_ACT, null,              null),
	);

	check_fields($fields);

/* AJAX */
        if(isset($_REQUEST['favobj'])){
                 if('filter' == $_REQUEST['favobj']){
                        CProfile::update('web.snmpbuilder.filter.state', $_REQUEST['favstate'], PROFILE_TYPE_INT);
                 }
        }
           
        if((PAGE_TYPE_JS == $page['type']) || (PAGE_TYPE_HTML_BLOCK == $page['type'])){
        	require_once dirname(__FILE__).'/include/page_footer.php';
                exit();
	}
                                                                                                                       


//--------

// IMPORT ///////////////////////////////////
	if (isset($_REQUEST['import']) && isset($_FILES['mib'])) {

		$file = new CUploadFile($_FILES['mib']);

		$cmd = 'cp -f '.$_FILES['mib']['tmp_name'].' '.$mibs_dir.'/'.$_FILES['mib']['name'];
		exec("LANG=C $cmd 2>&1", $results, $code);
		if ($code) {
			show_messages(false, null, _('Import failed'));
			error(_('Command').": $cmd. "._('Message').": ".join($results));
		} else {
			show_messages(true, _('Imported successfully'));
		}
	}
	
//filter os command injection
	if (isset($_REQUEST['mib']) && !empty($_REQUEST['mib']))
	{
		if (!preg_match('/^[a-z,0-9,\.,\-]+$/i',$_REQUEST['mib']))
		{
			error(_s('Warning. Incorrect value for "%1$s"', 'MIB'));
		}
		$mib = escapeshellcmd($_REQUEST['mib']);
		//$mib = $_REQUEST['mib'];
	}
	else
		$mib = '';
	
	if (isset($_REQUEST['oid']) && !empty($_REQUEST['oid']))
	{
		if (!preg_match('/^[a-z,0-9,\.,\-,\:]+$/i', $_REQUEST['oid']))
		{
			error(_s('Warning. Incorrect value for "%1$s"', 'OID'));
		}
		$oid = escapeshellcmd($_REQUEST['oid']);
	}
	else
		$oid = '';
	
	if (isset($_REQUEST['idx']) && !empty($_REQUEST['idx']))
	{
		$idx = escapeshellcmd($_REQUEST['idx']);
	}
	else
		$idx = 0;
		
	if (isset($_REQUEST['server_ip']) && !empty($_REQUEST['server_ip'])) {
		if (!preg_match('/^[:0-9,\.]+$/i', $_REQUEST['server_ip'])) {
			error(_s('Invalid host address "%1$s"', $_REQUEST['server_ip']));
		}
		$server_ip = escapeshellcmd($_REQUEST['server_ip']);
		if (preg_match('/:([0-9]+)$/i', $_REQUEST['server_ip'], $matches)) {
			$server_port = $matches[1];
		} else {
			$server_port = 161;
		}
	} else {
		$server_ip = '';
		$server_port = 161;
	}

	if (isset($_REQUEST['community']) && !empty($_REQUEST['community']))
	{
		$community = escapeshellcmd($_REQUEST['community']);
	}
	else
		$community = 'public';

	if (isset($_REQUEST['snmp_version']) && !empty($_REQUEST['snmp_version'])) {
		$snmp_version = escapeshellcmd($_REQUEST['snmp_version']);
	} else {
		$snmp_version = ITEM_TYPE_SNMPV2C;
	}
	
	$templateid = 0;
	if (isset($_REQUEST['templateid']))
		$templateid = $_REQUEST['templateid'];
	if (isset($_REQUEST['oids']))
		$oids = $_REQUEST['oids'];
	if (isset($_REQUEST['viewtype']))
		$viewtype =  $_REQUEST['viewtype'];

		
////////////////////////////////////////////////

// actions

	if (isset($_REQUEST['history']) && !empty($_REQUEST['history'])) {
		$history = escapeshellcmd($_REQUEST['history']);
	} else {
		$history = 90;
	}

	if (isset($_REQUEST['trends']) && !empty($_REQUEST['trends'])) {
		$trends = escapeshellcmd($_REQUEST['trends']);
	} else {
		$trends = 365;
	}

	if (isset($_REQUEST['delay']) && !empty($_REQUEST['delay'])) {
		$delay = escapeshellcmd($_REQUEST['delay']);
	} else {
		$delay = 60;
	}

	if (isset($_REQUEST['graph_create']) && !empty($_REQUEST['graph_create'])) {
		$graph_create = 1;
	} else {
		$graph_create = 0;
	}

	if (isset($_REQUEST['graph_name']) && !empty($_REQUEST['graph_name'])) {
		$graph_name = escapeshellcmd($_REQUEST['graph_name']);
	} else {
		$graph_name = '';
	}

	if (isset($_REQUEST['graph_width']) && !empty($_REQUEST['graph_width'])) {
		$graph_width = escapeshellcmd($_REQUEST['graph_width']);
	} else {
		$graph_width = 900;
	}

	if (isset($_REQUEST['graph_height']) && !empty($_REQUEST['graph_height'])) {
		$graph_height = escapeshellcmd($_REQUEST['graph_height']);
	} else {
		$graph_height = 200;
	}

	if (isset($_REQUEST['graph_type']) && !empty($_REQUEST['graph_type'])) {
		$graph_type = escapeshellcmd($_REQUEST['graph_type']);
	} else {
		$graph_type = GRAPH_TYPE_NORMAL;
	}

	if (isset($_REQUEST['graph_func']) && !empty($_REQUEST['graph_func'])) {
		$graph_func = escapeshellcmd($_REQUEST['graph_func']);
	} else {
		$graph_func = CALC_FNC_AVG;
	}

	if (isset($_REQUEST['draw_type']) && !empty($_REQUEST['draw_type'])) {
		$draw_type = escapeshellcmd($_REQUEST['draw_type']);
	} else {
		$draw_type = GRAPH_ITEM_DRAWTYPE_LINE;
	}

	if (isset($_REQUEST['yaxisside']) && !empty($_REQUEST['yaxisside'])) {
		$yaxisside = escapeshellcmd($_REQUEST['yaxisside']);
	} else {
		$yaxisside = 1;
	}

	if (isset($_REQUEST['select']))
	{	// get snmp values for oid for "OID data" table
		if (!$oid || !$mib)
		{
			error(_s('Warning. Incorrect value for field "%1$s"', '[mib]'));
			exit;
		}	
		$content = get_oid_content($oid);
		
		if ($viewtype == 1 || preg_match('/Table$/', $oid)) {
			$value = get_table_value($community, $server_ip, $snmp_version, $oid);
		}
		else { //if (preg_match('/SYNTAX/i', $content)) {
			//value
			$value = get_oid_value($community, $server_ip, $snmp_version, $oid, $idx);
			if ($content == '') //Fix for table cells
			{
				$content = get_oid_content(escapeshellcmd($value['row'][0]));
			}
		
		}
//		else {
//			$row = array('','','');
//			$value = array('ret' => 0,'row' => $row);
//		}
		
		$json = new CJSON();
		echo $json->encode(array('info' => $content, 'value' => $value));
		exit;
	}
	else if (isset($_REQUEST['save'])) {
		// Save zabbix items in template
		// Check variables
		if (!$oids)
		{
			error(_s('Warning. Incorrect value for "%1$s"', '[oids]'));
		}
		if (!$templateid)
		{
			error(_s('Warning. Incorrect value for field "%1$s"', '[templateid]'));
		}
		if (!$history){
			error(_s('Warning. Incorrect value for field "%1$s"', '[history]'));
		}
		if (!$trends){
			error(_s('Warning. Incorrect value for field "%1$s"', '[trends]'));
		}
		if (!$delay){
			error(_s('Warning. Incorrect value for field "%1$s"', '[delay]'));
		}
		if (!$snmp_version){
			error(_s('Warning. Incorrect value for field "%1$s"', '[snmp_version]'));
		}

		$json = new CJSON;
		$oidlist = $json->decode($oids);
		if (count($oidlist) === 0) {
			error(_('OID list is null'));
		}

		// Create items
		$items = array();
		foreach($oidlist as $oid)
		{
			//item = [OID, Descr/OID, 
			// value_type ('Numeric', 'Text'...),
			// data_type ('Decimal'),
			// units ('s', ...),
			// mutiplyer ('0.01'),
			// delta (0/1)]
			$oid_num = get_oid_from_name(escapeshellcmd($oid[0]));
			if (!$oid_num)
				error(_s('OID is null "%1$s"', $oid[0]));
			
			//value_type	
			switch($oid[2])
			{
				case 'Numeric (integer 64bit)' :
					$value_type = ITEM_VALUE_TYPE_UINT64;
					break;
				case 'Numeric (float)' :
					$value_type = ITEM_VALUE_TYPE_FLOAT;
					break;
				case 'Text' :
					$value_type = ITEM_VALUE_TYPE_TEXT;
					break;
				default:
					error(_s('Invalid type "%1$s"', $oid[1]));
			}
			
			//data_type
			switch($oid[3])
			{
				case 'Decimal' :
					$data_type = ITEM_DATA_TYPE_DECIMAL;
					break;
				default:
					$data_type = ITEM_DATA_TYPE_DECIMAL;
			}
		
			//units
			if (!$oid[4])
				$units = null;
		
			//multiplier
			if (!$oid[5]) {
				$multiplier = null;
				$formula = null;
			} else {
				$multiplier = 1;
				$formula = $oid[4];
			}	

			//delta
			if ($oid[6]) {
				$delta = 1;
			} else {
				$delta = 0;
			}
			
			// From 1.8.1 zabbix does not accept special char in key, :( so we must replace them with underscore
			$newkey = preg_replace('/[^0-9a-zA-Z_\.]/','_',$oid[0]);
			$item = array(
				'name'		=> $oid[1],
				'key_'			=> $newkey,
				'hostid'		=> $templateid,
				'delay'			=> $delay,
				'history'		=> $history,
				'status'		=> ITEM_STATUS_ACTIVE,
				'type'			=> $snmp_version,
				'snmp_community'	=> $community,
				'snmp_oid'		=> $oid_num,
				'value_type'		=> $value_type,
				'trapper_hosts'		=> null,
				'snmp_port'		=> $server_port,
				'units'			=> $units,
				'multiplier'		=> $multiplier,
				'delta'			=> $delta,
				'snmpv3_securityname'	=> null,
				'snmpv3_securitylevel'	=> null,
				'snmpv3_authpassphrase'	=> null,
				'snmpv3_privpassphrase'	=> null,
				'formula'		=> $formula,
				'trends'		=> $trends,
				'logtimefmt'		=> null,
				'valuemapid'		=> null,
				'delay_flex'		=> null,
				'authtype'		=> null,
				'username'		=> null,
				'password'		=> null,
				'publickey'		=> null,
				'privatekey'		=> null,
				'params'		=> null,
				'ipmi_sensor'		=> null,
				'data_type'		=> $data_type
			);
			array_push($items, $item);
		}
		
		DBstart();
		$result = API::Item()->create($items);
		show_messages($result, _('Item added'), _('Cannot add item'));
		$result = DBend($result);
		$itemids = array();
		if ($result) {
			$itemids = $result['itemids'];
		}
		
		// Create graphs
		if ($graph_create) {
			createGraph($itemids, $graph_name, $graph_width, $graph_height, 
				    $graph_type, $graph_func, $draw_type, $yaxisside);
		}
	}
	else{
// Build widget

	//Header
	$form = new CForm();
	$form->cleanItems();
	$form->setMethod('post');

        if(isset($_REQUEST['form'])){
// MIB file import
		$import_wdgt = new CWidget();
		$import_wdgt->addPageHeader(_('SNMP Builder'), $form);
		
		$data = array();
		$data['form'] = get_request('form');
		$data['widget'] = $import_wdgt;
		
		$importForm = new CView('administration.snmp_builder', $data);

		$import_wdgt->addItem($importForm->render());
		$import_wdgt->show();
	} else {
	
	$snmp_wdgt = new CWidget();
	$message_div = new CDiv();
	$message_div->setAttribute("id", "message");
	$snmp_wdgt->addItem($message_div);
	
	// Template selector
	$cmbTemplates = new CComboBox('templateid', $templateid);
	foreach(get_templates() as $temp)
	{
		$cmbTemplates->addItem($temp['key'], $temp['host']);
	}
	$form->addItem(array(_('Template').':'.SPACE,$cmbTemplates,SPACE));
	
	//Mib selector
	$cmbMibs = new CComboBox('mib',$mib,'javascript: submit();');
	$paths = explode(':', MIBS_ALL_PATH);
	foreach($paths as $path)
	{
		$cmbMibs->addItem('','---'.$path);
		foreach(glob($path."/*.txt")  as $filename){
			$modulename = get_module_name($filename);
			if ($modulename)	
				$cmbMibs->addItem($modulename,$modulename);
		}
		foreach(glob($path."/*.mib")  as $filename){
			$modulename = get_module_name($filename);
			if ($modulename)	
				$cmbMibs->addItem($modulename,$modulename);
		}
		foreach(glob($path."/*.my")  as $filename){
			$modulename = get_module_name($filename);
			if ($modulename)	
				$cmbMibs->addItem($modulename,$modulename);
		}
		
	}
	
	$form->addItem(array(_('MIB').':'.SPACE,$cmbMibs,SPACE));
	
	// server ip textbox
	$ipbServer = new CTextBox('server_ip', $server_ip);
	$form->addItem(array(_('Host').':'.SPACE,$ipbServer,SPACE));
	
	// server port hidden
	$hidPort = new CTag('server_port', $server_port);
	$form->addItem(array($hidPort));

	// snmp version selector
	$cmbSnmpVersion = new CComboBox('snmp_version', $snmp_version);
	foreach (array(ITEM_TYPE_SNMPV1, ITEM_TYPE_SNMPV2C) as $v) {
	    $cmbSnmpVersion->addItem($v, ($v == ITEM_TYPE_SNMPV1) ? '1':'2c');
	}
	$form->addItem(array(_('SNMP Version').':'.SPACE,$cmbSnmpVersion,SPACE));

	// community textbox
	$tbCommunity = new CTextBox('community', $community);
	$form->addItem(array(_('Community').':'.SPACE,$tbCommunity ,SPACE));
	
	// MIB import button
	$btnImport = new CSubmit('form', _('Import').' '._('MIB'));
	$form->addItem($btnImport);
	
	$snmp_wdgt->addHeader(_('SNMP Builder'), $form);
	
	//Body
	$outer_table = new CTable();
	
	$outer_table->setAttribute('border',0);
	$outer_table->setAttribute('width','100%');
	$outer_table->setCellPadding(2);
	$outer_table->setCellSpacing(2);

	//Empty row
	$empty_row = new CDiv();
	$empty_row->addItem(array(''));
	
	//Left panel
	$left_tab = new CTable();

	//Oid tree
	$oid_tree_w = new CWidget();
	$oid_tree_w->setClass('header');
	$oid_tree_w->addHeader(_('OID Tree'));
	
	$oid_tree_div = new CDiv();
	$oid_tree_div->setAttribute("id","oidtree");
	
	$oid_tree_container = new CDiv($oid_tree_div);
	$oid_tree_container->addStyle("overflow: auto; background-color: rgb(255, 255, 255); color: #000000; height: 400px; width: $left_widget_width;");
	
	$oid_tree_w->addItem($oid_tree_container);
	$left_tab->addRow($oid_tree_w);
	$left_tab->addRow($empty_row);

	//Oid description
	$oid_info_w = new CWidget();
	$oid_info_w->setClass('header');
	$oid_info_w->addHeader(_('Information'));
	
	$oid_info_div = new CDiv();
	$oid_info_div->setAttribute("id","oidinfo");
	$oid_info_div->addStyle("overflow: auto; background-color: rgb(255, 255, 255); color: #000000; max-height: 150px; width: $left_widget_width;");
	$oid_info_w->addItem($oid_info_div);
	$left_tab->addRow($oid_info_w);
	
	//Right panel
	$right_tab = new CTable();
	$right_tab->setAttribute('width',$right_widget_width);

	//Oidview
	$oid_view_w = new CWidget();
	$oid_view_w->setClass('header');
	$oid_view_w->addHeader(array(_('OID Data').' - '._('Click to force view as table'),new CCheckBox('viewtype','no','onViewType()',1)));
	
	$oid_view_div = new CDiv();
	$oid_view_div->setAttribute("id","oidview");
	$oid_view_div->addStyle("overflow: auto; max-height: 250px; width: $right_widget_width;");
	$oid_view_w->addItem($oid_view_div);
	$right_tab->addRow($oid_view_w);
	
	//Itemlist
	$item_list_w = new CWidget();
	$item_list_w->setClass('header');
	$item_list_w->addHeader(_('Items list'));
	
	$item_list_div = new CDiv();
	$item_list_div->setAttribute("id","itemlist");
	$item_list_div->addStyle("overflow: auto; max-height: 150px; width: $right_widget_width;");
	$item_list_w->addItem($item_list_div);
	$right_tab->addRow($empty_row);
	$right_tab->addRow($item_list_w);

	//Item parameters
	$itemDelay = new CTextBox('delay', $delay);
	$itemDelay->setAttribute("maxlength", "5");
	$itemHistory = new CTextBox('history', $history);
	$itemHistory->setAttribute("maxlength", "5");
	$itemTrends = new CTextBox('trends', $trends);
	$itemTrends->setAttribute("maxlength", "5");

	$item_params_w = new CWidget();
	$item_params_w->setClass('header');
	$item_params_w->addHeader(_('Items'));
	$item_params_div = new CDiv();
	$item_params_div->setAttribute("id","itemparams");
	$item_params_div->addStyle("width: $right_widget_width;");
	$item_params_tbl = new CTable();
	$item_params_tbl->attr('class', 'formtable');
	$item_params_tbl->addStyle("width: 100%; align: left");
	$item_params_tbl->addRow(array(_('Interval').':', $itemDelay, _('History').':', $itemHistory, 
					_('Trends').':', $itemTrends));
	$item_params_div->addItem($item_params_tbl);
	$item_params_w->addItem($item_params_div);
	$right_tab->addRow($empty_row);
	$right_tab->addRow($item_params_w);

	// Graph parameters
	$graphCreate = new CCheckBox('graph_create', 'no', '', 1);
	$graphName = new CTextBox('graph_name', $graph_name);
	$graphWidth = new CTextBox('graph_width', $graph_width);
	$graphWidth->setAttribute("size", "5");
	$graphWidth->setAttribute("maxlength", "5");
	$graphHeight = new CTextBox('graph_height', $graph_height);
	$graphHeight->setAttribute("size", "5");
	$graphHeight->setAttribute("maxlength", "5");
	// Graph type selector
	$cmbGraphType = new CComboBox('graph_type', $graph_type);
	$cmbGraphType->addItem(GRAPH_TYPE_NORMAL, _('Normal'));
	$cmbGraphType->addItem(GRAPH_TYPE_STACKED, _('Stacked'));
	$cmbGraphType->addItem(GRAPH_TYPE_PIE, _('Pie'));
	$cmbGraphType->addItem(GRAPH_TYPE_EXPLODED, _('Exploded'));
	// Graph function selector
	$cmbGraphFunc = new CComboBox('graph_func', $graph_func);
	foreach (array(CALC_FNC_ALL, CALC_FNC_MIN, CALC_FNC_AVG, CALC_FNC_MAX) as $func) {
		$cmbGraphFunc->addItem($func, graph_item_calc_fnc2str($func));
	}
	// Draw type selector
	$cmbDrawType = new CComboBox('draw_type', $draw_type);
	foreach (graph_item_drawtypes() as $dt) {
		$cmbDrawType->addItem($dt, graph_item_drawtype2str($dt));
	}
	// Yaxisside selector
	$cmbYaxisside = new CComboBox('yaxisside', $yaxisside);
	foreach (array(0, 1) as $side) {
		$cmbYaxisside->addItem($side, ($side == 0) ? _('Left'):_('Right'));
	}
	
	$graph_params_w = new CWidget();
	$graph_params_w->setClass('header');
	$graph_params_w->addHeader(_('Graphs'));
	$graph_params_div = new CDiv();
	$graph_params_div->setAttribute("id", "graphparams");
	$graph_params_div->addStyle("width: $right_widget_width;");
        $graph_params_tbl = new CTable();
	$graph_params_tbl->attr('class', 'formtable');
	$graph_params_tbl->addStyle("width: 100%; align: left; border-width: 0 0 0 0;");
	$graph_params_tbl->setCellPadding(0);
	$graph_params_tbl->setCellSpacing(0);
        $graph_params_tbl1 = new CTable();
	$graph_params_tbl1->attr('class', 'formtable');
	$graph_params_tbl1->addStyle("width: 100%; align: left; border-width: 0 0 0 0;");
	$graph_params_tbl1->addRow(array(_('Create graph'),$graphCreate,
					_('Name').':',$graphName, 
					_('Width').':',$graphWidth, _('Height').':',$graphHeight,
					_('Graph type').':',$cmbGraphType));
        $graph_params_tbl2 = new CTable();
	$graph_params_tbl2->attr('class', 'formtable');
	$graph_params_tbl2->addStyle("width: 100%; align: left; border-width: 0 0 0 0;");
	$graph_params_tbl2->addRow(array(_('Function').':',$cmbGraphFunc, 
					_('Draw style').':',$cmbDrawType, 
					_('Y axis side').':',$cmbYaxisside));
	$graph_params_tbl->addRow($graph_params_tbl1);
	$graph_params_tbl->addRow($graph_params_tbl2);
	$graph_params_div->addItem($graph_params_tbl);
	$graph_params_w->addItem($graph_params_div);
	$right_tab->addRow($empty_row);
	$right_tab->addRow($graph_params_w);

	//Mib selector
	$cmbMibs = new CComboBox('mib',$mib,'javascript: submit();');
	
	//Action srow
	$action_w= new CWidget();
	$action_w->setClass('header');
	
	$action_w->addHeader(array(new CButton('save',_('Save'),'javascript: onSaveItems()'), SPACE, new CButton('clear',_('Clear'),'javascript: onClearItems()')));
	$right_tab->addRow($empty_row);
	$right_tab->addRow($action_w);
	
	// Left panel
	$td_l = new CCol($left_tab);
	$td_l->setAttribute('valign','top');
	$td_l->setAttribute('width',$left_widget_width);
	
	//Right panel
	$td_r = new CCol($right_tab);
	$td_r->setAttribute('valign','top');
	$td_r->setAttribute('width','100%');
	
	$outer_table->addRow(array($td_l,$td_r));
	$snmp_wdgt->addItem($outer_table);
	$snmp_wdgt->show();


// Javascript GUI init
	if ($mib)
	{
		$oid_tree = get_oid_tree($mib);
		
		$json = new CJSON();

		insert_js('	var j = jQuery.noConflict();
				j(document).ready(function(){
					j("#oidtree").jstree({
						"json_data" : {
							"data" : [
								'.$json->encode($oid_tree).'
							]
						},
						"plugins" : [ "themes", "json_data", "ui", "types", "cookies" ],
						"themes" : {
							"theme" : "mib",
							"dots" : true,
							"icons" : true
						},
						"types" : {
						    "max_depth" : -2,
						    "max_children" : -2,
						    "types" : {
							"table" : {
								"icon" : {
									"image" : "js/jquery/themes/mib/table.gif"
								},
							},
							"globe" : {
								"icon" : {
									"image" : "js/jquery/themes/mib/globe.gif"
								},
							},
						    },
						},
						"ui" : {
							"select_limit" : 1
						},
						"core" : {
							"initially_open" : [ "all" ],
							"animation" : 0,
						}
					
					}).bind("select_node.jstree", function(e, data) {
						var selectedObj = data.rslt.obj;
						clickTree(selectedObj.attr("id"), 0, null, ["'._('OID Name').'", "'._('Type of information').'", "'._('Value').'"]);
					});
				});
		');
		
		insert_js("
			var oidview = new DynTable('oidview',{'headers' : ['"._('OID Name')."','"._('Type of information')."','"._('Value')."']});
			var itemlist = new DynTable('itemlist',{'headers' : ['"._('SNMP OID')."','"._('Description')."','"._('Type of information')."','"._('Data type')."','"._('Units')."','"._('Custom multiplier')."','"._('Delta')."'], 'observer' : {'tr': onClickItem}});
		");
	} // if ($mib)
	} // if(isset($_REQUEST['import'])){
	} //
?>
<?php

include_once('include/page_footer.php');

?>

<?php
function get_module_name($filename)
{
	$modulename = '';
	$handle = @fopen($filename, "r");
	if ($handle) {
		while (!feof($handle)) {
			$buffer = fgets($handle, 4096);
			if (preg_match('/^\s*(\S+)\s*DEFINITIONS\s*::=\s*BEGIN/i',$buffer,$matches))
			{
				$modulename = $matches[1];
				break;
			}
		}
		fclose($handle);
	}
	return ($modulename);
}

/*function get_oid_from_name($name)
{
	$oid = exec("/usr/local/bin/snmptranslate -M ".MIBS_ALL_PATH." -m ALL -On $name");
	
	if (preg_match('/[0123456789\.]+/', $oid))
		return $oid;
	else
		return null;
}
*/

/*
function get_oid_from_name($name)
{
    $name = preg_replace('/"/','\\\\"',$name);
    file_put_contents('/tmp/snmpbuilder.log', "<!-- snmptranslate -LE 1 -M -m ALL -On $name -->\n", FILE_APPEND);
    echo "<!-- snmptranslate -LE 1 -M -m ALL -On $name -->\n";
    $oid = exec("/usr/local/bin/snmptranslate -LE 1 -M ".MIBS_ALL_PATH." -m ALL -On $name");
 
    if (preg_match('/[0123456789\.]+/', $oid))
	return $oid;
    else
	return null;
}
*/

function get_oid_from_name($name)
{
    $name = preg_replace('/"/','\\\\"',$name);
    if (preg_match("/'\w+'/",$name))
    {
        $arr = preg_replace("/'/",'',preg_split("/\./",$name));
        $name = $arr[0];
        foreach(str_split($arr[1]) as $char)
        {
            $name = $name.".".ord($char);
        }
    }
    $cmd = "/usr/local/bin/snmptranslate -LE 1 -M ".MIBS_ALL_PATH." -m ALL -On $name";
    $oid = exec("$cmd 2>&1", $results ,$code);
    if ($code) {
	    error(_('Function').": get_oid_from_name. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
    }

    if (preg_match('/[0123456789\.]+/', $oid))
        return $oid;
    else
        return null;
}


function get_table_value($community, $server_ip, $snmp_version, $oid)
{
	// table view
	$rows = array();
	if ($server_ip =="")
	{
		$rows[0] = array(_('No host address provided'));
	}
	else
	{
		if ($snmp_version == ITEM_TYPE_SNMPV1) {
			$ver = '1';
		} else {
			$ver = '2c';
		}
	
		$results = array();
		$cmd = "/usr/local/bin/snmptable -v $ver -c $community -M ".MIBS_ALL_PATH." -Ci -Ch -Cf \",\" -m ALL $server_ip $oid"; 
		exec("$cmd 2>&1", $results, $code);
		if ($code) {
			error(_('Function').": get_table_value. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
		}
		$headers = explode(",", $results[0]);
		unset($results);

		$cmd = "/usr/local/bin/snmptable -v $ver -c $community -M ".MIBS_ALL_PATH." -Ci -CH -Cf \",\" -m ALL $server_ip $oid";
		exec("$cmd 2>&1", $results, $code);
		if ($code) {
			error(_('Function').": get_table_value. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
		}
		foreach ($results as $line)
		{
			$row = explode(",",$line);
			array_push($rows, $row);
		}
		unset($results);
	}
			
	$value = array('ret' => 1,'headers' => $headers, 'rows' => $rows);
	return ($value);
}

function get_oid_value($community, $server_ip, $snmp_version, $oid, $idx)
{	
	if (!$server_ip){
		$row = array(_('No host address provided'),'','');
		$value = array('ret' => 0,'row' => $row);
		return ($value);
	}

	if ($snmp_version == ITEM_TYPE_SNMPV1) {
		$ver = '1';
	} else {
		$ver = '2c';
	}
	
	// idx is number or string thank danrog
	if (preg_match('/^[0-9]+$/', $idx)) {
		$cmd = "/usr/local/bin/snmpget -v $ver -c $community -M ".MIBS_ALL_PATH." -m ALL $server_ip $oid.$idx";
	} else {        
		$cmd = "/usr/local/bin/snmpget -v $ver -c $community -M ".MIBS_ALL_PATH." -m ALL $server_ip $oid.\"".$idx."\"";
	}	
	exec($cmd, $results, $code);
	if ($code) {
		error(_('Function').": get_oid_value. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
	}
	
	//exampe: IP-MIB::ipOutRequests.0 = Counter32: 12303729
	if (preg_match('/^(\S+) = (\S+): (.+)$/i', $results[0], $matches)) // full information
	{
		$row = array($matches[1], $matches[2], $matches[3]);
	}
	else if (preg_match('/^(\S+) = (\S+):$/i', $results[0], $matches)) //no value
	{
		$row = array($matches[1], $matches[2],'');
	}
	else if (preg_match('/^(\S+) = (.+)$/i', $results[0], $matches)) //no type
	{
		$row = array($matches[1], '',$matches[2]);
	}
	else // error
		$row = array(join(' ', $results),'','');
	$value = array('ret' => 0,'row' => $row);
	return ($value);
}

function get_oid_content($oid)
{
	$cmd = "/usr/local/bin/snmptranslate -Td -OS -M ".MIBS_ALL_PATH." -m ALL $oid";
	exec($cmd, $results, $code);
	if ($code) {
		error(_('Function').": get_oid_content. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
	}
		
	$content = implode("<br>", $results);
	return ($content);
}

//Get oid tree per mib 
function get_oid_tree($mib)
{
	$cmd = "/usr/local/bin/snmptranslate -Ts -M ".MIBS_ALL_PATH." -m $mib";
	exec($cmd, $results, $code);
	if ($code) {
		error(_('Function').": get_oid_tree. "._('Command').": $cmd. "._('Error').": $code. "._('Message').": ".join($results));
	}

	$oid_tree = explodeTree($mib, $results);
	return $oid_tree;
}

function get_templates()
{
	$options = array(
			'sortfield' => 'name',
			'sortorder' => ZBX_SORT_UP,
			'output' => API_OUTPUT_EXTEND,
			'selectTemplates' => array('templateid', 'name'),
			'nopermissions' => 1
		);
	$template = array();
	$template_list = API::Template()->get($options);
	foreach ($template_list as $tnum => $temp)
	{
		array_push($template, array('key' => $temp['templateid'], 'host' => $temp['name']));
	}
	
	return $template;
}

function explodeTree($mib, $array, $delimiter = '.')
{
    if(!is_array($array)) return false;
    $splitRE = '/' . preg_quote($delimiter, '/') . '/';
    $returnArr['attr']['id'] = '';
    $returnArr['data'] = $mib;
    $returnArr['attr']['rel'] = 'globe';
    $returnArr['children'] = array(array('attr'=>array('id'=>'.iso'),'data'=>'iso'),array('attr'=>array('id'=>'.ccitt'),'data'=>'ccitt'));

    foreach ($array as $key) {
        // Get parent parts and the current leaf
        $parts = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
	$leaf = array_pop($parts);
	$parentArr = &$returnArr;

		foreach ($parts as $part) {
			$child_id = $parentArr['attr']['id'].'.'.$part;
			if (!isset($parentArr['children']))
				$parentArr['children'] = array();
		
			for ($i = 0; $i < count($parentArr['children']); $i++)
			{
				if ($parentArr['children'][$i]['attr']['id'] == $child_id) {
					break;
				}
			}
		
			if (!isset($parentArr['children'][$i]))
			{
				echo $child_id." ".$leaf." ".$key;
				exit();
			}
		
			$parentArr = &$parentArr['children'][$i];
		}
		if (!isset($parentArr['children'])) {
			$parentArr['children'] = array();
		}
		$i = count($parentArr['children']);
		$parentArr['children'][$i]['attr']['id'] = $key;
		$parentArr['children'][$i]['data'] = $leaf;
		if (preg_match('/^\w+Table$/',$leaf)) {
			$parentArr['children'][$i]['attr']['rel'] = 'table';
		}
    }
    
    return $returnArr;
}

function getColor($num) {
	$colors = array(
                "00FF00", "0000FF", "FF0000", "FFFF00", "FF00FF", "00FFFF", "F0F0F0",
                "008000", "000080", "800000", "808000", "800080", "008080", "808080",
    	        "00C000", "0000C0", "C00000", "C0C000", "C000C0", "00C0C0", "C0C0C0",
	        "004000", "000040", "400000", "404000", "400040", "004040", "404040",
                "002000", "000020", "200000", "202000", "200020", "002020", "202020",
                "006000", "000060", "600000", "606000", "600060", "006060", "606060",
                "00A000", "0000A0", "A00000", "A0A000", "A000A0", "00A0A0", "A0A0A0",
        	"00E000", "0000E0", "E00000", "E0E000", "E000E0", "00E0E0", "E0E0E0",
	);

	if ($num < count($colors)) {
		$color = $colors[$num];
	} else {
		$color = dechex(rand(200, hexdec('EEEEEE')));
	}
	return($color);
}

function createGraph($itemids, $name, $width, $height, $graphtype, $func, $drawtype, $yaxisside) {
		if (!$name) {
			error(_s('Warning. Incorrect value for field "%1$s"', '[name]'));
			return(false);
		}
		
		if (!$graphtype) {
			$graphtype = 0;
		}
		if (!$width) {
			$width = 900;
		}
		if (!$height) {
			$height = 200;
		}

		// Check permissions
		if(!empty($itemids)){
			$options = array(
				'nodeids'=>get_current_nodeid(true),
				'itemids'=>$itemids,
				'filter' => array('flags' => array(ZBX_FLAG_DISCOVERY_NORMAL, ZBX_FLAG_DISCOVERY_CREATED)),
				'webitems'=>1,
				'editable'=>1,
				'output'=>API_OUTPUT_EXTEND
			);
			$items = API::Item()->get($options);
			$items = zbx_toHash($items, 'itemid');

			foreach($itemids as $inum => $itemid){
				if(!isset($items[$itemid])){
					access_deny();
				}
			}
		}

		if(empty($items)){
			info(_('Items required for graph'));
			return(false);
		}
		else{
			$gitems = array();
			$num = 0;
			foreach ($items as $inum => $item){
				if (($item['value_type'] != ITEM_VALUE_TYPE_UINT64) && 
				    ($item['value_type'] != ITEM_VALUE_TYPE_FLOAT)) {
					info(_s('Cannot create graph for non-numeric item, skipping "%1$s"', $item['name']));
					continue;
				}
				$gitem['itemid'] = $item['itemid'];
				$gitem['drawtype'] = $drawtype;
				$gitem['sortorder'] = $num;
				$gitem['color'] = getColor($num);
				$gitem['yaxisside'] = $yaxisside;
				$gitem['calc_fnc'] = $func;
				$gitem['type'] = 0;
				$gitems[] = $gitem;
				$num++;
			}
			
			$graph = array(
				'name' => $name,
				'width' => $width,
				'height' => $height,
				'ymin_type' => 0,
				'ymax_type' => 0,
				'yaxismin' => 0,
				'yaxismax' => 0,
				'ymin_itemid' => 0,
				'ymax_itemid' => 0,
				'show_work_period' => 1,
				'show_triggers' => 1,
				'graphtype' => $graphtype,
				'show_legend' => 1,
				'show_3d' => 0,
				'percent_left' => 0,
				'percent_right' => 0,
				'gitems' => $gitems
			);

			$result = API::Graph()->create($graph);
			if ($result) {
				info(_('Graph added'));
				add_audit(AUDIT_ACTION_ADD, AUDIT_RESOURCE_GRAPH, 'Graph ['.$name.']');
			} else {
				info(_('Cannot add graph'));
			}
		}
		return($result);
}
?>
