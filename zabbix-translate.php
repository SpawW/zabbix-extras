<?php
    require_once('conf/zabbix.conf.php');

    global $VG_DEBUG;
  $VG_DEBUG = (isset($_REQUEST['p_debug']) && $_REQUEST['p_debug'] == 'S' ? TRUE : FALSE );
  global $zeMessages, $zeLocale, $baseName;
/*  $zeMessages = Array (
	"en_GB" => Array (
//Zabbix-CAT	
	"Zabbix-CAT-Title" => "Zabbix-CAT - Capacity and Trend"
	,"Zabbix-CAT-UpdateFilter" => 'Update filter'
	,"Zabbix-CAT-Analysis"=>"Analysis"
	,"Zabbix-CAT-Projection"=>'Projection'
	,"Zabbix-CAT-Ammount"=>'Amount'
	,"Zabbix-CAT-Trend"=>'Trend'
	,"Zabbix-CAT-Day" => "Day"
	,"Zabbix-CAT-Week" => "Week"
	,"Zabbix-CAT-Month" => "Month"
	,"Zabbix-CAT-Year" => "Year"
	,"Zabbix-CAT-Max"=>'Max'
	,"Zabbix-CAT-Avg"=>'Avg'
	,"Zabbix-CAT-Min"=>'Min'
	,'Zabbix-CAT-Formatting'=>'Formatting'
	,'Zabbix-CAT-Chart'=>'Chart'
	,'Zabbix-CAT-HistoryData'=>'Data from history'
	,'Zabbix-CAT-Data'=>'Data'
	,'Zabbix-CAT-Instant'=>'Instant'
	,'Zabbix-CAT-Value'=>'Value'
	,'Zabbix-CAT-Type'=>'Type'
//Zabbix-NS
	, "Zabbix-NS-Title" => "Not Supported Items"
	, "Zabbix-NS-TitleBig" =>"Not Supported Items Report"
//Zabbix-SC
	, "Zabbix-SC-Title" => "Zabbix-SC - Storage Costs"
	, "Zabbix-SC-WelcomeMessage" => "Enter the parameters for the research!"
//Zabbix-ALE
	, "Zabbix-ALE-Extended" => " extended"
	)
	, "pt_BR" => Array (
// Novo formato - Texto direto
     "Update Filter" => "Atualizar filtro",
     "Event Management" => "Correlacionamento de eventos",
     "Correlate" => "Correlacionar",
     "Amount" => "Ocorrências",
     "Enter the parameters for research!" => "Informe os parâmetros para a pesquisa!",
     "No events related to the event source or without events compatible with the filter informed." => "Sem eventos relacionados ao evento de origem ou sem eventos compatíveis com o filtro informado.",
     "History Costs" => "Custo - Histórico",
     "Trends Costs" => "Custo - Estatísticas",
     "Storage Costs" => "Custo - Armazenamento",
     "Related incidents" => "Incidentes relacionados",
     "Possible cause." => "Possível causa.",
     "Possible consequence." => "Possível consequência.",
     "Number of Incidents" => "Quantidade de incidentes",
     "Report generated on" => "Relatório gerado em",
//Zabbix-Extras
     "Update Filter" => "Atualizar filtro"		
//Zabbix-CAT	
	,"Zabbix-CAT-Title" => "Zabbix-CAT - Capacidade e Tendência"
	,"Zabbix-CAT-UpdateFilter" => 'Atualizar Filtro'
	,"Zabbix-CAT-Analysis"=>"Análise"
	,"Zabbix-CAT-Projection"=>'Projeção'
	,"Zabbix-CAT-Ammount"=>'Qtd'
	,"Zabbix-CAT-Trend"=>'Tendência'
	,"Zabbix-CAT-Day" => "Dia"
	,"Zabbix-CAT-Week" => "Semana"
	,"Zabbix-CAT-Month" => "Mês"
	,"Zabbix-CAT-Year" => "Ano"
	,"Zabbix-CAT-Max"=>'Máximo'
	,"Zabbix-CAT-Avg"=>'Média'
	,"Zabbix-CAT-Min"=>'Mínimo'
	,'Zabbix-CAT-Formatting'=>'Formatação'
	,'Zabbix-CAT-Chart'=>'Gráfico'
	,'Zabbix-CAT-HistoryData'=>'Valor coletado na monitoração'
	,'Zabbix-CAT-Data'=>'Dados'
	,'Zabbix-CAT-Instant'=>'Momento'
	,'Zabbix-CAT-Value'=>'Valor'
	,'Zabbix-CAT-Type'=>'Tipo'
//Zabbix-NS
	, "Zabbix-NS-Title" => "Itens não suportados"
	, "Zabbix-NS-TitleBig" =>"Relatório de itens não suportados"
//Zabbix-SC
	, "Zabbix-SC-Title" => "Zabbix-SC - Custos de Armazenamento"
//Zabbix-ALE
	, "Zabbix-ALE-Extended" => " extendido"
//Zabbix-IS 
    , "Zabbix-IS - Ranking of Items" => "Zabbix-IS - Ranking de itens"
	)
  );

   if (array_key_exists (CWebUser::$data['locale'],$zeMessages) ){
            $zeLocale = CWebUser::$data['locale'];
    } else {
            $zeLocale = "en_GB";
    }
    //  echo $zeLocale."<br>";
    function _ze($str) {
            global $zeMessages, $zeLocale;
            if (array_key_exists($str, $zeMessages[$zeLocale])) {
    //	  var_dump($zeMessages[$zeLocale][$str]);
              return $zeMessages[$zeLocale][$str];
            } else {
                  return $str;
            }

    }
    
    function _ze2($str) {
            global $baseName;
            return _ze($baseName.$str);
    }
*/
    function exibeConteudo ($condicao,$conteudo) {
            if ($condicao) { return $conteudo;} 
            else { return array (""); }
    } 
    function newComboFilter ($query, $value, $name) {
            $cmbRange 	= new CComboBox($name, $value, 'javascript: submit();');
            $result     = DBselect($query);
            $cmbRange->additem("0", "");
            while($row_extra = DBfetch($result)){
                    $cmbRange->additem($row_extra['id'], $row_extra['description']);
            }
            return $cmbRange;
    }
    function newComboFilterArray ($array, $name, $value) {
            $cmbRange 	= new CComboBox($name, $value, 'javascript: submit();');
            $cmbRange->additem('', 'Selecione...');
            foreach ($array as $k => $v) {
                $cmbRange->additem($k, $v);
            }
            return $cmbRange;
    }
    function valorCampo ($p_query, $p_campo) {
        $retorno = "";
        $result = preparaQuery($p_query);
        while($row = DBfetch($result)){
            $retorno = $row[$p_campo];
        }
        return $retorno;
    }
    function preparaQuery ($p_query) {
        $result	= DBselect($p_query);
        if (!$result) { 
            die("Invalid query.".mysql_error()
            ); 
            return 0;
        } else { return $result; } 
    }
    function getBetweenStrings($start, $end, $str){
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }
    function debugInfo ($p_mensagem, $p_debug = false, $p_cor = "gray") {
        global $VG_DEBUG;
        if ($p_debug == true || $VG_DEBUG == true) {
                echo '<div style="background-color:'.$p_cor.';">'.$p_mensagem."</div>";
        }
    }
    function array_sort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    function quotestr($p_texto) { // Função para colocar aspas com mais segurança
        global $DB;
            return "'".($DB['TYPE'] == ZBX_DB_POSTGRESQL ? 
              pg_escape_string($p_texto) :
              mysql_real_escape_string($p_texto))."'";
            //pg_escape_string
    }
    function versaoZabbix () {
        return substr(ZABBIX_VERSION,0,3);
    }
    function checkAccessGroup ($p_groupid) {
        if (get_request($p_groupid) && !API::HostGroup()->isReadable(array($_REQUEST[$p_groupid]))) {
            access_deny();
        } else {
            $groupids = array ($_REQUEST[$p_groupid]);
        }
        return $groupids;
    }
    function checkAccessHost ($p_hostid) {
        if (get_request($p_hostid) && !API::Host()->isReadable(array($_REQUEST[$p_hostid]))) {
            access_deny();
        } else {
            $hostids = array ($_REQUEST[$p_hostid]);
            if ($hostids[0] == 0) {
                $hostids = array();
            }
        }
        return $hostids;
    }
    function _zeT ($p_msg) {
        $lang = quotestr(CWebUser::$data['lang']);
        $p_msg2 = quotestr($p_msg);
        $return = valorCampo('select tx_new from zbxe_translation where tx_original = '
                .  $p_msg2 . ' and lang = ' . $lang
                ,'tx_new');
        if ($return == "") {
            $sql = "insert into zbxe_translation values (".$lang.",".$p_msg2.",".$p_msg2.")";
            preparaQuery ($sql);
            $return = $p_msg;
        }
        return $return;
    }
?>