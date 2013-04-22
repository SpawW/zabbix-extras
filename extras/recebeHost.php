<?php
require_once('../extras/utils.php');
registra('Carregando classes');
require_once("ZabbixAPI.class.php");
ZabbixAPI::debugEnabled($VG_DEBUG);
$zabbixServer = curPageURL()."/zabbix/";//'http://zabbix.spinola.net.br/';
//echo $zabbixServer;
if (!isset($_REQUEST['p_dns'])) {$_REQUEST['p_dns'] = '';}
function localiza($p_tipo, $p_propriedade, $p_nome) {
	$retorno = ZabbixAPI::fetch_array($p_tipo,'get', array('output'=>'extend', 'limit'=>1,
		'filter' => array($p_propriedade=>$p_nome)
	)) or die(mostra_erro_api('Erro de consulta: '));
	return $retorno;
}
// This logs into Zabbix, and returns false if it fails
registra('Login no Zabbix');
ZabbixAPI::login($zabbixServer,'api','awdqse1@3')
    or die(mostra_erro_api('Erro de login: '));
registra('Buscando hostgroup');
$group = localiza('hostgroup', 'name','Classificar');
registra('Buscando template');
$template = localiza('template','host','001_Identifica');

$groupid = $group[0]['groupid'];
$templateid = $template[0]['templateid'];

registra('Criando Host');
$hostid = ZabbixAPI::query('host', 'create', array(
		"host"=>$_REQUEST['p_hostname']
		, "interfaces"=>array(
			 "type"=>1,
              "main"=>1,
              "useip"=>($_REQUEST['p_dns'] == "" ? 1 : 0),
              "ip"=>"{$_REQUEST['p_ip']}",
              "dns"=>"{$_REQUEST['p_dns']}",
              "port"=>10050
		)
		, "groups"=>array(array("groupid" => $groupid))
		, "templates"=>array(array("templateid" => $templateid))
		, 'inventory'=>array("notes"=>"Host adicionado automaticamente pela integração.")
		)
	) 
    or 
	die(mostra_erro_api('Erro ao criar host: '));

registra('Host inserido com sucesso...');
tempoTotal();
?>