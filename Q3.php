<head><title>Q3</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);

	// echo some basic header info onto the page
	echo "<h2>What is the average paid leave for each continent?</h2><br>";

	function displayItems($res) {
		if ($res->num_rows == 0) {
			echo "No results found with specified inputs";
		} else {
			echo "<table border=\"1px solid black\">";
			echo "<tr><th> Continent </th> <th> Paid Leave </th> ";
			while (null !== ($row = $res->fetch_assoc())) {
				echo "<tr>";
				echo "<td>".$row['continent']."</td>";
				echo "<td>".$row['paidLeaveTotal']."</td>";
				echo "</tr>";
			}
	
			echo "</table>";
		}
	}

	if ($stmt = $conn->prepare(
		"SELECT continent, avg(paidLeaveTotal) AS avgPaidLeave
        FROM Country
        WHERE Continent IS NOT NULL
        GROUP BY continent;"
	)) {	
		
    displayItems($result);
	$result->free_result();
	echo "<br><br>";
    
	} else {
		$error = $conn->errno . ' ' . $conn->error;
		echo $error;
	}


	$conn->close();
?>
</body>