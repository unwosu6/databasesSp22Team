<?php
	//open a connection to dbase server 
	include 'open.php';

	$dataPoints = array();

	echo "<h2>What is the average number of days of annual paid leave for each continent?</h2><br>";

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sqlCode = "SELECT continent, avg(paidLeaveTotal) AS avgPaidLeave
				FROM Country
				WHERE Continent IS NOT NULL
				GROUP BY continent;";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('s', $country);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows == 0) {
				echo "There is no data for ".$countryName.".";
			} else{
				foreach($result as $row){
					array_push($dataPoints, array( "label"=> $row["continent"], "y"=> $row["avgPaidLeave"]));
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
			text: "Average Number of Days of Paid Leave By Continent"
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