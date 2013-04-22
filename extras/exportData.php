<?php include_once "./utils.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../css.css" />
<link rel="stylesheet" type="text/css" href="../styles/themes/originalblue/main.css" />
<script type="text/javascript">
	if (jQuery(window).width() < 1024) {
		document.write('<link rel="stylesheet" type="text/css" href="../styles/handheld.css" />');
	}
</script>
<title>Export de dados para recovery de node</title>
</head>
<body>
<?php
	conectaBD(); // Conectando ao BD
?>
<div id="tabs" class="min-width ui-tabs ui-widget ui-widget-content ui-corner-all widget">
<table id="portletGrpApl" class="tableinfo" cellspacing="1" cellpadding="3">
	<tr><th id="textovertical">&nbsp;</th>
<?php
    $VG_NOVO_ID = ($_REQUEST['p_nodeid_novo'] == "" ? $_REQUEST['p_node'] : $_REQUEST['p_nodeid_novo']);
    
    function exportaTabela ($p_tabela, $p_nodeid) {
        global $VG_NOVO_ID;
        $TABELA = $p_tabela;
        $campoID = valorCampo('select field_name as id from ids where nodeid = '.$p_nodeid
           .' and table_name = "'.$p_tabela.'"','id');
        if ( $campoID == "" ) {
            echo "não foram encontrados registros !";
            exit;
        }
        $script = "";
        $ultimoID = 0;
        $min = $p_nodeid.$p_nodeid.'00000000000';
        $max = $p_nodeid.$p_nodeid.'99999999999';

        $queryApl = 'select * from '.$TABELA. " where $campoID BETWEEN $min and $max ";
        debugInfo ($queryApl);
	$resultApl = arraySelect($queryApl);
        $cont = 0;
	foreach ($resultApl as &$row) {
           $campos = "";
           $valores = "";
           foreach($row as $key => $value) {               
               $campos  .= ($campos == "" ? "" : ", ") . "`$key`";
               if ($key == $campoID) {
                  $value = $VG_NOVO_ID.$VG_NOVO_ID.substr($value,6,100);
               }
               $valores .= ($valores == "" ? "" : ", ") . "'".addslashes($value)."'";
               $count++;
           }
           $ultimoID = ($ultimoID > $row[$campoID] ? $ultimoID : $VG_NOVO_ID.$VG_NOVO_ID.substr($row[$campoID],6,100));
           $linha   = "INSERT INTO $TABELA ($campos) VALUES ($valores);\n";
           $script .= $linha;
	}
        $script .= "UPDATE ids set nextid = ". $ultimoID . " where nodeid = $p_nodeid and table_name = '$p_tabela';\n";
        return $script;
    }
        debugInfo("Usuarios...",true,'blue');
        debugInfo(str_replace("\n","<BR>",exportaTabela('users',$_REQUEST['p_node'])),true);
        debugInfo("WebCheck...",true,'blue');
        debugInfo(str_replace("\n","<BR>",exportaTabela('httptest',$_REQUEST['p_node'])),true);
        debugInfo("WebCheck - Steps...",true,'blue');
        debugInfo(str_replace("\n","<BR>",exportaTabela('httpstep',$_REQUEST['p_node'])),true);
        debugInfo("WebCheck - Mapeamento entre webcheck e item ...",true,'blue');
        debugInfo(str_replace("\n","<BR>",exportaTabela('httptestitem',$_REQUEST['p_node'])),true);
        debugInfo("WebCheck - Mapeamento entre webcheck-Step e item ...",true,'blue');
        debugInfo(str_replace("\n","<BR>",exportaTabela('httpstepitem',$_REQUEST['p_node'])),true);
?>    
    </tr>
</table>
</div>
</body>
</html>