<?php include_once "serpro/php/utils.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Gerador de plant&otilde;es</title>
<style>
  .tSemana { background-color:#999; width:100px; height:100px; }
</style>
</head>

<body>
<?php 
$vDebug = $_REQUEST['p_debug'] == "S";
function calendar ($date, $p_plan) {
    global $vDebug;
	global $sql;
	global $plan;
	  // Array com o valor em pontos de cada dia --------------------
	$pontoDia = array(1,1,1,1,1,5,0);
	//	  if ($date == null) $date = getDate();
	$day = $date['mday'];
	$month = $date['mon'];
	$month_name = $date['month'];
	$year = $date['year'];
//	var_dump($date);
	
	$this_month = getDate(mktime(0,0,0,$month,1,$year));
	$next_month = getDate(mktime(0,0,0,$month+1,1,$year));
	
	$first_week_day = $this_month['wday'];
	$days_in_this_month = round(($next_month[0] - $this_month[0])/(60*60*24));
	
	$calendar_html =  "\n<table border='0' style='background-color:666699; color:ffffff;' >";
	$calendar_html .= "\n<tr><th class='tSemana'>Domingo</th><th  class='tSemana'>Segunda</th><th  class='tSemana'>Terça</th><th  class='tSemana'>Quarta</th><th  class='tSemana'>Quinta</th><th  class='tSemana'>Sexta</th><th  class='tSemana'>Sábado</th><tr>";
	$calendar_html .= "\n<tr><td colspan='7' align='center' style='background-color:9999cc; color:000000;'></td></tr><tr>";
	for ($week_day = 0; $week_day < $first_week_day; $week_day++) {
	  $calendar_html .= "<td style='background-color:9999cc; color:000000;' ></td> ";
	}
	$week_day = $first_week_day;
	for ($day_counter = 1; $day_counter <= $days_in_this_month; $day_counter++) {
	  $week_day %= 7;
	  if ($week_day == 0) $calendar_html .= "</tr>\n<tr>";
	  $menor = $indice = 100;
	//		  $day_counter .= "<br>".$p_plan[$indice][1];
	  for ($i = 0; $i < count($p_plan); $i++) {
	//$row['id_plantonista'],$row['nm_plantonista'],$row['st_dias'],0)
		$statusDia = $p_plan[$i][2][$week_day];			
		if ($statusDia == "S") {
			$tmp11 = $statusDia;
			if ($p_plan[$i][3] <= $menor) {
				$menor 	= $p_plan[$i][3];
				$indice = $i;
			}
		}
	  }
	  if ($indice == 100) { $indice = 0; }
	  // Pontuação ganha pelo dia de plantao ------------------------
	  $p_plan[$indice][3] += ($week_day == 6 || $week_day == 0 ? 5 : 1);//$pontoDia[$week_day];//
	  if ("NNNNNNS" == $p_plan[$indice][2]) {
		  $p_plan[$indice][3] += 5;
	  }
	  $descricao = $day_counter."<br>".$p_plan[$indice][1].
	  ($vDebug ? "<br>Pontos=".$p_plan[$indice][3]."<br>[{$indice}]".$tmp11." ".$week_day 
	  : "");
	  if ($p_plan[$indice][0] >= 0) {
	  	$sql .= "INSERT INTO `serpro_form`.`plantoes` (`dt_plantao` ,`userid` ,`userid_substituto` ,`st_status` ,`tx_motivo`) VALUES ('"
	  . $year."-".$month."-".$day_counter."', '".$p_plan[$indice][0]."', '', 'S', '');\n";
	  }
	  if ($day == $day_counter)
		$calendar_html .= "<td align='center' ".($week_day == 0 || $week_day == 6 ? " class='tSemana' " : "")."><b> ".$descricao."</b></td>";
	  else 
		$calendar_html .= "<td align='center'  ".($week_day == 0 || $week_day == 6 ? " class='tSemana' " : " style='background-color:9999cc; color:000000;'").">&nbsp;".$descricao."</td>";
	  $week_day++;
	}
	$calendar_html .="\n</tr></table>";
	$plan = $p_plan;
	return ($calendar_html);
}
$query = "Select * from (SELECT pri.userid, pri.nm_plantonista, res.st_dias FROM `plantonistas` pri, `restricoes` res
 WHERE res.userid = pri.userid and pri.st_ativo = 'S'
   AND SYSDATE() between res.dt_inicio and res.dt_final
   ) a
   order by LENGTH(`st_dias`)-LENGTH(REPLACE(`st_dias`,'S', '')) +1 DESC, nm_plantonista DESC
";
$result = preparaQuery (sprintf($query, mysql_real_escape_string($_REQUEST[''])));
$plan = Array (Array(-1,'Ninguem','SSSSSSS',100));
	global $sql;
$sql = "";
while ($row = @mysql_fetch_assoc($result)){ 
	$plan[count($plan)] = Array($row['userid'],$row['nm_plantonista'],$row['st_dias'],0);
}
?>
<?php //echo calendar(getDate(mktime(0,0,0,9,1,2012)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,10,1,2012)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,11,1,2012)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,12,1,2012)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,1,1,2013)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,2,1,2013)),$plan); ?>
<?php echo calendar(getDate(mktime(0,0,0,3,1,2013)),$plan); ?>

<textarea cols="120" rows="10">
<?php 
echo $sql;
?>
</textarea>

</body>
</html>