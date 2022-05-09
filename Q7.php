<head><title>Q7</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$number = $_POST['number'];

	// echo some basic header info onto the page
	echo "<h2>Which continent has the greatest percentage of countries that have more than ".$number." of days of paid leave?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Continent </th> <th> Percentage with more than ".$number." paid leave </th> ";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['continent']."</td>";
				echo "<td>".$row['pctCountries']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH fiveDays AS (SELECT countryCode, continent
        FROM Country
        WHERE paidLeaveTotal > ?)
        SELECT percents.continent, MAX(percents.pct) AS pctCountries
        FROM (SELECT C2.continent, MAX(COUNT(greater.countryCode)/COUNT(C2.countryCode) AS pct)
        FROM fiveDays, Country AS C2
        GROUP BY C2.continent) AS percents;"
	)) {	
		$stmt->bind_param('i', $number);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			displayItems($result);
			$result->free_result();
		} else {
			echo "Execution failed";
		}
		echo "<br><br>";

	} else {
		$error = $conn->errno . ' ' . $conn->error;
		echo $error;
	}


	$conn->close();
?>
</body>