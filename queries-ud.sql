-- Joanna Bi jbi9
-- Udochuwku Nwosu unwosu6

-- 2: What is the most well paid job sector in 2016 for each country from most well paid to least well paid?
SELECT W.countryCode, W.sectorID, W.sex, W.monthlyEarnings
FROM WorksIn AS W, WorksIn AS Wmax
WHERE W.countryCode = Wmax.countryCode 
AND W.year = Wmax.year AND W.year = 2016 
AND Wmax.sectorID != 'Total' 
GROUP BY W.countryCode, W.monthlyEarnings, W.sectorID, W.sex
HAVING W.monthlyEarnings = max(Wmax.monthlyEarnings)
ORDER BY W.monthlyEarnings DESC;

-- 4: Given a range of “happiness” levels (in this case between 5 and 9,) what is the average number of days of paid leave for countries within the range?
SELECT avg(C.paidLeaveTotal) AS averagePaidLeave
FROM Country AS C JOIN AnnualCountryStats AS ACS
ON C.countryCode = ACS.countryCode
WHERE ACS.year = 2016 AND ACS.lifeSatisfaction > 5 AND ACS.lifeSatisfaction < 9;

-- 6: How many countries from each continent have a higher life satisfaction than the United States and what are those countries?
SELECT ACS.countryCode, C.countryName, C.continent, ACS.lifeSatisfaction
FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
WHERE year = 2016 
AND lifeSatisfaction >= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = 2016 AND countryCode = 'USA')
ORDER BY C.continent, ACS.lifeSatisfaction DESC;

SELECT C.continent, count(*) AS numCountries
FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
WHERE year = 2016
AND lifeSatisfaction >= (SELECT ACS2.lifeSatisfaction FROM AnnualCountryStats AS ACS2 WHERE year = 2016 AND countryCode = 'USA')
GROUP BY C.continent;

-- 8: What is the average life expectancy of a country with a life expectancy above 6 for each year before 2016?
SELECT ADS.year, avg(ADS.lifeExpect) AS averageLifeExpectancy
FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS
ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode
WHERE ACS.year < 2016 AND ACS.lifeSatisfaction > 6
GROUP BY ADS.year;

-- 10: What is the average fertility rate of the countries with a life satisfaction above 7? 
SELECT year, avg(fertRate) AS averageFertilityRate
FROM AnnualCountryStats
WHERE lifeSatisfaction > 7
GROUP BY year;

-- 12: What country has the smallest gap in average monthly earnings between men and women in 2016?
WITH Diff AS (
SELECT W.countryCode, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
FROM WorksIn AS W JOIN WorksIn AS W2
ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode
WHERE W.sectorId = 'Total' AND W.year = 2016)
SELECT *
FROM Diff
WHERE payDiff = (SELECT min(payDiff) FROM Diff);

-- 14: List the 30 countries with the lowest percentage of people using the internet in 2016 and provide an average of their GDP
WITH Bot30Internet AS (
SELECT countryCode, pctUsingInternet AS percentPopulationWithInternetAccess, GDPperCap
FROM AnnualCountryStats
WHERE year = 2016 AND pctUsingInternet IS NOT NULL
ORDER BY pctUsingInternet ASC LIMIT 30)
SELECT * FROM Bot30Internet;
SELECT avg(GDPperCap) AS averageGDPofLeastInternetAccess FROM Bot30Internet;

-- 15: Which countries have the highest population in their continent and what is their fertility rate and continent?
SELECT countryCode, max(population), fertRate, continent
FROM AnnualCountryStats ACS, AnnualCountryStats ACS2
WHERE year = 2016
GROUP BY continent, countryCode;

