<!-- jbi9_unwosu6.html -->
<!DOCTYPE html>
<html>
   <body>

	<!-- (1) -->
	<h2> In [YEAR] what was the average level of life satisfaction for countries with the top 3 GDPs on the continent of [CONTINENT]?</h2>
	<form action="Q1.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		CONTINENT: 
		<select name="continent">  
			<option value="">--- choose a continent ---</option>}
			<option value="Africa">Africa</option>
			<option value="Asia">Asia</option>  
			<option value="North America">North America</option>  
			<option value="South America">South America</option>  
			<option value="Europe">Europe</option>  
			<option value="Oceania">Oceania</option>  
		</select>
		<br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (2) -->
	<h2> What was the most well-paid job sector in [YEAR] for each country?</h2>
	<form action="Q2.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (3) -->
	<h2> What is the average paid leave for each continent? (Visual)</h2>
	<form action="Q3.php" method="post">
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (4) -->
	<h2> In [YEAR], what is the average number of days of paid leave for countries with a life satisfaction above [LOWER LIMIT] and below [UPPER LIMIT]?</h2>
	<h3> (Life satisfaction is measured on a scale from 0 to 10)</h3>
	<form action="Q4.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		LOWER LIMIT: <input type="text" name="lower"> <br/>
		UPPER LIMIT: <input type="text" name="upper"> <br/>
		<input type="submit">
	</form>

	<!-- (5/6) -->
	<h2> How many countries from each contient have a [DIRECTION: LOWER/HIGHER] life satisfaction than [COUNTRY] in [YEAR]? (Visual)</h2>

	<form action="Q56.php" method="post">
		DIRECTION:
		<select name="direction">  
			<option value="">--- choose a direction ---</option>}
			<option value="lower">Lower</option>
			<option value="higher">Higher</option>   
		</select><br/>
		<?php
			include 'open.php';
			echo "COUNTRY:"; 
			echo "<select name=\"country\">";
			echo "<option value=\"\">Select Country</option>";
			//populate value using php
			$query = "SELECT * FROM Country;";
			$results = mysqli_query($conn, $query);
			//loop
			foreach ($results as $country){
				echo "<option value=\"".$country['countryCode']."\">".$country['countryName']."</option>";
			}
			echo "</select><br/>";
			$conn->close();
		?>
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>

	<!-- (7) -->
	<h2> Which continent has the greatest percentage of countries that have more than [NUMBER] of days of paid leave? (Visual)</h2>
	<h3> (The recommended range is from 0 to 10)</h3>
	<form action="Q7.php" method="post">
		NUMBER: <input type="text" name="number"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (8) -->
	<h2> What is the average [FACTOR] of a country with a life satisfaction above [NUMBER] for each year before [YEAR]?</h2>
	<form action="Q8.php" method="post">
		FACTOR:
		<select name="factor">  
			<option value="">--- choose a factor ---</option>}
			<option value="laborForcePartipation">Labor Force Participation Rate</option>
			<option value="pctAdvancedEdu">Percent of the Population with Advanced Education</option>  
			<option value="pctBasicEdu">Percent of the Population with Basic Education</option>  
			<option value="lifeExpect">Life Expectancy</option>  
			<option value="literacyRate">Literacy Rate</option>  
		</select><br/>
		NUMBER: <input type="text" name="number"> <br/>
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (9) -->
	<h2> What is the average life satisfaction of the bottom 30 countries with the lowest [SEX] [FACTOR] in [YEAR]?</h2>
	<form action="Q9.php" method="post">
		SEX: <input type="text" name="sex"> <br/>
		FACTOR:
		<select name="factor">  
			<option value="">--- choose a factor ---</option>}
			<option value="laborForcePartipation">Labor Force Participation Rate</option>
			<option value="pctAdvancedEdu">Percent of the Population with Advanced Education</option>  
			<option value="pctBasicEdu">Percent of the Population with Basic Education</option>  
			<option value="lifeExpect">Life Expectancy</option>  
			<option value="literacyRate">Literacy Rate</option>  
		</select>
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (10) -->
	<h2> What was the average [FACTOR] of the countries with a life satisfaction above [NUMBER]?</h2>
	<form action="Q10.php" method="post">
		FACTOR:
		<select name="factor">  
			<option value="">--- choose a factor ---</option>}
			<option value="pctUsingInternet">Percent of the Population Using the Internet</option>
			<option value="GDPperCap">GDP Per Capita</option>  
			<option value="population">Population</option>  
			<option value="fertRate">Fertility Rate</option>  
		</select>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (11) -->
	<h2> What sectors in [COUNTRY] had a growth in monthly earnings from [START YEAR] to [END YEAR] for [SEX]? (Visual)</h2>
	<form action="Q11.php" method="post">
		<?php
			include 'open.php';
			echo "COUNTRY:"; 
			echo "<select name=\"country\">";
			echo "<option value=\"\">Select Country</option>";
			//populate value using php
			$query = "SELECT * FROM Country;";
			$results = mysqli_query($conn, $query);
			//loop
			foreach ($results as $country){
				echo "<option value=\"".$country['countryCode']."\">".$country['countryName']."</option>";
			}
			echo "</select><br/>";
			$conn->close();
		?>
		START YEAR: <input type="text" name="start"> <br/>
		END YEAR: <input type="text" name="end"> <br/>
		SEX: 
		<select name="sex">  
			<option value="">--- choose a sex ---</option>}
			<option value="lower">Females</option>
			<option value="higher">Males</option>   
		</select>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (12) -->
	<h2> What country has the smallest gap in average monthly earnings between males and females in [YEAR]? (Visual)</h2>
	<form action="Q12.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (12-new) -->
	<h2> What is the gap in average monthly earnings between males and females in [COUNTRY]? (Visual)</h2>
	<form action="Q12-new.php" method="post">
		<?php
			include 'open.php';
			echo "COUNTRY:"; 
			echo "<select name=\"country\">";
			echo "<option value=\"\">Select Country</option>";
			//populate value using php
			$query = "SELECT * FROM Country;";
			$results = mysqli_query($conn, $query);
			//loop
			foreach ($results as $country){
				echo "<option value=\"".$country['countryCode']."\">".$country['countryName']."</option>";
			}
			echo "</select><br/>";
			$conn->close();
		?>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (13) -->
	<h2> In [YEAR], how many countries (list them) are in both the top 50 for [FACTOR] and GDP per capita?</h2>
	<form action="Q13.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
		FACTOR:
		<select name="factor">  
			<option value="">--- choose a factor ---</option>}
			<option value="pctUsingInternet">Percent of the Population Using the Internet</option>
			<option value="lifeSatisfaction">Life Satisfaction</option>  
			<option value="population">Population</option>  
			<option value="fertRate">Fertility Rate</option>  
		</select>
	</form>
	<br/><br/>

	<!-- (14) -->
	<h2> List the 30 countries with the lowest [FACTOR] in [YEAR] and provide an average of their GDP.</h2>
	<form action="Q14.php" method="post">
		FACTOR:
		<select name="factor">  
			<option value="">--- choose a factor ---</option>}
			<option value="pctUsingInternet">Percent of the Population Using the Internet</option>
			<option value="lifeSatisfaction">Life Satisfaction</option>  
			<option value="population">Population</option>  
			<option value="fertRate">Fertility Rate</option>  
		</select>
		YEAR: <input type="text" name="year"> <br/>
		<input type="submit">
	</form>
	<br/><br/>

	<!-- (15) -->
	<h2> In [YEAR], which countries have the highest [FACTOR 1] in their continent and what is their [FACTOR 2]?</h2>
	<form action="Q15.php" method="post">
		YEAR: <input type="text" name="year"> <br/>
		FACTOR 1:
		<select name="factor1">  
			<option value="">--- choose a factor ---</option>}
			<option value="pctUsingInternet">Percent of the Population Using the Internet</option>
			<option value="GDPperCap">GDP Per Capita</option>  
			<option value="population">Population</option>  
			<option value="fertRate">Fertility Rate</option>  
			<option value="lifeSatisfaction">Life Satisfaction</option>
		</select>
		FACTOR 2:
		<select name="factor2">  
			<option value="">--- choose a factor ---</option>}
			<option value="pctUsingInternet">Percent of the Population Using the Internet</option>
			<option value="GDPperCap">GDP Per Capita</option>  
			<option value="population">Population</option>  
			<option value="fertRate">Fertility Rate</option>  
			<option value="lifeSatisfaction">Life Satisfaction</option>
		</select>
		<input type="submit">
	</form>
	<br/><br/>

   </body>
</html>