<?php
/* Utilizei como base inicial para o desenvolvimento o "Zabbix-IS.php" e alguns outros arquivos do Frontend do Zabbix
** Objetivo: Proceder com pesquisa de correlacionamento de eventos
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


    require_once('include/config.inc.php');
    require_once('include/hosts.inc.php');
    require_once('include/forms.inc.php');
    require_once('zabbix-translate.php');

    $titulo     = _ze2('Event Management');
    $page['title'] 	= $titulo;
    $page['file'] 	= 'zabbix-em.php';
//    $page['hist_arg'] 	= array('hostid','groupid','graphid');
//	$page['scripts'] 	= array('class.calendar.js', 'gtlc.js');

    include_once('include/page_header.php');
/*  ------------------ Inicialização de variáveis ---------------------         */

    $startDateDefault = 86400*30;
    $_REQUEST['report_timesince'] 	= zbxDateToTime(get_request('report_timesince',date('YmdHis', time()-$startDateDefault)));
    $_REQUEST['report_timetill'] 	= zbxDateToTime(get_request('report_timetill',date('YmdHis')));
    $hostid = $_REQUEST['hostid']	= get_request('hostid', 0);    
    $applicationid = $_REQUEST['applicationid']	= get_request('applicationid', 0);
    $formato = $_REQUEST['formato']	= get_request('formato', 'html');
    
/*  ------------------ Filtro ---------------------                             */

    
    $options = array(
            'groups' => array(
                    'monitored_hosts' => 1,
                    'with_items' => 1
            ),
            'hosts' => array(
                    'monitored_hosts' => 1,
                    'with_items' => 1,
                    'with_triggers' => 1
            ),
            'hostid' => get_request('hostid', null),
            'applicationid' => get_request('applicationid', null),
            'groupid' => get_request('groupid', null),
            'triggerid' => get_request('triggerid', null)
    );
    $pageFilter = new CPageFilter($options);

    // Filtro do título --------------------------------------------------------
/*    $r_form->addItem(array(
            _('Group').SPACE,
            $pageFilter->getGroupsCB(true)
    ));
    $r_form->addItem(array(
            SPACE._('Host').SPACE,
            $pageFilter->getHostsCB(true)
    ));
*/
    $cmbGroups 		= $pageFilter->getGroupsCB(true);
    $cmbHosts 		= $pageFilter->getHostsCB(true);
    $cmbApplications = newComboFilter("select a.applicationid as id, a.name as description from applications a inner join hosts h on h.hostid = a.hostid where a.hostid = ". $hostid . " order by 2 ",$applicationid,'applicationid');

    // Combo com os formatos de exibição
    //$cmbFormato		= new CComboBox('formato', $formato, 'javascript: submit();');
    //$cmbFormato->additem('html', 'HTML');
    //$cmbFormato->additem('csv', 'CSV');

    $filter_table = new CTable('', 'filter_config');
    $filter_table->setAttribute('border',0);
    $filter_table->setAttribute('width','100%');
    $filter_table->addRow(array(
        array(bold(_('Group')), ': ', $cmbGroups),
        array(bold(_('Host')), ': ', $cmbHosts),
        exibeConteudo ($hostid > 0,array(bold(_('Application')), ': ', $cmbApplications)),
        array()
    ));
    
    $events_wdgt = new CWidget();
    $events_wdgt->addHeader(_ze($titulo).SPACE.'['.zbx_date2str(_('d M Y H:i:s')).']', 
      array(get_icon('fullscreen', array('fullscreen' => $_REQUEST['fullscreen'])))
    );
    $events_wdgt->addHeaderRowNumber();

/*
     $events_wdgt->addPageHeader(
        _ze($titulo).SPACE.'['.zbx_date2str(_('d M Y H:i:s')).']',
        array(
            $frmForm,
            SPACE,
            get_icon('fullscreen', array('fullscreen' => $_REQUEST['fullscreen']))
        )
    );
*/
     
    
/* ------------------- Report ---------------------                             */
    $rep2_wdgt = new CWidget();
    $rep2_wdgt->addPageHeader(_ze($titulo));

    // Tabela que apresentará o relatório
    $table = new CTableInfo();
    $table->setHeader(
            array(is_show_all_nodes() ? _('Node') : null,
            (($hostid == 0) || (1 == $config))? _('Trigger') : NULL,
            _('Name'),
            _('Error'),
    ));
    $table->setHeader(array(
            _('Time'),
            is_show_all_nodes() ? _('Node') : null,
            ($_REQUEST['hostid'] == 0) ? _('Host') : null,
            _('Description'),
            _('Status'),
            _('Severity'),
            _('Duration'),
            ($config['event_ack_enable']) ? _('Ack') : null,
            _('Actions')
    ));
    // Qual o evento "sintoma" do problema
        //  Filtrar por grupo de host
        //  Filtrar por host
    // Qual o período que se deseja pesquisar
    // Quais grupos deseja-se pesquisar
    // Apenas no node / proxy atual ?
    // Apenas eventos iniciados no período ? Ou eventos ativos a mais tempo devem ser apresentados ?
        // Adicionar recurso para marcar correlacionamento dos eventos para sinalização em evento futuro.
    
    $events_wdgt->show();
    $rep2_wdgt->show();
    include_once 'include/page_footer.php';
?>
