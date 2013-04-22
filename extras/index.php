<?php include_once "./utils.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
<form action="zabbix-portlet-grp-apl.php" name="formFiltro" method="post">
<div id="tabs" class="min-width ui-tabs ui-widget ui-widget-content ui-corner-all widget">
<table id="portletGrpApl" class="tableinfo" cellspacing="1" cellpadding="3" border="1">
<tr><td colspan="2"><h2>Escolha os filtros:</h2></td></tr>

<?php	
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
group by pri.name
ORDER BY pri.name
';
	$resultGrp = arraySelect($query);

	echo "<tr><td>Host Group: </td><td>
	<select name='f_GroupId'>
	<option value='' selected></option>";
	foreach ($resultGrp as &$rowGrp) {
		echo "<option value='".$rowGrp['groupid']."' >".$rowGrp['name']."</option>";
	}
		echo "</select></td></tr>";
?>
<?php	
$queryApl = '
 SELECT DISTINCT pri.name,
 				 pri.applicationid
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
		 ORDER BY pri.name
';

	$resultApl = arraySelect($queryApl);

	echo "<tr><td>Application: </td><td>
	<select name='f_ApplicationId'>
	<option value='' selected></option>";
	foreach ($resultApl as &$row){
		echo "<option value='".$row['applicationid']."' >".$row['name']."</option>";
	}
		echo "</select></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><input type='submit' value='Buscar'></td></tr>";
?>
</table>
</div>
</form>
</body>
</html>