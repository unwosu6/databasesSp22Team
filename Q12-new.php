<?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$country = $_POST['country'];

	$query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

	$dataPoints = array();

	echo "<h1>What is the gap in average monthly earnings between males and females in ".$countryName."?</h1>";

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sqlCode = "SELECT C.countryName, W.countryCode, W.year, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
    FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
    ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
    WHERE W.sectorId = 'Total' AND W.countryCode = ? AND abs(W.monthlyEarnings - W2.monthlyEarnings) IS NOT NULL;";

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
					array_push($dataPoints, array( "label"=> $row["year"], "y"=> $row["femalePay"]));
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
			text: "Average Monthly Pay by Year"
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
