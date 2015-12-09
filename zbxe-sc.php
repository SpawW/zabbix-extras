<?php

/* Utilizei como base inicial para o desenvolvimento o "zabbix-cat.php" e alguns outros arquivos do Frontend do Zabbix
 * * Objetivo: Relatório de custo de armazenamento e processamento na monitoração
 * * Adail Horst - http://spinola.net.br/blog

 * * Comentários do arquivo original -------------------------------------------
 * * ZABBIX
 * * Copyright (C) 2000-2010 SIA Zabbix
 * *
 * * This program is free software; you can redistribute it and/or modify
 * * it under the terms of the GNU General Public License as published by
 * * the Free Software Foundation; either version 2 of the License, or
 * * (at your option) any later version.
 * *
 * * This program is distributed in the hope that it will be useful,
 * * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * * GNU General Public License for more details.
 * *
 * * You should have received a copy of the GNU General Public License
 * * along with this program; if not, write to the Free Software
 * * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 * * 
 * */
?>
<?php

require_once('include/config.inc.php');
require_once('include/hosts.inc.php');
require_once('include/forms.inc.php');
require_once('include/zbxe_visual_imp.php');

/* Configuração basica do arquivo para o módulo de segurança do Zabbix	 */
$page['title'] = _zeT('Costs');
$page['file'] = 'zbxe-sc.php';
$page['hist_arg'] = array('hostid', 'groupid');

include_once('include/page_header.php');
?>
<?php

function descritivo($texto) {
    $texto = str_replace("\n", ";\n", $texto);
    $arrayDesc = explode("\n", $texto);
    return new CTag('div', 'yes', $arrayDesc, 'text');
}

// Calculo de UBMs
function totalUBM($vps, $gb) {
    $refVPS = 0.15;
    $refGB = 0.07;
    $fatorVPS = 0.59;
    $fatorGB = 0.29;
    $fatorHost = 0.12;
    // Transformando de bytes para GB
    $gb = round($gb / 1024 / 1024 / 1000, 4);
    //var_dump(array("gb" => $gb, "vps" => $vps));
    //return ($gb/1024/1024);
    return round((($vps * 100 / $refVPS) / 100 * $fatorVPS) + (($gb * 100 / $refGB) / 100 * $fatorGB) + $fatorHost, 2);
}

?>
<?php

//		VAR			TYPE	OPTIONAL FLAGS	VALIDATION	EXCEPTION
$fields = array(
    'groupid' => array(T_ZBX_INT, O_OPT, P_SYS, DB_ID, null),
    'hostid' => array(T_ZBX_INT, O_OPT, P_SYS, DB_ID, null),
    'formato' => array(T_ZBX_STR, O_OPT, P_SYS, DB_ID, null), // Identificador do formato
    'view' => array(T_ZBX_STR, O_OPT, P_SYS, DB_ID, null), // Identificador da visão que se deseja
    'nmitem' => array(T_ZBX_STR, O_OPT, P_SYS, DB_ID, null), // Exibe ou ignora itens não monitorados
    /* actions */
    'filter' => array(T_ZBX_STR, O_OPT, P_SYS | P_ACT, null, null)
);

check_fields($fields);
?>
<?php

// Atribuição de variaveis ========================================
$options = array(
    'groups' => array('monitored_hosts' => 1),
    'hosts' => array('monitored_hosts' => 1),
    'groupid' => getRequest('groupid', null),
    'hostid' => getRequest('hostid', null)
);
$pageFilter = new CPageFilter($options);
$startDateDefault = 86400 * 30;
$groupid = $_REQUEST['groupid'] = getRequest('groupid', 0);
$hostid = $_REQUEST['hostid'] = getRequest('hostid', 0);
$formato = $_REQUEST['formato'] = getRequest('formato', 'html');
$view = $_REQUEST['view'] = getRequest('view', 'H');
$nmItem = $_REQUEST['nmitem'] = getRequest('nmitem', 'S');

// Verificação de segurança =========================================

$groupids = checkAccessGroup('groupid');
$hostids = checkAccessHost('hostid');
$hostprof_wdgt = new CWidget();
// Formulario de Filtro =========================================================
// Combos de filtro =========================================================
$cmbGroups = $pageFilter->getGroupsCB(true);
$cmbHosts = $pageFilter->getHostsCB(true);
// Combo com os formatos de exibição
$cmbFormato = newComboFilterArray(array(
    'html' => _('HTML'), 'csv' => _('CSV')
        ), 'formato', $formato);
// Combo com os formatos de exibição
$cmbView = newComboFilterArray(array(
    'H' => _('Item'), 'G' => _('Host')
        ), 'view', $view);
// Combo com a regra de cálculo
$cmbNMItem = newComboFilterArray(array(
    'S' => _('Show'), 'H' => _('Hide')
        ), 'nmitem', $nmItem);

