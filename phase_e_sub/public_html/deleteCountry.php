<head><title>Delete Country</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$country = $_POST['country'];

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$country."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];
   
    $code = "DELETE FROM Country WHERE countryCode = ?;";

	echo "<h2>Delete a country</h2><br>";

    if ($stmt = $conn->prepare($code)) {	
        $stmt->bind_param('s', $country);

        if ($stmt->execute()) {
            echo $countryName." was sucessfully deleted from the database.";
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
