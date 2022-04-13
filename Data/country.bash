awk '$1 == "ARE" && $2 == "1990" {print}' AnnualCountryStats.txt > AnnualCountryStats-small.txt
awk '$1 == "NGA" && $2 == "1995" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "USA" && $2 == "2000" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "CHN" && $2 == "2005" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "PHL" && $2 == "2010" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "MEX" && $2 == "2015" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "TTO" && $2 == "2016" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "GBR" && $2 == "2001" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
