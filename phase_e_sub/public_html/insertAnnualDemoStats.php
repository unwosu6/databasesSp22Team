<head><title>Insert AnnualDemoStats</title></head>
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
    // can be null
	$laborForcePartipation = $_POST['laborForcePartipation'];
    $pctAdvancedEdu = $_POST['pctAdvancedEdu'];
    $pctBasicEdu = $_POST['pctBasicEdu'];
    $lifeExpect = $_POST['lifeExpect'];
    $literacyRate = $_POST['literacyRate'];

    $query = "SELECT countryName FROM Country WHERE countryCode ='".$countryCode."';";
	$results = mysqli_query($conn, $query);
	$countryName = $results->fetch_assoc()['countryName'];

    $code = "INSERT INTO AnnualDemoStats(countryCode, year, sex, laborForcePartipation, pctAdvancedEdu, pctBasicEdu, lifeExpect, literacyRate)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?);";

	echo "<h2>Add a Country Statistic by Demographic</h2><br>";

    if (!ctype_digit($year)) {
        echo "ERROR: year (".$year.") must be a positive integer"; 
    } elseif ((strlen($laborForcePartipation) > 0 && !is_numeric($laborForcePartipation)) || ((float)$laborForcePartipation < 0 || (float)$laborForcePartipation > 100) ) {
        echo "ERROR: the labor force participation rate (".$laborForcePartipation.") must be a positive number between 0 and 100"; 
    } elseif ((strlen($lifeExpect) > 0 && !is_numeric($lifeExpect)) || (float)$lifeExpect < 0) {
        echo "ERROR: Life expectancy (".$lifeExpect.") must be a positive number"; 
    } elseif ((strlen($pctAdvancedEdu) > 0 && !is_numeric($pctAdvancedEdu)) || ((float)$pctAdvancedEdu < 0 || (float)$pctAdvancedEdu > 100) ) {
        echo "ERROR: Percent of the Population with an Advanced Education (".$pctAdvancedEdu.") must be a positive number between 0 and 100"; 
    } elseif ((strlen($pctBasicEdu) > 0 && !is_numeric($pctBasicEdu)) || ((float)$pctBasicEdu < 0 || (float)$pctBasicEdu > 100) ) {
        echo "ERROR: Percent of the Population with an Basic Education (".$pctBasicEdu.") must be a positive number between 0 and 100"; 
    } elseif ((strlen($literacyRate) > 0 && !is_numeric($literacyRate)) || ((float)$literacyRate < 0 || (float)$literacyRate > 100) ) {
        echo "ERROR: Literacy Rate (".$literacyRate.") must be a positive number between 0 and 100"; 
    } else {
        if (strlen($laborForcePartipation) == 0) {
            $laborForcePartipation = null;
        }
        if (strlen($pctAdvancedEdu) == 0) {
            $pctAdvancedEdu = null;
        }
        if (strlen($pctBasicEdu) == 0) {
            $pctBasicEdu = null;
        }
        if (strlen($lifeExpect) == 0) {
            $lifeExpect = null;
        }
        if (strlen($literacyRate) == 0) {
            $literacyRate = null;
        }
        if ($stmt = $conn->prepare($code)) {
            $stmt->bind_param('sdsddddd', $countryCode, $year, $sex, $laborForcePartipation, $pctAdvancedEdu, $pctBasicEdu, $lifeExpect, $literacyRate);
    
            if ($stmt->execute()) {
                echo "Statistics for ".$sex."s in ".$countryName." in ".$year." were sucessfully added to the database";
            } else {
                echo "Execution failed because statistics for ".$sex."s in ".$countryName." in ".$year." have already been added to the database";
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
