<head><title>Q13</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
    $year = $_POST['year'];
	$factor = $_POST['factor'];

    $factorWord = "NONE GIVEN";

    if ($factor == "pctUsingInternet") {
        $factorWord = "Percent of the Population Using the Internet";
    } elseif ($factor == "lifeSatisfaction") {
        $factorWord = "Life Satisfaction";
    } elseif ($factor == "population") {
        $factorWord = "Population";
    } elseif ($factor == "fertRate") {
        $factorWord = "Fertility Rate";
    } 

	// echo some basic header info onto the page
	echo "<h2>In ".$year.", how many countries are in both the top 50 for ".$factorWord." and GDP per capita?</h2><br>";

	function displayItems($res) {
		global $factorWord, $year;
		if ($res->num_rows == 0) {
			echo "There were no records found for the year ".$year.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> ".$factorWord." </th> ";
			echo "<th> GDP Per Capita </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['factor']."</td>";
				echo "<td>".$row['GDPperCap']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH GDP AS (
            SELECT ACS.countryCode, C.countryName, ACS.GDPperCap
            FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
            WHERE ACS.GDPperCap IS NOT NULL AND year = ?
            ORDER BY ACS.GDPperCap DESC LIMIT 50),
            FactorTwo AS (
            SELECT countryCode, ".$factor." AS factor
            FROM AnnualCountryStats
            WHERE ".$factor." IS NOT NULL AND year = ?
            ORDER BY ".$factor." DESC LIMIT 50)
            SELECT *
            FROM GDP AS G JOIN FactorTwo AS F ON F.countryCode = G.countryCode
			ORDER BY F.factor DESC;"
	)) {	
		$stmt->bind_param('dd', $year, $year);

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