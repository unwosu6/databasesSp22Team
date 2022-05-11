<head><title>Insert Country</title></head>
<body>
<?php

	//open a connection to dbase server 
	include 'open.php';
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	// collect the posted value in a variable
	$countryCode = $_POST['countryCode'];
    $countryName = $_POST['countryName'];
	$continent = $_POST['continent'];
    $paidVacDay = $_POST['paidVacDay'];
    $paidHoliday = $_POST['paidHoliday'];
    $paidLeaveTotal = 0;

    $code = "INSERT INTO Country(countryCode, countryName, continent, paidVacDays, paidHoliday, paidLeaveTotal)
    VALUES (?, ?, ?, ?, ?, ?);";

	echo "<h2>Add a country</h2><br>";

    if (strlen($countryCode) != 3) {
        echo "ERROR: country code was not three letters long";
    } elseif (strlen($continent) == 0) {
        echo "ERROR: no continent given";
    } elseif (!(strlen($countryName) > 0 && strlen($countryName) < 200)) {
        echo "ERROR: country name not between 1 and 200 characters";
    } elseif (!ctype_alpha($countryCode)) {
        echo "ERROR: country code must contain only letters";
    } elseif (strlen($paidVacDay) > 0 && !ctype_digit($paidVacDay)) {
        echo "ERROR: the number of paid vacation days (".$paidVacDay.") must be a positive integer"; 
    } elseif (strlen($paidHoliday) > 0 && !ctype_digit($paidHoliday)) {
        echo "ERROR: the number of paid holidays (".$paidHoliday.") must be a positive integer"; 
    } else {
        if (strlen($paidHoliday) == 0) {
            $paidHoliday = null;
        }
        if (strlen($paidVacDay) == 0) {
            $paidVacDay = null;
        }
        if ($paidVacDay == null && $paidHoliday == null) {
            $paidLeaveTotal = null;
        } elseif ($paidVacDay == null) {
            $paidLeaveTotal = $paidHoliday;
        } elseif ($paidHoliday == null) {
            $paidLeaveTotal = intval($paidVacDay);
        } else {
            $paidLeaveTotal = intval($paidHoliday) + intval($paidVacDay);
        }
        
        if ($paidLeaveTotal > 365) {
            echo "ERROR: paid holiday and vacation days combinded (".$paidLeaveTotal.") exceed the number of days in a year";
        } elseif ($stmt = $conn->prepare($code)) {
            
            $stmt->bind_param('sssddd', $countryCode, $countryName, $continent, $paidVacDay, $paidHoliday, $paidLeaveTotal);
    
            if ($stmt->execute()) {
                echo $countryName." was sucessfully added to the database";
            } else {
                echo "Execution failed because the country code ".$countryCode." is already taken";
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
