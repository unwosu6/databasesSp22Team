<head>
    <title>Q12</title>
    <script>
    window.onload = function () { 
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            title:{
                text: "Average Pay Gap Over Time"
            },
            data: [{
                type: "line", //change type to column, bar, line, area, pie, etc  
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render(); 
    }
    </script>
</head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$year = $_POST['year'];

    $dataPoints = array();

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sql = "SELECT C.countryName, W.year, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
    FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
    ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
    WHERE W.sectorId = 'Total' AND W.countryCode = 'USA';";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($result = mysqli_query($conn,$sql)){	  
	   foreach($result as $row){
	      array_push($dataPoints, array( "year"=> $row["year"], "monthlyEarnings"=> $row["femalePay"]));
	   }
	}

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
            SELECT C.countryName, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
            FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
            ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
            WHERE W.sectorId = 'Total' AND W.year = ?)
            SELECT *
            FROM Diff
            WHERE payDiff = (SELECT min(payDiff) FROM Diff);"
	)) {	
		$stmt->bind_param('ds', $year, $continent);

		if ($stmt->execute()) {
			$result = $stmt->get_result();
			if ($result->num_rows != 0) {
				$row = $result->fetch_assoc();
				echo "In ".$year.", ".$row['countryName']." had the lowest difference (".$row['payDiff']." USD).<br>";
                echo "Female monthly pay was".$row['femalePay']."<br>";
                echo "Male monthly pay was".$row['malePay']."<br>";
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


	$conn->close();
?>
    <div id="chartContainer" style="height: 400px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
