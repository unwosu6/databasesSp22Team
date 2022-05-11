<?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$number = $_POST['number'];

	$dataPoints = array();

	echo "<h2>What percentage of countries have more than ".$number." of days of paid leave on each continent?</h2><br>";

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sqlCode = "WITH NumCountry AS (SELECT continent, count(*) AS numCountries
	FROM Country
	WHERE paidLeaveTotal > ?
	GROUP BY continent),
	Totals AS (SELECT continent, count(*) AS totalCountries
	FROM Country
	GROUP BY continent)
	SELECT N.continent, N.numCountries/T.totalCountries*100 AS pct
	FROM NumCountry AS N JOIN Totals AS T ON N.continent = T.continent;";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('d', $number);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows == 0) {
				echo "There are no records with a number of days above ".$number.".";
			} else{
				foreach($result as $row){
					array_push($dataPoints, array( "label"=> $row["continent"], "y"=> $row["pct"]));
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
			text: "Percent of Countries on Each Continent"
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