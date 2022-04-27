-- Joanna Bi jb9
-- Udochukwu Nwosu unwosu6

-- insertions
-- case where foreign key (countryCode) does not yet exist
INSERT INTO Country(countryCode, countryName, continent, paidVacDays, paidHoliday, paidLeaveTotal)
VALUES ('ABC', 'Udoannaland', 'Africa', 5, 5, 10, 20);
INSERT INTO AnnualCountryStats (countryCode, year, pctUsingInternet, GDPperCap, population, fertRate, lifeSatisfaction) 
VALUES ('ABC', 2001, 0.9, 5, 10, 1, 5);
-- case where foreign keys (countryCode and sex) already exist
INSERT INTO WorksIn(sectorID, countryCode, year, sex, monthlyEarnings)
VALUES ('Total', 'NGA', 3001, 'Female', 100000);

-- deletions
DELETE FROM Country WHERE countryCode = 'ABC';
-- this will delete all tuples in tables that include countryCode as a foreign key 
-- thus all tuples with the countryCode 'ABC' in WorksIn, AnnualCountryStats, and AnnualDemographicStats will also be deleted.

