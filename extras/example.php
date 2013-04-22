Integracao Host
<?php
require_once("ZabbixAPI.class.php");
function localiza($p_tipo, $p_propriedade, $p_nome) {
	$retorno = ZabbixAPI::fetch_array($p_tipo,'get', array('output'=>'extend', 'limit'=>1,
		'filter' => array($p_propriedade=>$p_nome)
	)) or die('Unable to get: '.print_r(ZabbixAPI::getLastError(),true));
	return $retorno;
}
// This enables debugging, this is rather verbose but can help debug problems
ZabbixAPI::debugEnabled(TRUE);

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

// This logs into Zabbix, and returns false if it fails
ZabbixAPI::login('http://200.99.148.144/zabbix/','api','pt3hx')
    or die('Unable to login: '.print_r(ZabbixAPI::getLastError(),true));
$group = localiza('hostgroup', 'name','Classificar');
$template = localiza('template','host','001_Identifica');

$groupid = $group[0]['groupid'];
$templateid = $template[0]['templateid'];

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
    or die('<li>Unable to create host: '.
    print_r(ZabbixAPI::getLastError(),true).'</li>');

echo "Host inserido com sucesso!";

?>