// FIM Combos de filtro =========================================================
$hostprof_wdgt->addHeader($page['title'], array());
$filter_table = new CTable('', 'filter_config');
$filter_table->setAttribute('border', 0);
$filter_table->setAttribute('width', '100%');

$reporttimetab2 = new CTable(null, 'calendar');
$reporttimetab2->addRow(array(array(bold("Formato"), ': '), array($cmbFormato)));
/* ----------- Implementa o Filtro por período --------------- */
$filter_table->addRow(array(
    array(bold(_('Group')), ': ', $cmbGroups),
    array(bold(_('Host')), ': ', $cmbHosts),
    array(bold(_zeT('Format')), ': ', $cmbFormato),
    array(bold(_zeT('View')), ': ', $cmbView),
    array(bold(_zeT('Not monitored items')), ': ', $cmbNMItem),
    array()
));

$filter_form = new CForm();
$filter_form->setMethod('get');
$filter_form->setAttribute('name', 'zbx_filter');
$filter_form->setAttribute('id', 'zbx_filter');

$reset = new CButton('reset', _('Reset'));
$reset->onClick("javascript: clearAllForm('zbx_filter');");
$filter = new CButton('filter', _zeT('Update Filter'));
$filter->onClick("javascript: submit();");

$footer_col = new CCol(array($filter, SPACE, $reset), 'center');
$footer_col->setColSpan(4);

$filter_table->addRow($footer_col);

$filter_form->addItem($filter_table);
$hostprof_wdgt->addFlicker($filter_form, true);

// FIM Formulario de Filtro =========================================================
$completo = (getRequest('formato', '') != '') && ($groupid > 1 || $hostid > 1);

