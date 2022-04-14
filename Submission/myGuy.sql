DROP TABLE IF EXISTS AnnualCountryStats;
DROP TABLE IF EXISTS Country;
DROP TABLE IF EXISTS Year;
DROP TABLE IF EXISTS Year1;

CREATE TABLE Year(
	year INT,
	PRIMARY KEY (year)
);

CREATE TABLE Country(
	countryCode VARCHAR(3), 
	countryName VARCHAR(200) NOT NULL, 
	continent VARCHAR(200), 
	paidVacDays INT, 
	paidHoliday INT, 
	paidLeaveTotal INT,
	PRIMARY KEY (countryCode)
);

CREATE TABLE AnnualCountryStats(
	countryCode VARCHAR(5),
	year INT,
	pctUsingInternet FLOAT,
	GDPperCap FLOAT,
	population INT,
	fertRate FLOAT,
	lifeSatisfaction FLOAT,
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (year) REFERENCES Year(year) ON DELETE CASCADE ON UPDATE CASCADE,
	CHECK (pctUsingInternet >= 0 AND pctUsingInternet <= 100)
);

LOAD DATA LOCAL INFILE 'Year-small.txt'
INTO TABLE Year
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 2 LINES;

LOAD DATA LOCAL INFILE 'Country-small.txt'
INTO TABLE Country
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(countryCode, countryName, @vcontinent, @vpaidVacDays, @vpaidHoliday, @vpaidLeaveTotal)
SET
continent = NULLIF(@vcontinent,''),
continent = NULLIF(@vcontinent,'NULL'),
paidVacDays = NULLIF(@vpaidVacDays,'NULL'),
paidHoliday = NULLIF(@vpaidHoliday,'NULL'),
paidLeaveTotal = NULLIF(@vpaidLeaveTotal,'NULL');

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(countryCode, year, @vpctUsingInternet, @vGDPperCap, @vpopulation, @vfertRate, @vlifeSatisfaction)
SET
pctUsingInternet = NULLIF(@vpctUsingInternet,'NA'),
GDPperCap = NULLIF(@vGDPperCap,'NA'),
population = NULLIF(@vpopulation,'NA'),
fertRate = NULLIF(@vfertRate,'NA'),
lifeSatisfaction = NULLIF(@vlifeSatisfaction, 'NA');