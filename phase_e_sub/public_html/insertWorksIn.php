<head><title>Insert WorksIn</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$country = $_POST['country'];
    $sectorID = $_POST['sectorID'];
	$year = $_POST['year'];
    $sex = $_POST['sex'];
    $monthlyEarnings = $_POST['monthlyEarnings'];

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

    $code = "INSERT INTO WorksIn(sectorID, countryCode, year, sex, monthlyEarnings)
    VALUES (?, ?, ?, ?, ?);";

	echo "<h2>Add an Average Monthly Earnings Value</h2><br>";

    if (!ctype_digit($year)) {
        echo "ERROR: year (".$year.") must be a positive integer"; 
    } elseif (!ctype_digit($monthlyEarnings)) {
        echo "ERROR: average montly earnings  (".$monthlyEarnings.") must be a positive integer";
    } else {
        if (strlen($year) == 0) {
            echo "ERROR: year (".$year.") must be a positive integer";
        } elseif ($stmt = $conn->prepare($code)) {
            $stmt->bind_param('ssdsd', $sectorID, $country, $year, $sex, intval($monthlyEarnings));
    
            if ($stmt->execute()) {
                echo "Average monthly earnings in ".$year." for ".$sex."s in ".$countryName." working in ".$sectorID." was sucessfully added to the database";
            } else {
                echo "Execution failed because there is already a record for average monthly earnings in ".$year." for ".$sex."s in ".$countryName." working in the sector: ".$sectorID." in the database.";
            }
            echo "<br><br>";
    
        } else {
            $error = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
         
    }


	$conn->close();
?>
</body>
