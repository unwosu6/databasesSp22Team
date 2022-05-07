<head><title>Question 2</title></head>
<body>
<?php

     //open a connection to dbase server 
    include 'open.php';

    // collect the posted value in a variable called $item
    $id = $_POST['year'];

    if (!empty($id)) {
        echo "<h2>Most well paid job sector in each country in $id </h2><br>";
        if ($result = $conn->query("CALL QuestionTwo('".$id."');")) {
            if ($result->num_rows > 0) {
                echo "<table border=\"2px solid black\">";
                echo "<tr><td>sid</td><td>lname</td><td>fname</td><td>section</td></tr>";
                foreach($result as $row){
                    echo "<tr>";
                    echo "<td>".$row["Country"]."</td>";
                    echo "<td>".$row["Sector"]."</td>";
                    echo "<td>".$row["Sex"]."</td>";
                    echo "<td>".$row["Monthly Earnings"]."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "ERROR: Specified year does not have data";
            }
        } else {
            echo "Call to QuestionTwo failed<br>";
        }
	} else {
        echo "no year given";
    }
	$conn->close();

?>
</body>