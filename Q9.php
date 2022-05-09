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
	echo "<h2>What is the average life satisfaction of the bottom 30 countries with the lowest ".$sex." ".$factorWord." in ".$year."?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Average Life Satisfaction </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['avLS']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH BotThirty AS (SELECT ADS.countryCode, ACS.?
        FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS 
        ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode 
        WHERE ACS.year = ? AND ADS.sex = ? AND ACS.? IS NOT NULL
        ORDER BY ADS.lifeExpect ASC LIMIT 30)
        SELECT avg(?) AS aveLS
        FROM BotThirty;"
	)) {	
		$stmt->bind_param('sdsss', $factor, $year, $sex, $factor, $factor);

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