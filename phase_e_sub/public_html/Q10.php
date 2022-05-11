<head><title>Q10</title></head>
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

    if ($factor == "pctUsingInternet") {
        $factorWord = "Percent of the Population Using the Internet";
    } elseif ($factor == "GDPperCap") {
        $factorWord = "GDP Per Capita";
    } elseif ($factor == "population") {
        $factorWord = "Population";
    } elseif ($factor == "fertRate") {
        $factorWord = "Fertility Rate";
    }

	echo "<h2>What was the average ".$factorWord." of the countries with a life satisfaction above ".$number."? </h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
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

	if ($stmt = $conn->prepare(
		"SELECT year, CAST(avg(".$factor.") AS DECIMAL(5, 2)) AS average 
        FROM AnnualCountryStats 
        WHERE lifeSatisfaction > ? 
        GROUP BY year;"
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
