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

    if ($factor1 == "laborForcePartipation") {
        $factorWord1 = "Labor Force Participation Rate";
    } elseif ($factor1 == "pctAdvancedEdu") {
        $factorWord1 = "Percent of the Population with Basic Education";
    } elseif ($factor1 == "pctBasicEdu") {
        $factorWord1 = "Percent of the Population with Basic Education";
    } elseif ($factor1 == "lifeExpect") {
        $factorWord1 = "Life Expectancy";
    } elseif ($factor1 == "literacyRate") {
        $factorWord1 = "Literacy Rate";
    }

    $factorWord2 = "NONE GIVEN";

    if ($factor2 == "laborForcePartipation") {
        $factorWord2 = "Labor Force Participation Rate";
    } elseif ($factor2 == "pctAdvancedEdu") {
        $factorWord2 = "Percent of the Population with Basic Education";
    } elseif ($factor2 == "pctBasicEdu") {
        $factorWord2 = "Percent of the Population with Basic Education";
    } elseif ($factor2 == "lifeExpect") {
        $factorWord2 = "Life Expectancy";
    } elseif ($factor2 == "literacyRate") {
        $factorWord2 = "Literacy Rate";
    }

	// echo some basic header info onto the page
	echo "<h2>In ".$year.", which countries have the highest ".$factorWord1." in their continent and what is their ".$factorWord2."?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Job Sector </th> ";
			echo "<th> Monthly Earnings </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryCode']."</td>";
                echo "<td>".$row['continent']."</td>";
				echo "<td>".$row[$factor1]."</td>";
				echo "<td>".$row[$factor2]."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH AnnualCountryStatsWithContinent AS(
            SELECT ACS.countryCode, ACS.?, ACS.?, ACS.year, C.continent
            FROM AnnualCountryStats AS ACS JOIN Country AS C ON ACS.countryCode = C.countryCode)
        SELECT ACS.countryCode, ACS.continent, ACS.?, ACS.? 
        FROM AnnualCountryStatsWithContinent AS ACS, AnnualCountryStatsWithContinent AS ACSmax
        WHERE ACS.year = ? AND ACS.year = ACSmax.year AND ACS.continent = ACSmax.continent
        GROUP BY ACS.continent, ACS.countryCode, ACS.?, ACS.?
        HAVING ACS.? = max(ACSmax.?)
        ORDER BY ACS.? DESC;"
	)) {	

		$stmt->bind_param('ssssdsssss', $factor1, $factor2, $factor1, $factor2, $year, $factor1, $factor2, $factor1, $factor1, $factor1);

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