<head><title>Delete AnnualCountryStats</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$countryCode = $_POST['country'];
	$year = $_POST['year'];
    $sex = $_POST['sex'];

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$countryCode."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

    $code = "DELETE FROM AnnualDemoStats 
    WHERE countryCode = ? AND year = ? AND sex = ?;";

	echo "<h2>Delete a Country Statistic by Demographic</h2><br>";

    if (!ctype_digit($year)) {
        echo "ERROR: year (".$year.") must be a positive integer"; 
    } else {
        if (strlen($year) == 0) {
            echo "ERROR: year (".$year.") must be a positive integer";
        } elseif ($stmt = $conn->prepare($code)) {
            $stmt->bind_param('sds', $countryCode, $year, $sex);
    
            if ($stmt->execute()) {
                echo "Statistics for ".$sex."s in ".$countryName." in ".$year." were sucessfully removed from the database";
            } else {
                echo "Execution failed because statistics for ".$sex."s in ".$countryName." in ".$year." have not been added to the database";
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
