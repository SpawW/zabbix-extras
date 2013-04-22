<?php
  global $zeMessages, $zeLocale, $baseName;
  $zeMessages = Array (
	"en_GB" => Array (
//Zabbix-Extras
     "Zabbix-Extras-AtualizarFiltro" => "Update Filter"		
//Zabbix-CAT	
	,"Zabbix-CAT-Title" => "Zabbix-CAT - Capacity and Trend"
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
//Zabbix-Extras
     "Zabbix-Extras-AtualizarFiltro" => "Atualizar filtro"		
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
	, "Zabbix-SC-WelcomeMessage" => "Informe os parâmetros para a pesquisa!"
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
    function exibeConteudo ($condicao,$conteudo) {
            if ($condicao) { return $conteudo;} 
            else { return array (""); }
    }
    function newComboFilter ($query, $value, $name) {
            $cmbRange 		= new CComboBox($name, $value, 'javascript: submit();');
            $result			= DBselect($query);
            $cmbRange->additem("0", "");
            while($row_extra = DBfetch($result)){
                    $cmbRange->additem($row_extra['id'], $row_extra['description']);
            }
            return $cmbRange;
    }
?>