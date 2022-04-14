DROP TABLE IF EXISTS AnnualCountryStats;

CREATE TABLE AnnualCountryStats(
	countryCode VARCHAR(5),
	year INT,
	pctUsingInternet FLOAT,
	GDPperCap FLOAT,
	population INT,
	fertRate FLOAT,
	lifeExpectMale FLOAT,
	lifeExpectFem FLOAT,
	PRIMARY KEY (countryCode, year)
);

CREATE TABLE AnnualDemoStats(
	countryCode VARCHAR(5),
	year INT,
	sex VARCHAR(10),
	laborForcePartipation FLOAT
	pctAdvancedEdu FLOAT,
	pctBasicEdu FLOAT,
	lifeExpect INT,
	fertRate FLOAT,
	literacyRate FLOAT,
	PRIMARY KEY (countryCode, year, sex)
);

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'AnnualDemoStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;
