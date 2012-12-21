<html>
<head>
	<meta name="eiji" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>eiji</title>

	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load('visualization', '1', {packages: ['corechart']});
	</script>
	<script type="text/javascript">
	
		function graficaTemperatura() {
			var data = google.visualization.arrayToDataTable([
				['x', 'temp ºC']
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
				width: 700, height: 400,
				vAxis: {minValue: 37, maxValue: 43}}
			);
    }
      

		google.setOnLoadCallback(graficaTemperatura);
		
	</script>
</head>
<body>
	<div id="visualizacionTemperatura" style="width: 700px; height: 400px;"></div>
</body>
</html>
