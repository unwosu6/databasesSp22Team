<head><title>Delete WorksIn</title></head>
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

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

    $code = "DELETE FROM WorksIn 
    WHERE countryCode = ? AND sectorID = ? AND year = ? AND sex = ?;";

	echo "<h2>Delete an Average Monthly Earnings Value</h2><br>";

    if (!ctype_digit($year)) {
        echo "ERROR: year (".$year.") must be a positive integer"; 
    } else {
        if (strlen($year) == 0) {
            echo "ERROR: year (".$year.") must be a positive integer";
        } elseif ($stmt = $conn->prepare($code)) {
            $stmt->bind_param('ssds', $country, $sectorID, $year, $sex);
    
            if ($stmt->execute()) {
                echo "Average monthly earnings in ".$year." for ".$sex."s in ".$countryName." working in ".$sectorID." was sucessfully deleted from the database";
            } else {
                echo "Execution failed because there is no record for average monthly earnings in ".$year." for ".$sex."s in ".$countryName." working in the sector: ".$sectorID." in the database.";
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
