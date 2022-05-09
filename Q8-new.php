<?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$number = $_POST['number'];
    $factor = $_POST['factor'];
    $factorWord = $_POST['factorWord'];

	$dataPoints = array();

	echo "<h2>What is the average ".$factorWord." of a country with a life satisfaction above ".$number." for each year? </h2><br>";

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sqlCode = "SELECT ADS.year, avg(ADS.".$factor.") AS average 
	FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS 
	ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode 
	WHERE ACS.lifeSatisfaction > ? 
	GROUP BY ADS.year;";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('d', $number);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows == 0) {
				global $factor, $number;
				echo "There is no data for the factor ".$factor." where the life satisfaction is above ".$number.".";
			} else{
				foreach($result as $row){
					array_push($dataPoints, array( "label"=> $row["year"], "y"=> $row["average"]));
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
			text: "Average Over Time"
		},
		data: [{
			type: "line", //change type to column, bar, line, area, pie, etc  
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
