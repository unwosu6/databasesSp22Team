Joanna Bi jbi9
Udochukwu Nwosu unwosu6

We made several changes to the relationship schema after careful consideration. 
For Country we removed the religion attributes since they didn't add that much, and we expanded the minimumAnnualLeave attribute into  paidVacDays, paidHolidy, paidLeaveTotal.
We replaced countryName attributes in WorksIn and AnnualCountryStats with countryCode, since that is more standardized and easier, being a 3 letter code. We also added that to Country. 
We added lifeExpect (life expectancy) to AnnualDemographicStats as well.
