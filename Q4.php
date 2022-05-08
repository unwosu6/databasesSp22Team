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
	echo "<h2>In ".$year." what was the life satisfaction for countries with the top 3 GDPs on the continent of ".$continent."? </h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> GDP per Capita </th> ";
			echo "<th> Life Satisfaction </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['GDPperCap']."</td>";
				echo "<td>".$row['lifeSatisfaction']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"SELECT C.countryName, ACS.GDPperCap, ACS.lifeSatisfaction ".
		"FROM AnnualCountryStats AS ACS JOIN Country AS C ".
		"ON ACS.countryCode = C.countryCode ".
		"WHERE ACS.year = ? AND C.continent = ? AND ACS.lifeSatisfaction IS NOT NULL ".
		"ORDER BY ACS.GDPperCap DESC LIMIT 3;"
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
