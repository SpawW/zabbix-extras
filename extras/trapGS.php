<?php 
/*
 * Webservice para integracao entre Ferramentas de terceiros e o Zabbix para recebimento de traps
 * @version 0.1 Public Release - February, 2013
 * @author Adail Horst @ adail.horst@serpro.gov.br
 * Visa receber traps via HTTP oriundas da monitoração com visão de usuário da GS
*/

require_once('../extras/utils.php');
registra('Carregando classes');
 
// Iniciando parâmetros obrigatórios de servidor
iniciaPar('p_server','127.0.0.1'); 	// Nome do servidor zabbix que receberá a trap
iniciaPar('p_port','10051'); 		// Porta do zabbix_server
iniciaPar('p_host','GS_Integracao'); 			// Host relativo ao evento
iniciaPar('p_key','');				// Chave da trap
iniciaPar('p_value','');			// Valor da trap

// Enviando o sender -------------------------------------------------------------------
$host 	= 'GS_Integracao';
$key 	= $_REQUEST['p_key'];
$value	= $_REQUEST['p_value'];

if ($_REQUEST['p_processo'] == "" || $_REQUEST['p_tempo'] == "" || $_REQUEST['p_disp'] == "") {
    zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 
       $host, 'gs.trapinvalida',1);
    zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 
       $host, 'gs.ipdeorigem',$_SERVER['REMOTE_ADDR']);
}

iniciaPar('p_processo','',"Favor informar o nome do processo",true);		
iniciaPar('p_tempo','',"Favor informar o tempo utilizado no processo",true);	
iniciaPar('p_disp','',"Favor informar o status de disponibilidade do processo",true);

$_REQUEST['p_processo'] = strtolower($_REQUEST['p_processo']);
registra("[p_processo={$_REQUEST['p_processo']}]");
registra("[p_tempo={$_REQUEST['p_tempo']}]");
registra("[p_disp={$_REQUEST['p_disp']}]");

zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 
    $host, 'gs.'.$_REQUEST['p_processo'].".custo",intval($_REQUEST['p_tempo'])/1000);
zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 
    $host, 'gs.'.$_REQUEST['p_processo'].".status",$_REQUEST['p_disp']);

?>
 
