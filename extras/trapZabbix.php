<?php 
/*
 * Webservice para integracao entre Ferramentas de terceiros e o Zabbix para recebimento de traps
 * @version 0.2 Public Release - November, 2012
 * @author Adail Horst @ http://spinola.net.br/blog/
 * @see http://spinola.net.br/blog/
 * Visa receber traps via HTTP oriundas de interface PHP do SGIR
*/
require_once('../extras/utils.php');
registra('Carregando classes');
// Iniciando parâmetros obrigatórios de servidor
iniciaPar('p_server','127.0.0.1'); 	// Nome do servidor zabbix que receberá a trap
iniciaPar('p_port','10051'); 		// Porta do zabbix_server
iniciaPar('p_host',''); 			// Host relativo ao evento
iniciaPar('p_key','');				// Chave da trap
iniciaPar('p_value','');			// Valor da trap

// Validação de parâmetros ------------------------------------------------------------
if ($_REQUEST['p_server'] == "") { debug_info('Servidor inválido!',true); exit; }
if ($_REQUEST['p_port'] == "") { debug_info('Porta inválida!',true); exit; }
if ($_REQUEST['p_host'] == "") { debug_info('Hostname faltando!',true); exit; }
if ($_REQUEST['p_key'] == "") { debug_info('Chave inválida!',true); exit; }
if ($_REQUEST['p_value'] == "") { debug_info('Valor inválido!',true); exit; }

// Enviando o sender -------------------------------------------------------------------
$host 	= $_REQUEST['p_host'];
$key 	= $_REQUEST['p_key'];
$value	= $_REQUEST['p_value'];
// Logando ação
//echo $_SERVER['REMOTE_ADDR'];exit;
//echo $_REQUEST['p_server'] .":". $_REQUEST['p_port'];exit;
zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 'SGIR_Integracao', 'integra.ip',$_SERVER['REMOTE_ADDR']);
zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], 'SGIR_Integracao', 'integra.dado','['.$host.']['.$key.']['.$value.']');
// Salvando o dado
zabbix_sender ($_REQUEST['p_server'], $_REQUEST['p_port'], $host, $key, $value);

?>
 
