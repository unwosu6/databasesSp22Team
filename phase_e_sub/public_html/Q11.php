<head><title>Q2</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$start = $_POST['start'];
    $end = $_POST['end'];
    $sex = $_POST['sex'];
    $country = $_POST['country'];

	$query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

	echo "<h2>What sectors in ".$countryName." had a growth in monthly earnings from ".$start." to ".$end." for ".$sex."s?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			global $start, $end, $countryName;
			echo "There are no records for monthly earnings in ".$countryName." in either the year ".$start." or ".$end.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Job Sector </th> <th> Growth </th> ";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['sectorID']."</td>";
				echo "<td>".$row['growth']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH FirstYear AS (
			SELECT sectorID, monthlyEarnings 
			FROM WorksIn
			WHERE year = ? AND sex = ? AND countryCode = ?),
			SecondYear AS (
			SELECT sectorID, monthlyEarnings 
			FROM WorksIn
			WHERE year = ? AND sex = ? AND countryCode = ?)
			SELECT F.sectorID, S.monthlyEarnings - F.monthlyEarnings AS growth
			FROM FirstYear AS F JOIN SecondYear AS S ON F.sectorID = S.sectorID
			WHERE S.monthlyEarnings - F.monthlyEarnings > 0 AND S.sectorID != 'Total'
			ORDER BY S.monthlyEarnings - F.monthlyEarnings DESC;"
	)) {	
		$stmt->bind_param('dssdss', $start, $sex, $country, $end, $sex, $country);

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