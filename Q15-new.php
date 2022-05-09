<?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$year = $_POST['year'];
	$factor1 = $_POST['factor1'];
	$factor2 = $_POST['factor2'];
	$factorWord1 = $_POST['factorWord1'];
	$factorWord2 = $_POST['factorWord2'];

	$dataPoints1 = array();
	$dataPoints2 = array();

	echo "<h2>In ".$year.", which countries have the highest ".$factorWord1." in their continent and what is their ".$factorWord2."?</h2><br>";

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sqlCode = "WITH AnnualCountryStatsWithContinent AS(
        SELECT ACS.countryCode, C.countryName, ACS.".$factor1." AS factor1, ACS.".$factor2." AS factor2, ACS.year, C.continent
        FROM AnnualCountryStats AS ACS JOIN Country AS C ON ACS.countryCode = C.countryCode)
    SELECT ACS.countryCode, ACS.countryName, ACS.continent, ACS.factor1, ACS.factor2 
    FROM AnnualCountryStatsWithContinent AS ACS, AnnualCountryStatsWithContinent AS ACSmax
    WHERE ACS.year = ? AND ACS.year = ACSmax.year AND ACS.continent = ACSmax.continent
    GROUP BY ACS.continent, ACS.countryCode, ACS.factor1, ACS.factor2
    HAVING ACS.factor1 = max(ACSmax.factor1)
    ORDER BY ACS.factor1 DESC;";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('d', $year);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows == 0) {
				echo "There is no data for ".$countryName.".";
			} else{
				foreach($result as $row){
					array_push($dataPoints1, array( "label"=> $row["countryName"]." (".$row["continent"].")", "y"=> $row["factor1"]));
					array_push($dataPoints2, array( "label"=> $row["countryName"]." (".$row["continent"].")", "y"=> $row["factor2"]));

				}
			}
			
			$result->free_result();
		} else {
			echo "Execution failed";
		}
		echo "<br><br>";

	} else {
		$error = $conn->errno . ' ' . $conn->error;
		echo $error;
	}
	
	//close the connection opened by open.php since we no longer need access to dbase
	$conn->close();
?>


<html>
<head>
<script>
window.onload = function () { 
	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		exportEnabled: true,
		theme: "light1", // "light1", "light2", "dark1", "dark2"
		title:{
			text: "<?php echo "Countries by Continent with the Highest ".$factorWord1; ?>"
		},
		axisX: {
		},
		axisY2: {
		},
		toolTip: {
			shared: true
		},
		legend: {
			cursor: "pointer",
			verticalAlign: "top",
			horizontalAlign: "center",
			dockInsidePlotArea: true
		},
		data: [{
			type: "bar", //change type to column, bar, line, area, pie, etc  
			showInLegend: true,
			name: "<?php echo $factorWord1; ?>",
			dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		},
		{
			type: "bar", //change type to column, bar, line, area, pie, etc  
			showInLegend: true,
			name: "<?php echo $factorWord2; ?>",
			dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render(); 
}
</script>
</head>
<body>
	<div id="chartContainer" style="height: 400px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
