<head><title>Insert AnnualCountryStats</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$countryCode = $_POST['country'];
    $year = $_POST['year'];
    // can be null
	$pctUsingInternet = $_POST['pctUsingInternet'];
    $GDPperCap = $_POST['GDPperCap'];
    $population = $_POST['population'];
    $fertRate = $_POST['fertRate'];
    $lifeSatisfaction = $_POST['lifeSatisfaction'];

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$countryCode."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

    $code = "INSERT INTO AnnualCountryStats(countryCode, year, pctUsingInternet, GDPperCap, population, fertRate, lifeSatisfaction)
    VALUES (?, ?, ?, ?, ?, ?, ?);";

	echo "<h2>Add a Country Statistic</h2><br>";

    if (!ctype_digit($year)) {
        echo "ERROR: year (".$year.") must be a positive integer"; 
    } elseif ((strlen($pctUsingInternet) > 0 && !is_numeric($pctUsingInternet)) || ((float)$pctUsingInternet < 0 || (float)$pctUsingInternet > 100) ) {
        echo "ERROR: the percent of people using the internet (".$pctUsingInternet.") must be a positive number between 0 and 100"; 
    } elseif (strlen($GDPperCap) > 0 && !is_numeric($GDPperCap)) {
        echo "ERROR: GDP per capita (".$GDPperCap.") must be a number"; 
    } elseif ((strlen($population) > 0 && !is_numeric($population)) || (float)$population < 0) {
        echo "ERROR: population (".$population.") must be a positive number"; 
    } elseif ((strlen($fertRate) > 0 && !is_numeric($fertRate)) || (float)$fertRate < 0) {
        echo "ERROR: fertility rate (".$fertRate.") must be a non negative number"; 
    } elseif ((strlen($lifeSatisfaction) > 0 && !is_numeric($lifeSatisfaction)) || ((float)$lifeSatisfaction < 0 || (float)$lifeSatisfaction > 10) ) {
        echo "ERROR: life satisfaction (".$lifeSatisfaction.") must be a positive number between 0 and 10"; 
    } else {
        if (strlen($pctUsingInternet) == 0) {
            $pctUsingInternet = null;
        }
        if (strlen($GDPperCap) == 0) {
            $GDPperCap = null;
        }
        if (strlen($population) == 0) {
            $population = null;
        }
        if (strlen($fertRate) == 0) {
            $fertRate = null;
        }
        if (strlen($lifeSatisfaction) == 0) {
            $lifeSatisfaction = null;
        }
        if ($stmt = $conn->prepare($code)) {
            $stmt->bind_param('sdddddd', $countryCode, $year, $pctUsingInternet, $GDPperCap, $population, $fertRate, $lifeSatisfaction);
    
            if ($stmt->execute()) {
                echo "Statistics for ".$countryName." in ".$year." were sucessfully added to the database";
            } else {
                echo "Execution failed because statistics for ".$countryName." in ".$year." have already been added to the database";
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