if ($completo) {
// Localizar todos os hosts que o usuário selecionou -------------------------------------
    $baseQuery = "SELECT hos.name as host_name, it.hostid, it.name as item_name, it.key_ as item_key, it.delay, it.history, it.trends, it.status , 86400 / it.delay * it.history AS history_costs, it.trends * 24 AS trends_costs, it.itemid as itemid
FROM items it
INNER JOIN hosts hos
   ON hos.hostid = it.hostid
INNER JOIN hosts_groups hgr
   ON hgr.hostid = it.hostid
" . ( $groupid > 0 ? " AND hgr.groupid = " . $groupid : "")
            . " WHERE it.flags <> 2 and it.type not in (2,17)
" . ($hostid > 0 ? " AND it.hostid = " . $hostid : "")
            . ($nmItem == "H" ? " AND it.status = 0 " : "")
            . "\n order by host_name, item_key ";
    $report = Array();
    $cont = $historyTotal = $trendTotal = $storageTotal = $vpsTotal = $ubmTotal = (float) 0;
    // Relatorio por grupos (total do host)=========================
    if ($view == "G") {
        $baseQuery = "SELECT hitem.host_name, hitem.hostid"
                . ", SUM( history_costs ) AS history_costs, SUM( trends_costs ) AS trends_costs, SUM(1/delay) vps_costs"
                . " FROM (" . $baseQuery . ") hitem "
                . " Group by hitem.hostid, hitem.host_name "
                . " order by hitem.host_name, hitem.hostid";
        $result = DBselect($baseQuery);
        $idVPS = 4;
        $idUBM = 5;
        $idtotal = 6;
        while ($row = DBfetch($result)) {
            $report[$cont][0] = $row['host_name'];
            $report[$cont][1] = round(floatval($row['history_costs']), 0);
            $historyTotal += $report[$cont][1];
            $report[$cont][2] = round(floatval($row['trends_costs']), 0);
            $trendTotal += $report[$cont][2];
            $report[$cont][$idtotal] = ($report[$cont][1] * 50) + ($report[$cont][2] * 128);
            $storageTotal += $report[$cont][$idtotal];
            $report[$cont][3] = convert_units(array('value' => $report[$cont][$idtotal], 'units' => 'B'));
            $report[$cont][$idVPS] = (float) round(floatval($row['vps_costs']), 4);
            $vpsTotal += (float) $report[$cont][$idVPS];
            $report[$cont][$idUBM] = totalUBM($report[$cont][4], $report[$cont][$idtotal]);
            $ubmTotal += (float) $report[$cont][$idUBM];
            // Adicionando a unidade
            $report[$cont][1] .=' ' . _zeT('rows');
            $report[$cont][2] .=' ' . _zeT('rows');
            $report[$cont][$idVPS] .=' vps';
            $cont++;
        }
    } else {
        // Relatorio por item individual ===============================
        $result = DBselect($baseQuery);
        $idVPS = 10;
        $idtotal = 12;
        $idUBM = 11;
        $lastItemID = -1;
        while ($row = DBfetch($result)) {
            if ($lastItemID !== $row['itemid']) {
                $report[$cont][0] = $row['host_name'];
                $report[$cont][1] = $row['item_name'];
                $report[$cont][2] = $row['item_key'];
                $report[$cont][3] = $row['delay'];
                $report[$cont][4] = $row['history'];
                $report[$cont][5] = $row['trends'];
                $report[$cont][6] = ($row['status'] == 1 ? _('Not monitored') : _('Active'));
                $report[$cont][7] = round(floatval($row['history_costs']), 0);
                $historyTotal += $report[$cont][7];
                $report[$cont][8] = round(floatval($row['trends_costs']), 0);
                $trendTotal += $report[$cont][8];
                $report[$cont][$idtotal] = ($report[$cont][7] * 50) + ($report[$cont][8] * 128);
                $storageTotal += $report[$cont][$idtotal];
                $report[$cont][9] = convert_units(array('value' => $report[$cont][$idtotal], 'units' => 'B'));
                $report[$cont][$idVPS] = round(1 / floatval($row['delay']), 4);
                $vpsTotal += (float) $report[$cont][$idVPS];
                $report[$cont][$idUBM] = totalUBM($report[$cont][$idVPS], $report[$cont][$idtotal]);
                $ubmTotal += (float) $report[$cont][$idUBM];
                // Adicionando a unidade
                $report[$cont][7] .=' ' . _zeT('rows');
                $report[$cont][8] .=' ' . _zeT('rows');
                $report[$cont][10] .=' vps';
                $cont++;
            }
            $lastItemID = $row['itemid'];
        }
    }
    // Monta o relatório ----------------------------------------------------------
    $table = new CTableInfo();
    switch ($formato) {
        case 'csv';
            $table->setHeader(array("Dados"));
            break;
        case 'html';
            // Cabeçalho do relatorio HTML
            if ($view == "G") {
                $table->setHeader(array(_("Host"), _("History"), _("Trends")
                    , _zeT("Storage"), _zeT("VPS"), _zeT("BMU")
                ));
            } else {
                $table->setHeader(array(_("Host"), _("Item"), _("Key"), _("Delay")
                    , _("History"), _("Trends"), _("Status")
                    , _("History"), _("Trends")
                    , _zeT("Storage"), _zeT("VPS"), _zeT("BMU")
                ));
            }
            break;
    }
    $linha = array();
    $linhasDesc = " " . _zeT("rows");
    $cont2 = count($report[0]) - 1;
    for ($i = 0; $i < $cont; $i++) {
        switch ($formato) {
            case 'csv';
                $linhaCSV = "";
                for ($x = 0; $x < $cont2; $x++) {
                    $linhaCSV .= quotestr($report[$i][$x]) . ";";
//                                $linhaCSV .= quotestr($report[$i][$x].($x == 7 || $x == 8 ? $linhasDesc : " ")).";";
                }
                $table->addRow(array($linhaCSV));
                break;
            case 'html';
                for ($x = 0; $x < $cont2; $x++) {
                    $linha[$x] = new CCol($report[$i][$x], 1);
//                                $linha[$x] = new CCol($report[$i][$x].($x == 7 || $x == 8 ? $linhasDesc : " "),1);
                }
                // Calculo de UBM por host
                //$linha[$cont2] = new CCol(totalUBM($report[$i][],$report[$i][]),1);                            
                $table->addRow($linha);
                break;
        }
    }
    $descricao = new CCol('');
    $descricao->setAttribute('colspan', '6');
    $descricao->setAttribute('align', 'right');
    if ($formato !== 'csv') {
        if ($view == "G") {
            $table->addRow(array('Total', $historyTotal . $linhasDesc, $trendTotal . $linhasDesc
                , convert_units(array('value' => $storageTotal, 'units' => 'B')), (float) $vpsTotal . ' vps', $ubmTotal));
        } else {
            //var_dump($vpsTotal);
            $table->addRow(array($descricao, 'Total', $historyTotal . $linhasDesc, $trendTotal . $linhasDesc
                , convert_units(array('value' => $storageTotal, 'units' => 'B')), (float) $vpsTotal . ' vps'), $ubmTotal);
        }
    }
    $numrows = new CDiv();
    $numrows->setAttribute('name', 'numrows');
    $hostprof_wdgt->addHeader($numrows);

    if (versaoZabbix() < 241) {
        $paging = getPagingLine($report);
    } else {
        $paging = getPagingLine($report, "ASC");
    }

    $hostprof_wdgt->addItem($table);
} else {
    $hostprof_wdgt->addItem(_zeT('Enter the parameters for research!'));
}
$hostprof_wdgt->show();
?>
<?php

include_once('include/page_footer.php');
?>
