<head><title>Q2</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
    $year = $_POST['year'];
	$factor1 = $_POST['factor1'];
	$factor2 = $_POST['factor2'];

    $factorWord1 = "NONE GIVEN";

    if ($factor1 == "pctUsingInternet") {
        $factorWord1 = "Percent of the Population Using the Internet";
    } elseif ($factor1 == "lifeSatisfaction") {
        $factorWord1 = "Life Satisfaction";
    } elseif ($factor1 == "GDPperCap") {
        $factorWord1 = "GDP Per Capita";
    } elseif ($factor1 == "population") {
        $factorWord1 = "Population";
    } elseif ($factor1 == "fertRate") {
        $factorWord1 = "Fertility Rate";
    }

    $factorWord2 = "NONE GIVEN";

    if ($factor2 == "pctUsingInternet") {
        $factorWord2 = "Percent of the Population Using the Internet";
    } elseif ($factor2 == "lifeSatisfaction") {
        $factorWord2 = "Life Satisfaction";
    } elseif ($factor2 == "GDPperCap") {
        $factorWord2 = "GDP Per Capita";
    } elseif ($factor2 == "population") {
        $factorWord2 = "Population";
    } elseif ($factor2 == "fertRate") {
        $factorWord2 = "Fertility Rate";
    }

	// echo some basic header info onto the page
	echo "<h2>In ".$year.", which countries have the highest ".$factorWord1." in their continent and what is their ".$factorWord2."?</h2><br>";

	function displayItems($res) {
		global $year, $factorWord1, $factorWord2;
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Continent </th> ";
			echo "<th> ".$factorWord1." </th> <th> ".$factorWord2." </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
                echo "<td>".$row['continent']."</td>";
				echo "<td>".$row['factor1']."</td>";
				echo "<td>".$row['factor2']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH AnnualCountryStatsWithContinent AS(
            SELECT ACS.countryCode, C.countryName, ACS.".$factor1." AS factor1, ACS.".$factor2." AS factor2, ACS.year, C.continent
            FROM AnnualCountryStats AS ACS JOIN Country AS C ON ACS.countryCode = C.countryCode)
        SELECT ACS.countryCode, ACS.countryName, ACS.continent, ACS.factor1, ACS.factor2 
        FROM AnnualCountryStatsWithContinent AS ACS, AnnualCountryStatsWithContinent AS ACSmax
        WHERE ACS.year = ? AND ACS.year = ACSmax.year AND ACS.continent = ACSmax.continent
        GROUP BY ACS.continent, ACS.countryCode, ACS.factor1, ACS.factor2
        HAVING ACS.factor1 = max(ACSmax.factor1)
        ORDER BY ACS.factor1 DESC;"
	)) {	

		$stmt->bind_param('d',  $year);

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