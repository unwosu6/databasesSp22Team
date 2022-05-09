<head><title>Q8</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];
	$direction = $_POST['direction'];
	$symb = "";
	$country = "USA";
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


	
	// dynamic dropdown
    echo "COUNTRY:"; 
	echo "<select name=\"country\">";
	echo "<option value=\"\">Select Country</option>";
	//populate value using php
    $query = "SELECT * FROM Country;";
    $results = mysqli_query($conn, $query);
    //loop
    foreach ($results as $country){
        echo "<option value=\"".$country['countryCode']."\">".$country['countryName']."</option>";
    }
	echo "</select><br/>";



    function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
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

	// echo some basic header info onto the page
	echo "<h2>How many countries from each contient have a ".$direction." life satisfaction than [COUNTRY] in ".$year."?</h2><br>";

	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('dds', $year, $year, $countryCode);

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
