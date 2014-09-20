<?php

/* Used for inicial development: 
** Objective: Show a chart from data in ZE-Cat Module
** Copyright 2014 - Adail Horst - http://spinola.net.br/blog
**
** This file is part of Zabbix-Extras.
** It is not authorized any change that would mask the existence of the plugin. 
** The menu names, logos, authorship and other items identificatory plugin 
** should always be maintained.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
** If not, see http://www.gnu.org/licenses/.
**/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Zabbix-CAT Chart Builder</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="Author" content="Adail Horst" />
    <meta charset="utf-8" />
    <link class="include" rel="stylesheet" type="text/css" href="jqplot/dist/jquery.jqplot.min.css" />
  
  <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="../excanvas.js"></script><![endif]-->
    <script class="include" type="text/javascript" src="jqplot/dist/jquery.min.js"></script>
</head>
<body>
    <div id="chart1" style="width:100%; height:300px"></div>
    <div class="example-content">

<!-- Example scripts go here -->

<?php
  $tmp = $_REQUEST['p_points'];
  $tmp = explode("[;]",$tmp);
  $tmp2 = "";
  for ($i = 0; $i < count($tmp)-1; $i++) {
	  $tmp2 .= ($i == 0 ? "" : ",")."[".$tmp[$i]."]";
  }
?>
 <script type="text/javascript">
$(document).ready(function(){
  var line1 = [<?php echo $tmp2;?>];
 
  var plot2 = $.jqplot('chart1', [line1], {
		// Turns on animatino for all series in this plot.
		animate: true,
		// Will animate plot on calls to plot1.replot({resetAxes:true})
//		animateReplot: true,
		// Give the plot a title.
		title: '<?php 
$titulo = rawurldecode ($_REQUEST['p_title']); //, ENT_COMPAT);
echo $titulo;?>',
    axesDefaults: {
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
        tickOptions: {
          angle: -45
        }
    },
      seriesDefaults: {
        showMarker:false,
        pointLabels: {
          show: true,
          edgeTolerance: 5
        }},	
    axes: {
      xaxis: {
        renderer: $.jqplot.CategoryAxisRenderer,
        autoscale:false
      },
      x2axis: {
        renderer: $.jqplot.CategoryAxisRenderer
      },
      yaxis: {
        autoscale:false
      },
      y2axis: {
        autoscale:false
      }
    }
  });
});
</script>
<!-- End example scripts -->

<!-- Don't touch this! -->


    <script class="include" type="text/javascript" src="jqplot/dist/jquery.jqplot.min.js"></script>
<!-- Additional plugins go here -->

  <script class="include" type="text/javascript" src="jqplot/dist/plugins/jqplot.barRenderer.min.js"></script>
  <script class="include" type="text/javascript" src="jqplot/dist/plugins/jqplot.highlighter.min.js"></script>
  <script class="include" type="text/javascript" src="jqplot/dist/plugins/jqplot.cursor.min.js"></script> 
  <script class="include" type="text/javascript" src="jqplot/dist/plugins/jqplot.pointLabels.min.js"></script>

<!-- End additional plugins -->

<script type="text/javascript" src="jqplot/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="jqplot/dist/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="jqplot/dist/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="jqplot/dist/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="jqplot/dist/plugins/jqplot.barRenderer.min.js"></script>

	</div>	

</body>


</html>