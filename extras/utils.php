<?php
	global $VG_DEBUG;
	$VG_DEBUG = (isset($_REQUEST['p_debug']) && $_REQUEST['p_debug'] == 'S' ? TRUE : FALSE );
	if ($VG_DEBUG == TRUE) {
		error_reporting( E_ALL & ~E_NOTICE);
		ini_set('display_errors', '1');
	}
	include_once "../conf/zabbix.conf.php";
  	// Importa informações de conexão do zabbix -------------------------------------------------------------------
	// Funções de banco MYSQL -------------------------------------------------------------------------------------
    function debugInfo ($p_mensagem, $p_debug = false, $p_cor = "gray") {
		global $VG_DEBUG;
		if ($p_debug == true || $VG_DEBUG == true) {
			echo '<div style="background-color:'.$p_cor.';">'.$p_mensagem."</div>";
		}
	}

	function conectaBD() {
		global $DB;
		$config_host = $DB['SERVER'];
		$config_user = $DB['USER'];
		$config_password = $DB['PASSWORD'];
		$config_db = $DB['DATABASE'];
		// Opens a connection to a mySQL server
		//debugInfo($config_host. " - ". $config_user. " - ". $config_password,true);
		$connection=mysql_connect ($config_host, $config_user, $config_password);
		if (!$connection) {
		  die("Not connected : " . mysql_error());
		}

		// Set the active mySQL database
		$db_selected = mysql_select_db($config_db, $connection);
		if (!$db_selected) {
		  die ("Can\'t use db : " . $config_db . " - " . mysql_error());
		}
		mysql_query("SET names utf8;");
	}
	function preparaQuery ($p_query) {
		debugInfo($p_query,$_REQUEST['p_debug'] == 'S');
		$result = mysql_query($p_query);
		if (!$result) { 
			die("Invalid query: " . mysql_error()); 
			return 0;
		} else { return $result; } 
	}
	function valorCampo ($p_query, $p_campo) {
		$result = preparaQuery($p_query);
		while ($row = @mysql_fetch_assoc($result)){ 
			$retorno = $row[$p_campo];
		}
		return $retorno;
	}
	function arraySelect ($p_query) {
		$result = preparaQuery($p_query);
		$retorno = array();
		$cont = 0;
		while ($row = @mysql_fetch_assoc($result)){ 
			$retorno[$cont] = $row;
			$cont++;
		}
		return $retorno;
	}

	// Fim Funcoes de banco MYSQL (remover no futuro utilizando as de conexão do zabbix... ) ----------------------

//	error_reporting(E_ALL);
//	ini_set('display_errors', '1');	
	global $tempos;
	$tempos = array();
function execucao(){ 
	list($usec, $sec) = explode(' ', microtime());
	$custo = (float) $sec + (float) $usec;
    return $custo; 
}
function registra($p_desc) {
	global $tempos;
	$tmp = execucao();
	if (count($tempos) == 0) {
		$custoMomento = 0;
	} else {
		$custoMomento = round((float)$tmp-(float)$tempos[count($tempos)-1][1], 5);
	}
	$tempos[count($tempos)] = array($p_desc, (float)$tmp, (float)$custoMomento,'<br>');	
	echo '<div style="color:blue;">'.$tempos[count($tempos)-1][0].'</div>';
//	var_dump($tempos[count($tempos)-1][0]);
}
function tempoTotal () {
	global $tempos;
	$custoTotal = number_format(((float)$tempos[count($tempos)-1][1]-(float)$tempos[0][1]),6);
	echo "<div>Tempo total: ".$custoTotal." segundo(s)</div>";
//	echo "<div>Uso de memória: ".round(((memory_get_peak_usage(true) / 1024) / 1024), 2). 'Mb'."</div>";
}

	
function curPageURL() {
	$pageURL = 'http';
	if (!isset($_SERVER["HTTPS"])) {$_SERVER["HTTPS"] = '';}
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];//.$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"];//.$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function mostra_erro_api ($p_mensagem) {
	global $VG_DEBUG;
	$tmp = ZabbixAPI::getLastError();
//	var_dump($tmp);
	$extra = $tmp['data']." - [Erro ". $tmp['code']." - ".$tmp['message'].']';
	if ($VG_DEBUG) { var_dump($tmp['debug']); }
	return mostra_erro($p_mensagem.$extra);
}
function mostra_erro ($p_mensagem) {
	return '<div style="color:red">'.$p_mensagem."</div>";
}
function debug_info ($p_mensagem, $p_exibe = false) {
	if ($p_exibe == TRUE) {
		echo mostra_erro($p_mensagem);
	}
}

function zabbix_sender ($p_server, $p_port, $p_hostname, $p_key, $p_value, $p_timeout = 30) {
	global $VG_DEBUG;
	if ($VG_DEBUG == true) {
		debug_info ('server = '.$p_server.":".$p_port,true);
		debug_info ('key/value = '.$p_key.":".$p_value,true);
		debug_info ('hostname = '.$p_hostname,true);
	} else {
		$fp = fsockopen($p_server, $p_port, $errno, $errstr, $p_timeout);
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			$host64=base64_encode($p_hostname);
			$key64=base64_encode($p_key);
			$msg64=base64_encode($p_value); 
			$out="<req><host>$host64</host><key>$key64</key><data>$msg64</data></req>\n";
			fwrite($fp, $out);
			while (!feof($fp)) {
				echo fgets($fp, 128);
			}
			fclose($fp);
		}
	}
}

function custoAtual () {
	global $tempos;
	return  number_format(((float)$tempos[count($tempos)-1][1]-(float)$tempos[0][1]),6);
}

function iniciaPar($p_parametro, $p_default = "", $p_erro = "Parâmetro invalido", $p_aborta = false) {
    if ($p_default != "") {
        global $_REQUEST;
        $_REQUEST[$p_parametro] = $p_default;
    }
    if ($_REQUEST[$p_parametro] == "") { 
        debug_info($p_erro,$p_aborta); 
        if ($p_aborta == true) {
            exit;
        }
    }
}

?>