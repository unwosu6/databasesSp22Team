<head><title>Q2</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];

	// echo some basic header info onto the page
	echo "<h2>What is the most well-paid job sector in ".$year." for each country?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Job Sector </th> ";
			echo "<th> Monthly Earnings </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['sectorID']."</td>";
				echo "<td>".$row['monthlyEarnings']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"SELECT W.countryCode, W.sectorID, W.sex, W.monthlyEarnings
        FROM WorksIn AS W, WorksIn AS Wmax
        WHERE W.countryCode = Wmax.countryCode 
        AND W.year = Wmax.year AND W.year = ? 
        AND Wmax.sectorID != 'Total' 
        GROUP BY W.countryCode, W.monthlyEarnings, W.sectorID, W.sex
        HAVING W.monthlyEarnings = max(Wmax.monthlyEarnings)
        ORDER BY W.monthlyEarnings DESC;"
	)) {	
		$stmt->bind_param('d', $year);

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