<!-- First we'll use php to query dbase, then we'll use html and CanvasJS to
     render a chart built from the data returned to use from dbase.
     See CanvasJS documentation, including other chart options at
     https://canvasjs.com/docs/charts/basics-of-creating-html5-chart/   -->

     <?php
	//open a connection to dbase server 
	include 'open.php';

	//construct an array in which we'll store our data
	$dataPoints = array();

	//we'll soon see how to upgrade our queries so they aren't plain strings
	$sql = "SELECT C.countryName, W.countryCode, W.year, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
    FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
    ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
    WHERE W.sectorId = 'Total' AND W.countryCode = 'USA';";

	//execute the query, then run through the result table row by row to
	//put each row's data into our array
	if ($result = mysqli_query($conn,$sql)){	  
	   foreach($result as $row){
	      array_push($dataPoints, array( "label"=> $row["year"], "y"=> $row["femalePay"]));
	   }
	}
	
	//close the connection opened by open.php since we no longer need access to dbase
	$conn->close();
?>


<html>
<head>
<script>
window.onload = function () { 
	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		exportEnabled: true,
		theme: "light1", // "light1", "light2", "dark1", "dark2"
		title:{
			text: "PHP Line Chart from Database - MySQLi"
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
	<div id="chartContainer" style="height: 400px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
