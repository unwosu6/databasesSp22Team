DROP TABLE IF EXISTS AnnualCountryStats;
DROP TABLE IF EXISTS Country;
DROP TABLE IF EXISTS Year;

CREATE TABLE Year(
	year INT,
	PRIMARY KEY (year)
);

CREATE TABLE Country(
	countryCode VARCHAR(3), 
	countryName VARCHAR(200) NOT NULL, 
	continent VARCHAR(200) NOT NULL, 
	paidVacDays INT, 
	paidHolidy INT, 
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
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Country-small.txt'
INTO TABLE Year
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;