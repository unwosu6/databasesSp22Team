<head><title>Q9</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];
    $sex = $_POST['sex'];
    $factor = $_POST['factor'];

    $factorWord = "NONE GIVEN";

    if ($factor == "laborForcePartipation") {
        $factorWord = "Labor Force Participation Rate";
    } elseif ($factor == "pctAdvancedEdu") {
        $factorWord = "Percent of the Population with Basic Education";
    } elseif ($factor == "pctBasicEdu") {
        $factorWord = "Percent of the Population with Basic Education";
    } elseif ($factor == "lifeExpect") {
        $factorWord = "Life Expectancy";
    } elseif ($factor == "literacyRate") {
        $factorWord = "Literacy Rate";
    }

	// echo some basic header info onto the page
	echo "<h2>What is the average life satisfaction of the bottom 10 countries with the lowest ".$sex." ".$factorWord." in ".$year."?</h2><br>";

	function displayItems($res) {
		global $factorWord, $year;
		if ($res->num_rows == 0) {
			echo "No records found for the year ".$year.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> ".$factorWord." </th> <th> Life Satisfaction </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['factor']."</td>";
				echo "<td>".$row['lifeSatisfaction']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	// show average
	if ($stmt = $conn->prepare(
		"WITH BotThirty AS (
			SELECT ADS.countryCode, C.countryName, ADS.".$factor." AS factor, ACS.lifeSatisfaction
			FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS JOIN Country AS C
			ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode  AND C.countryCode = ADS.countryCode
			WHERE ACS.year = ? AND ADS.sex = ? AND ACS.lifeSatisfaction IS NOT NULL AND ADS.".$factor." IS NOT NULL
			ORDER BY ADS.".$factor." ASC LIMIT 10)
			SELECT avg(lifeSatisfaction) AS avgLS
			FROM BotThirty;"
	)) {	
		$stmt->bind_param('ds', $year, $sex);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
            if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				if ($row['avgLS'] != null) {
					echo "The Average Life Satisfaction: ".$row['avgLS']."<br>";
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

	// list countries
	if ($stmt = $conn->prepare(
		"WITH BotThirty AS (
			SELECT ADS.countryCode, C.countryName, ADS.".$factor." AS factor, ACS.lifeSatisfaction
			FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS JOIN Country AS C
			ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode  AND C.countryCode = ADS.countryCode
			WHERE ACS.year = ? AND ADS.sex = ? AND ACS.lifeSatisfaction IS NOT NULL AND ADS.".$factor." IS NOT NULL
			ORDER BY ADS.".$factor." ASC LIMIT 10)
			SELECT *
			FROM BotThirty;"
	)) {	
		$stmt->bind_param('ds', $year, $sex);

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