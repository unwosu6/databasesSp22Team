-- Joanna Bi jbi9
-- Udochuwku Nwosu unwosu6

-- 1: In 2016 what was the average level of life satisfaction for countries with the top 3 GDPs on the continent of Africa?
SELECT C.countryName, ACS.GDPperCap, ACS.lifeSatisfaction
FROM AnnualCountryStats AS ACS JOIN Country AS C 
ON ACS.countryCode = C.countryCode
WHERE ACS.year = 2016 AND C.continent = 'Africa' AND ACS.lifeSatisfaction IS NOT NULL
ORDER BY ACS.GDPperCap DESC LIMIT 3;

-- 2: What is the most well paid job sector in 2016 for each country from most well paid to least well paid?
SELECT W.countryCode, W.sectorID, W.sex, W.monthlyEarnings
FROM WorksIn AS W, WorksIn AS Wmax
WHERE W.countryCode = Wmax.countryCode 
AND W.year = Wmax.year AND W.year = 2016 
AND Wmax.sectorID != 'Total' 
GROUP BY W.countryCode, W.monthlyEarnings, W.sectorID, W.sex
HAVING W.monthlyEarnings = max(Wmax.monthlyEarnings)
ORDER BY W.monthlyEarnings DESC;

-- 3: What is the average paid leave for each continent?
SELECT continent, avg(paidLeaveTotal) AS avgPaidLeave
FROM Country
WHERE Continent IS NOT NULL
GROUP BY continent;


-- 4: Given a range of “happiness” levels (in this case between 5 and 9,) what is the average number of days of paid leave for countries within the range?
SELECT C.countryName, C.paidLeaveTotal, ACS.lifeSatisfaction
FROM Country AS C JOIN AnnualCountryStats AS ACS
ON C.countryCode = ACS.countryCode
WHERE ACS.year = 2016 AND ACS.lifeSatisfaction > 5 AND ACS.lifeSatisfaction < 9
ORDER BY C.paidLeaveTotal DESC;

-- 5: How many countries had a lower life satisfaction than the United States in 2010?
WITH lowerLS AS (
SELECT countryCode
FROM AnnualCountryStats
WHERE year = 2010 AND lifeSatisfaction < (
SELECT lifeSatisfaction
FROM AnnualCountryStats
WHERE countryCode = 'USA' AND year = 2010))
SELECT count(*)/(SELECT count(*) FROM Country)*100 AS pctLowerUS
FROM lowerLS;


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

-- 7
WITH NumCountry AS (SELECT continent, count(*) AS numCountries
FROM Country
WHERE paidLeaveTotal > 5
GROUP BY continent),
Totals AS (SELECT continent, count(*) AS totalCountries
FROM Country
GROUP BY continent)
SELECT N.continent, N.numCountries/T.totalCountries*100 AS pct
FROM NumCountry AS N JOIN Totals AS T ON N.continent = T.continent;


-- 8: What is the average life expectancy of a country with a life expectancy above 6 for each year before 2016?
SELECT ADS.year, avg(ADS.lifeExpect) AS averageLifeExpectancy
FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS
ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode
WHERE ACS.year < 2016 AND ACS.lifeSatisfaction > 6
GROUP BY ADS.year;

-- 9: What is the average life satisfaction of the bottom 10 countries with the lowest male life expectancy in 2016?
WITH BotThirty AS (
SELECT ADS.countryCode, ACS.lifeSatisfaction
FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS 
ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode 
WHERE ACS.year = 2016 AND ADS.sex = 'Male' AND ACS.lifeSatisfaction IS NOT NULL AND ADS.lifeExpect IS NOT NULL
ORDER BY ADS.lifeExpect ASC LIMIT 10)
SELECT avg(lifeSatisfaction)
FROM BotThirty;

WITH BotThirty AS (
SELECT ADS.countryCode, C.countryName, ADS.lifeExpect, ACS.lifeSatisfaction
FROM AnnualDemoStats AS ADS JOIN AnnualCountryStats AS ACS JOIN Country AS C
ON ADS.year = ACS.year AND ADS.countryCode = ACS.countryCode  AND C.countryCode = ADS.countryCode
WHERE ACS.year = 2016 AND ADS.sex = 'Male' AND ACS.lifeSatisfaction IS NOT NULL AND ADS.lifeExpect IS NOT NULL
ORDER BY ADS.lifeExpect ASC LIMIT 10)
SELECT *
FROM BotThirty;


-- 10: What is the average fertility rate of the countries with a life satisfaction above 7? 
SELECT year, avg(fertRate) AS averageFertilityRate
FROM AnnualCountryStats
WHERE lifeSatisfaction > 7
GROUP BY year;

