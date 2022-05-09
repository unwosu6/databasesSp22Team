<head><title>Q8</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$number = $_POST['number'];
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

    function displayItems($res) {
		if ($res->num_rows == 0) {
			global $factor, $number;
			echo "There is no data for the factor ".$factor." where the life satisfaction is above ".$number.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Year </th> <th> Average </th> ";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['year']."</td>";
				echo "<td>".$row['average']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	echo "<h2>What is the average ".$factorWord." of a country with a life satisfaction above ".$number." for each year? </h2><br>";
	// show visual
	echo "<form action=\"Q8-new.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"number\" value=\"".$number."\">";
	echo "<input type=\"hidden\" name=\"factor\" value=\"".$factor."\">";
	echo "<input type=\"hidden\" name=\"factorWord\" value=\"".$factorWord."\">";
	echo "<input type=\"submit\" value=\"see visual\">";
	echo "</form>";
	echo "<br/><br/>";


	if ($stmt = $conn->prepare(
		"SELECT ADS.year, avg(ADS.".$factor.") AS average 
        FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS 
        ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode 
        WHERE ACS.lifeSatisfaction > ? 
        GROUP BY ADS.year;"
	)) {	
		$stmt->bind_param('d', $number);

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
