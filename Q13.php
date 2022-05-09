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
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> Job Sector </th> ";
			echo "<th> Monthly Earnings </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['numCountry']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"WITH GDP AS (
            SELECT countryCode
            FROM AnnualCountryStats
            WHERE GDPperCap IS NOT NULL AND year = ?
            ORDER BY GDPperCap DESC LIMIT 50),
            FactorTwo AS (
            SELECT countryCode
            FROM AnnualCountryStats
            WHERE ? IS NOT NULL AND year = ?
            ORDER BY ? DESC LIMIT 50)
            SELECT count(*) AS numCountry
            FROM GDP JOIN FactorTwo ON FactorTwo.countryCode = GDP.countryCode;"
	)) {	
		$stmt->bind_param('dsds', $year, $factor, $year, $factor);

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