-- 11: What sectors in the United States had a growth in monthly earnings from 2018 to 2019 for women?
WITH FirstYear AS (
SELECT sectorID, monthlyEarnings 
FROM WorksIn
WHERE year = '2018' AND sex = 'Female' AND countryCode = 'USA'),
SecondYear AS (
SELECT sectorID, monthlyEarnings 
FROM WorksIn
WHERE year = '2019' AND sex = 'Female' AND countryCode = 'USA')
SELECT F.sectorID, S.monthlyEarnings - F.monthlyEarnings AS growth
FROM FirstYear AS F JOIN SecondYear AS S ON F.sectorID = S.sectorID
WHERE S.monthlyEarnings - F.monthlyEarnings > 0 AND S.sectorID != 'Total'
ORDER BY S.monthlyEarnings - F.monthlyEarnings DESC;


-- 12: What country has the smallest gap in average monthly earnings between men and women in 2016?
WITH Diff AS (
SELECT C.countryName, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
WHERE W.sectorId = 'Total' AND W.year = 2016)
SELECT *
FROM Diff
WHERE payDiff = (SELECT min(payDiff) FROM Diff);

SELECT C.countryName, W.monthlyEarnings AS femalePay, W2.monthlyEarnings AS malePay, abs(W.monthlyEarnings - W2.monthlyEarnings) AS payDiff
FROM WorksIn AS W JOIN WorksIn AS W2 JOIN Country AS C
ON W.sex < W2.sex AND W.sectorId = W2.sectorId AND W.year = W2.year AND W.countryCode = W2.countryCode AND C.countryCode = W.countryCode
WHERE W.sectorId = 'Total' AND W.countryCode = 'USA' AND abs(W.monthlyEarnings - W2.monthlyEarnings) IS NOT NULL;

-- 13: How many countries are in both the top 50 for life satisfaction and GDP per capita?
WITH GDP AS (
SELECT countryCode
FROM AnnualCountryStats
WHERE GDPperCap IS NOT NULL AND year = 2016
ORDER BY GDPperCap DESC LIMIT 50),
LifeSat AS (
SELECT countryCode
FROM AnnualCountryStats
WHERE lifeSatisfaction IS NOT NULL AND year = 2016
ORDER BY lifeSatisfaction DESC LIMIT 50)
SELECT *
FROM GDP JOIN LifeSat ON LifeSat.countryCode = GDP.countryCode;

WITH GDP AS (
SELECT ACS.countryCode, C.countryName, ACS.GDPperCap
FROM AnnualCountryStats AS ACS JOIN Country AS C ON C.countryCode = ACS.countryCode
WHERE ACS.GDPperCap IS NOT NULL AND year = 2016
ORDER BY ACS.GDPperCap DESC LIMIT 50),
FactorTwo AS (
SELECT countryCode, lifeSatisfaction AS factor
FROM AnnualCountryStats
WHERE lifeSatisfaction IS NOT NULL AND year = 2016
ORDER BY lifeSatisfaction DESC LIMIT 50)
SELECT *
FROM GDP AS G JOIN FactorTwo AS F ON F.countryCode = G.countryCode
ORDER BY F.factor DESC;
-- 14: List the 30 countries with the lowest percentage of people using the internet in 2016 and provide an average of their GDP
WITH Bot30Internet AS (
SELECT countryCode, pctUsingInternet AS percentPopulationWithInternetAccess, GDPperCap
FROM AnnualCountryStats
WHERE year = 2016 AND pctUsingInternet IS NOT NULL
ORDER BY pctUsingInternet ASC LIMIT 30)
SELECT * FROM Bot30Internet;
WITH Bot30Internet AS (
SELECT countryCode, pctUsingInternet AS percentPopulationWithInternetAccess, GDPperCap
FROM AnnualCountryStats
WHERE year = 2016 AND pctUsingInternet IS NOT NULL
ORDER BY pctUsingInternet ASC LIMIT 30)
SELECT avg(GDPperCap) AS average FROM Bot30Internet;

-- 15: Which countries have the highest population in their continent and what is their fertility rate and continent?
WITH greater AS (SELECT continent, COUNT(countryCode) AS numCountry
                FROM Country
                WHERE paidLeaveTotal > 5
                GROUP BY continent),
total AS (SELECT continent, COUNT(countryCode) AS numCountry
                FROM Country
                GROUP BY continent)
SELECT total.continent, MAX(CAST(greater.numCountry / total.numCountry * 100 AS DECIMAL(5, 2)))
FROM greater JOIN total ON total.continent = greater.continent;
