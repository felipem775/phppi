<html>
<head>
	<title>eiji</title>

	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load('visualization', '1', {packages: ['corechart']});
	</script>
	<script type="text/javascript">
	
		function graficaTemperatura() {
			var data = google.visualization.arrayToDataTable([
				['x', 'temp C']
				<?
					$filename = "/var/log/temperatura.log";
					$command = "tail -144 $filename";
					$lines = explode("\n", shell_exec($command));
					foreach ($lines as &$line) {
						if ($line != "") {
							list($fecha,$temp) = split(" ", $line);
						}
					
						if ($fecha != "") { 
							$fechaStr = date('G:i:s', ($fecha));
							print ",['$fechaStr',$temp]"; 
						}
							
					}	
				?>
			]);
      
		        // Create and draw the visualization.
			new google.visualization.LineChart(document.getElementById('visualizacionTemperatura')).
				draw(data, {curveType: "function",
				width: 900, height: 500,
				vAxis: {minValue: 36, maxValue: 44}}
			);
    }
      

		google.setOnLoadCallback(graficaTemperatura);
		
	</script>
</head>
<body>
	Div para gráfica temperatura
	<div id="visualizacionTemperatura" style="width: 900px; height: 500px;"></div>
</body>
</html>
