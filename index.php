<?php 
	// Registro de visitas
	$pathRegistro = ("/var/log/eiji.log");
	
	$msg = array(
			date("ymdGis",time())
			, $_SERVER['REMOTE_ADDR']
			, $_SERVER['HTTP_REFERER']
			,$_SERVER['HTTP_ACCEPT_LANGUAGE']
			,"\n" 
		);
	
	
	$fr= fopen($pathRegistro, "a");
	fputs($fr ,
		implode(";", $msg)
	);
	fclose($fr);
?>

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
				['Used', <?php echo $ocupado?>],
        ['Available', <?php echo $libre?>]
      ]);
	    var options = {'title':'USB HDD', 'width':400, 'height':300};
			var chart = new google.visualization.PieChart(document.getElementById('visualizacionEspacioUSB'));
      chart.draw(data, options);
				
		}      
		<?php 
			// http://arpaneting.es/2007/04/28/mostrando-el-uptime-con-php/
			function format_uptime($seconds) {
				$secs = intval($seconds % 60);
				$mins = intval($seconds / 60 % 60);
				$hours = intval($seconds / 3600 % 24);
				$days = intval($seconds / 86400);
				$uptimeString = "";
				if ($days > 0) {
					$uptimeString .= $days;
					$uptimeString .= (($days == 1) ? " day" : " days");
				}
				
				if ($hours > 0) {
					$uptimeString .= (($days > 0) ? ", " : "") . $hours;
					$uptimeString .= (($hours == 1) ? " hour" : " hours");
				}
				
				if ($mins > 0) {
					$uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
					$uptimeString .= (($mins == 1) ? " minute" : " minutes");
				}
				
				if ($secs > 0) {
				
					$uptimeString .= (($days > 0 || $hours > 0 || $mins > 0) ? ", " : "") . $secs;
					$uptimeString .= (($secs == 1) ? " second" : " seconds");
				
				}
				return $uptimeString;
			}
		?>
		function uptimeEiji() {
			document.getElementById('visualizacionUptime').innerHTML = "uptime: <?php 
					$commandSegundosUptime = "cat /proc/uptime |awk '{printf(\"%d\",$1);}'";
					$segundosUptime = explode("\n", shell_exec($commandSegundosUptime));
					print format_uptime($segundosUptime[0]);
			?>"
		} 
		
		google.setOnLoadCallback(graficaTemperatura);
		google.setOnLoadCallback(graficoEspacioUSB);
		google.setOnLoadCallback(uptimeEiji);
	</script>
</head>
<body>
	<div id="visualizacionUptime" style="width: 700px; height: 40px;"></div>
	<div id="visualizacionTemperatura" style="width: 700px; height: 400px;"></div>
	<div id="visualizacionEspacioUSB" ></div>
	
</body>
</html>
