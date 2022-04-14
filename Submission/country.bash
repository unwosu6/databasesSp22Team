awk '$1 == "ARE" && $2 == "1990" {print}' AnnualCountryStats.txt > AnnualCountryStats-small.txt
awk '$1 == "NGA" && $2 == "1995" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "USA" && $2 == "2000" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "CHN" && $2 == "2005" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "ECA" && $2 == "2005" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "PHL" && $2 == "2010" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "MEX" && $2 == "2015" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "TTO" && $2 == "2016" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt
awk '$1 == "GBR" && $2 == "2001" {print}' AnnualCountryStats.txt >> AnnualCountryStats-small.txt

awk '$1 == "ARE" {print}' Country.txt > Country-small.txt
awk '$1 == "NGA" {print}' Country.txt >> Country-small.txt
awk '$1 == "USA" {print}' Country.txt >> Country-small.txt
awk '$1 == "CHN" {print}' Country.txt >> Country-small.txt
awk '$1 == "ECA" {print}' Country.txt >> Country-small.txt
awk '$1 == "PHL" {print}' Country.txt >> Country-small.txt
awk '$1 == "MEX" {print}' Country.txt >> Country-small.txt
awk '$1 == "TTO" {print}' Country.txt >> Country-small.txt
awk '$1 == "GBR" {print}' Country.txt >> Country-small.txt
awk '$1 == "SVK" {print}' Country.txt >> Country-small.txt
awk '$1 == "ECU" {print}' Country.txt >> Country-small.txt
awk '$1 == "PAN" {print}' Country.txt >> Country-small.txt
awk '$1 == "EGY" {print}' Country.txt >> Country-small.txt
awk '$1 == "AUT" {print}' Country.txt >> Country-small.txt
awk '$1 == "MNG" {print}' Country.txt >> Country-small.txt
awk '$1 == "UGA" {print}' Country.txt >> Country-small.txt
awk '$1 == "IDN" {print}' Country.txt >> Country-small.txt
awk '$1 == "KAZ" {print}' Country.txt >> Country-small.txt
awk '$1 == "ZWE" {print}' Country.txt >> Country-small.txt
awk '$1 == "LUX" {print}' Country.txt >> Country-small.txt
awk '$1 == "CZE" {print}' Country.txt >> Country-small.txt
