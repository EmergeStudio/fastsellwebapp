<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
	  var data = new google.visualization.DataTable();
	  data.addColumn('string', 'Year');
	  data.addColumn('number', 'Spent');
	  data.addRows([
		 ['Oct \'12', 1002.00],
		 ['Nov \'12', 960.00],
		 ['Dec \'12', 987.00],
		 ['Jan \'13', 1045.00]
	  ]);
	
		var $options = 
		{
			<?php
			if(isset($chart_height))
			{
				echo 'height	: '.$chart_height.',';
			}
			else
			{
				echo 'height	: 264,';
			}
			?>
			<?php
			if(isset($chart_width))
			{
				echo 'width		: '.$chart_width.',';
			}
			else
			{
				echo 'width		: 500,';
			}
			?>
			legend				: {position : 'none'},
			pointSize			: 12,
			chartArea			: {left : 50},
			vAxis					: {textStyle : {color : '#bebebf'}, gridlines : {color : '#ececec'}, baselineColor : '#ececec'},
			hAxis					: {textStyle : {color : '#bebebf'}, gridlines : {color : '#ececec'}},
			backgroundColor	: 'none'
		};
	
	  var chart = new google.visualization.AreaChart(document.getElementById('chart_spendingOverTime'));
	  chart.draw(data, $options);
	}
</script>

<?php
// Display the chart
echo full_div('', '', '', 'chart_spendingOverTime');
?>