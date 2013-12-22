<!DOCTYPE html>

<html>
<head>
	<title>Zabbix-CAT Chart Builder</title>
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
		title: '<?php echo $_REQUEST['p_title'];?>',
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