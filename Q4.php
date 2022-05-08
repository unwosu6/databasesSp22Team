<head><title>Q4</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$lower = $_POST['lower'];
	$upper = $_POST['upper'];

	// echo some basic header info onto the page
	echo "<h2>What is the average number of days of paid leave for countries with a life satisfaction above ".$lower." and below ".$upper."? </h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
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
		"WHERE ACS.year = 2016 AND ACS.lifeSatisfaction > ? AND ACS.lifeSatisfaction < ?;"
	)) {	
		$stmt->bind_param('dd', $lower, $upper);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				echo "The average number of days: ".$row['averagePaidLeave']."<br>";
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
		"SELECT C.countryName, C.paidLeaveTotal, ACS.lifeSatisfaction ".
		"FROM Country AS C JOIN AnnualCountryStats AS ACS ".
		"ON C.countryCode = ACS.countryCode ".
		"WHERE ACS.year = 2016 AND ACS.lifeSatisfaction > ? AND ACS.lifeSatisfaction < ?".
		"ORDER BY C.paidLeaveTotal DESC;"
	)) {	
		$stmt->bind_param('dd', $lower, $upper);

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
