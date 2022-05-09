<head>
    <title>Q12</title>
</head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];
	$country = "";

    $dataPoints = array();

	// echo some basic header info onto the page
	echo "<h2>What country has the smallest gap in average monthly earnings between males and females in ".$year."? </h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found for that year";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Country Name </th> <th> GDP per Capita </th> ";
			echo "<th> Life Satisfaction </th>";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['countryName']."</td>";
				echo "<td>".$row['femalePay']."</td>";
                echo "<td>".$row['malePay']."</td>";
				echo "<td>".$row['payDiff']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}
	if ($stmt = $conn->prepare(
		"WITH Diff AS (
            SELECT C.countryName, W.countryCode, W.year, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
            FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
            ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
            WHERE W.sectorId = 'Total' AND W.year = ?)
            SELECT *
            FROM Diff
            WHERE payDiff = (SELECT min(payDiff) FROM Diff);"
	)) {	
		$stmt->bind_param('d', $year);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
            if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
                $country = $row['countryCode'];
				echo "In ".$year.", ".$row['countryName']." had the lowest difference (".$row['payDiff']." USD).<br>";
                echo "Female monthly pay was ".$row['femalePay']." USD <br>";
                echo "Male monthly pay was ".$row['malePay']." USD <br>";
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
	// show visual
	echo "<form action=\"Q12-new.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"country\" value=\"".$country."\">";
	echo "<input type=\"submit\" value=\"see visual\">";
	echo "</form>";
	echo "<br/><br/>";

	$conn->close();
?>
</body>
