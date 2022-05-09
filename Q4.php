<head><title>Q4</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];
	$lower = $_POST['lower'];
	$upper = $_POST['upper'];

	// echo some basic header info onto the page
	echo "<h2>In ".$year.", what was the average number of days of paid leave for countries with a life satisfaction above ".$lower." and below ".$upper."? </h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			global $year, $upper, $lower;
			echo "There is no record for the year ".$year." with countries that have a life satisfaction above ".$lower." and below ".$upper.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Total Number of Days of Paid Leave </th> ";
			echo "<th> Life Satisfaction </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['paidLeaveTotal']."</td>";
				echo "<td>".$row['lifeSatisfaction']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"SELECT avg(C.paidLeaveTotal) AS averagePaidLeave ".
		"FROM Country AS C JOIN AnnualCountryStats AS ACS ".
		"ON C.countryCode = ACS.countryCode ".
		"WHERE ACS.year = ? AND ACS.lifeSatisfaction > ? AND ACS.lifeSatisfaction < ? AND C.paidLeaveTotal IS NOT NULL;"
	)) {	
		$stmt->bind_param('ddd', $year, $lower, $upper);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				if ($row['averagePaidLeave'] != null) {
					echo "The average number of days: ".$row['averagePaidLeave']."<br>";
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

	if ($stmt = $conn->prepare(
		"SELECT C.countryName, C.paidLeaveTotal, ACS.lifeSatisfaction
		FROM Country AS C JOIN AnnualCountryStats AS ACS
		ON C.countryCode = ACS.countryCode
		WHERE ACS.year = ? AND ACS.lifeSatisfaction > ? AND ACS.lifeSatisfaction < ? AND C.paidLeaveTotal IS NOT NULL
		ORDER BY C.paidLeaveTotal DESC;"
	)) {	
		$stmt->bind_param('ddd', $year, $lower, $upper);

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
