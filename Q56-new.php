<?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$dataPoints = array();

	$year = $_POST['year'];
	$direction = $_POST['direction'];
	$country = $_POST['country'];

	$query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];
	$sqlCode = "";
	
	if ($direction == "higher") {
        $sqlCode = "SELECT C.continent, count(*) AS numCountries
		FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
		WHERE year = ?
		AND lifeSatisfaction >= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = ? AND countryCode = ?)
		GROUP BY C.continent;";
    } elseif ($direction == "lower") {
        $sqlCode = "SELECT C.continent, count(*) AS numCountries
		FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
		WHERE year = ?
		AND lifeSatisfaction <= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = ? AND countryCode = ?)
		GROUP BY C.continent;";
    }

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('dds', $year, $year, $country);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			foreach($result as $row){
				array_push($dataPoints, array( "label"=> $row["continent"], "y"=> $row["numCountries"]));
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
			text: "Number of Countries Per Continent"
		},
		data: [{
			type: "bar", //change type to column, bar, line, area, pie, etc  
			dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
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
