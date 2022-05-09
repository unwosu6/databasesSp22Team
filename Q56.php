<head><title>Q56</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];
	$direction = $_POST['direction'];
	$country = $_POST['country'];

	$query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];
	$sqlCode = "";
	
	if ($direction == "higher") {
        $sqlCode = "SELECT ACS.countryCode, C.countryName, C.continent, ACS.lifeSatisfaction
        FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
        WHERE year = ? 
        AND lifeSatisfaction >= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = ? AND countryCode = ?)
        ORDER BY C.continent, ACS.lifeSatisfaction DESC;";
    } elseif ($direction == "lower") {
        $sqlCode = "SELECT ACS.countryCode, C.countryName, C.continent, ACS.lifeSatisfaction
        FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
        WHERE year = ? 
        AND lifeSatisfaction <= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = ? AND countryCode = ?)
        ORDER BY C.continent, ACS.lifeSatisfaction DESC;";
    }

	// $_SESSION['sqlCode'] = $sqlCode;

    function displayItems($res) {
		if ($res->num_rows == 0) {
			global $year, $direction, $countryName;
			echo "There is no record of a country in the year ".$year." with a life satisfaction ".$direction." than ".$countryName." in ".$year.".";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Continent </th> ";
			echo "<th> Life Satisfaction </th> ";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['continent']."</td>";
				echo "<td>".$row['lifeSatisfaction']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	echo "<h2>How many countries from each contient have a ".$direction." life satisfaction than ".$countryName." in ".$year."?</h2><br>";
	// show visual
	echo "<form action=\"Q56-new.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"direction\" value=\"".$direction."\">";
	echo "<input type=\"hidden\" name=\"year\" value=\"".$year."\">";
	echo "<input type=\"hidden\" name=\"country\" value=\"".$country."\">";
	echo "<input type=\"submit\" value=\"see visual\">";
	echo "</form>";
	echo "<br/><br/>";

	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('dds', $year, $year, $country);

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
