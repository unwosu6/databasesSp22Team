awk '$1 == "ARE" && $2 == "1990" && $3 == "Male" {print}' AnnualCountryStats.txt > AnnualDemoStats-small.txt
awk '$1 == "NGA" && $2 == "1995" && $3 == "Female" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "USA" && $2 == "2000" && $3 == "Male" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "CHN" && $2 == "2005" && $3 == "Female" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "PHL" && $2 == "2010" && $3 == "Male" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "MEX" && $2 == "2015" && $3 == "Female" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "TTO" && $2 == "2016" && $3 == "Male" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
awk '$1 == "GBR" && $2 == "2001" && $3 == "Female" {print}' AnnualCountryStats.txt >> AnnualDemoStats-small.txt
