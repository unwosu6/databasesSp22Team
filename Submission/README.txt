Joanna Bi jbi9
Udochukwu Nwosu unwosu6

We made several changes to the relationship schema after careful consideration. 
For Country we removed the religion attributes since they didn't add that much, and we expanded the minimumAnnualLeave attribute into  paidVacDays, paidHolidy, paidLeaveTotal.
We replaced countryName attributes in WorksIn and AnnualCountryStats with countryCode, since that is more standardized and easier, being a 3 letter code. We also added that to Country.
We removed the Year table (at the advice of TA Aditya) since it was redundant and did not add more information. 
There is no problem if a tuple is added or deleted that has a year that is not seen elsewhere in the database.
We added lifeExpect (life expectancy) to AnnualDemographicStats as well.

Issues:
Some data sets included country names without the standardized country codes. These names were in consistent (some included commas, parentheses, the word "The", etc.).
To fix this, we made many translation files that inlcuded many different versions of the country name and its standardized 3 letter country code.
