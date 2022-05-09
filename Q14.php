<head><title>Q14</title></head>
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

    $sqlCode = "WITH Bot30Internet AS (
        SELECT ACS.countryCode, C.countryName, ACS.".$factor." AS factor, ACS.GDPperCap
        FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
        WHERE ACS.year = ? AND ACS.".$factor." IS NOT NULL
        ORDER BY ACS.".$factor." ASC LIMIT 30)
        SELECT * FROM Bot30Internet;";

    $sqlCode2 = "WITH Bot30Internet AS (
        SELECT countryCode, ".$factor." AS factor, GDPperCap
        FROM AnnualCountryStats
        WHERE year = ? AND ".$factor." IS NOT NULL
        ORDER BY ".$factor." ASC LIMIT 30)
        SELECT avg(GDPperCap) AS average FROM Bot30Internet;";

	// echo some basic header info onto the page
	echo "<h2>List the 30 countries with the lowest ".$factorWord." in ".$year." and provide an average of their GDP. </h2><br>";

	function displayItems($res, $factorWord) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
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

    // make average
    if ($stmt = $conn->prepare($sqlCode2)) {	
		$stmt->bind_param('d', $year);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				echo "The Average GDP Per Capita: ".$row['average']."<br>";
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

    // make table
	if ($stmt = $conn->prepare($sqlCode)) {	
		$stmt->bind_param('d', $year);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			displayItems($result, $factorWord);
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
