<?php include_once "./utils.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if IE]> 
   <style>
      .rotate_text
      {
         writing-mode: tb-rl;
         filter: flipH() flipV();
      }
   </style>
<![endif]-->
<!--[if !IE]><!-->
   <style> 
      .rotate_text
      {
         -moz-transform:rotate(-90deg); 
         -moz-transform-origin: top left;
         -webkit-transform: rotate(-90deg);
         -webkit-transform-origin: top left;
         -o-transform: rotate(-90deg);
         -o-transform-origin:  top left;
          position:relative;
         top:20px;
      }
   </style>
<!--<![endif]-->

   <style>  
      table
      {
         border: 0px solid black;
         table-layout: fixed;
         width: 59px; /*Table width must be set or it wont resize the cells*/
      }
      th, td 
      {
          border: 1px solid black;
          width: 23px;
      }
      .rotated_cell
      {
         height:130px;
         vertical-align:bottom;
		 font-size:10px;
		 font-family:Verdana, Geneva, sans-serif;
      }
   </style>
<link rel="stylesheet" type="text/css" href="../css.css" />
<link rel="stylesheet" type="text/css" href="../styles/themes/originalblue/main.css" />
<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="../styles/ie.css" />
<![endif]-->
<script type="text/javascript">
	if (jQuery(window).width() < 1024) {
		document.write('<link rel="stylesheet" type="text/css" href="../styles/handheld.css" />');
	}
</script>
<title>Portlet de status por grupo / aplicação</title>
</head>
<body>
<?php

	conectaBD(); // Conectando ao BD
	
?>
<div id="tabs" class="min-width ui-tabs ui-widget ui-widget-content ui-corner-all widget">
<table id="portletGrpApl" class="tableinfo" cellspacing="1" cellpadding="3">
	<tr><th id="textovertical">&nbsp;</th>
<?php
	// Construir linha de titulos --------------------------------------------------------------------------------
	$CondicaoApplic = "";
	if($_POST['f_ApplicationId'] != "")
	{
		$CondicaoApplic = " WHERE pri.applicationid = ".$_POST['f_ApplicationId']."";
	}

	$queryApl = '
 SELECT DISTINCT pri.name
	   FROM applications pri
 INNER JOIN items_applications iapl
		 ON iapl.applicationid = pri.applicationid
 INNER JOIN items ite
		 ON ite.itemid = iapl.itemid
 INNER JOIN hosts hos
		 ON hos.hostid = ite.hostid
		AND hos.status <> 3
 INNER JOIN hosts_groups hgr
		 ON hgr.hostid = hos.hostid
		 '.$CondicaoApplic.'
';
	$resultApl = arraySelect($queryApl);
	foreach ($resultApl as &$row) {
		echo '<th class="rotated_cell"><div class="rotate_text">'.$row['name'].'</div></th>';
	}
?>    
    </tr>
<?php	
	//
	$severity = array ('not_classified','information','warning','average','high','disaster');
	// Localizar todos os grupos ---------------------------------------------------------------------------------
	$CondicaoGroup = "";
	if($_POST['f_GroupId'] != "")
	{
		$CondicaoGroup = " WHERE pri.groupid = ".$_POST['f_GroupId']."";
	}
	
	$query = '
SELECT DISTINCT pri.*
		   FROM groups pri		   
     INNER JOIN hosts_groups hgr
             ON hgr.groupid = pri.groupid
     INNER JOIN hosts hos
             ON hos.hostid = hgr.hostid
            AND hos.status <> 3
     INNER JOIN items ite
             ON ite.hostid = hgr.hostid
	 INNER JOIN functions fun
   		 ON fun.itemid = ite.itemid
	 INNER JOIN triggers tri
   		 ON tri.triggerid = fun.triggerid
		 '.$CondicaoGroup.'
group by pri.name
';
#echo $query;
	$resultGrp = arraySelect($query);
	$padraoLinha = "";
	foreach ($resultGrp as &$rowGrp) {
		$padraoLinha = ($padraoLinha == "even_row" ? "old_row" : "even_row");
		echo "<tr class='{$padraoLinha}' ><td  >{$rowGrp['name']}</td>";
		foreach ($resultApl as &$rowApl) {
			$statusGrpApp = arraySelect('
 SELECT DISTINCT ite.itemid, tri.triggerid, tri.value as triValue, tri.priority as triPriority
	   FROM applications pri
 INNER JOIN items_applications iapl
		 ON iapl.applicationid = pri.applicationid
 INNER JOIN items ite
		 ON ite.itemid = iapl.itemid
 INNER JOIN hosts hos
		 ON hos.hostid = ite.hostid
		AND hos.status <> 3
 INNER JOIN hosts_groups hgr
		 ON hgr.hostid = hos.hostid
		AND hgr.groupid = '.$rowGrp['groupid'].'
 INNER JOIN functions fun
   		 ON fun.itemid = ite.itemid
 INNER JOIN triggers tri
   		 ON tri.triggerid = fun.triggerid

 where pri.name = "'.$rowApl['name'].'"
 
 ');
 			$status = "";
			$totalTriggers = count($statusGrpApp);
			$classe = "#13F813";
			$totalAtiva = 0;
			if ($totalTriggers > 0) {
				$maximo = -1;
				foreach ($statusGrpApp as &$rowTri) {
					if ($rowTri['triValue'] == 1) {
						$maximo = ( $maximo < $rowTri['triPriority'] ? $rowTri['triPriority'] : $maximo );
						$totalAtiva++;
//						echo "[".$rowTri['triPriority']."-".$maximo."]";
					}
				}
				if ($maximo > -1) {
					// Varrer todas as triggers, identificar as ativas e a maior severidade delas
					$classe = valorCampo('select severity_color_'.$maximo.' as id from config ','id');
//					$classe = $severity[$maximo];
				} 
 				$status = '&nbsp;'. ( $totalAtiva > 0 ? $totalAtiva : "");//. " - " . $statusGrpApp[0]['triggerid'];
			}
 			echo "<td style='background-color:{$classe}; align=\'center\';'>".$status."</td>";
			
		}
		// Pesquisar em cada linha de titulo a existência de trigger ativa (evento na verdade) -----------------------
				
		// Caso exista, colorir a celula com a trigger mais alta (depois virá a imagem) ------------------------------
		echo "</tr>";
	}
?>
</table>
</div>
</body>
</html>