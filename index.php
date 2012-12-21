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
      
			new google.visualization.LineChart(document.getElementById('visualizacionTemperatura')).
				draw(data, {curveType: "function",
				width: 700, height: 400,
				vAxis: {minValue: 37, maxValue: 43}}
			);
    }

	    
		function graficoEspacioUSB() {
			<?php 
				$commandocupado = "df -h |grep usb|awk '{printf(\"%d\",$3);}'";
				$arrayocupado = explode("\n", shell_exec($commandocupado));
				$ocupado = $arrayocupado[0];
				
				$commandlibre = "df -h |grep usb|awk '{printf(\"%d\",$4);}'";
				$arraylibre = explode("\n", shell_exec($commandlibre));
				$libre = $arraylibre[0];
			?>
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Topping');
	    data.addColumn('number', 'Slices');
	    data.addRows([
				['Ocupado', <?php echo $ocupado?>],
        ['Libre', <?php echo $libre?>]
      ]);
	    var options = {'title':'Disco USB', 'width':400, 'height':300};
			var chart = new google.visualization.PieChart(document.getElementById('visualizacionEspacioUSB'));
      chart.draw(data, options);
				
		}      

		google.setOnLoadCallback(graficaTemperatura);
		google.setOnLoadCallback(graficoEspacioUSB);
		
	</script>
</head>
<body>
	<div id="visualizacionTemperatura" style="width: 700px; height: 400px;"></div>
	<div id="visualizacionEspacioUSB" ></div>
	
</body>
</html>
