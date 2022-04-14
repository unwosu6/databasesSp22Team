DROP TABLE IF EXISTS AnnualCountryStats;

CREATE TABLE Demographic(
	sex VARCHAR(10),
	PRIMARY KEY (sex)
);

CREATE TABLE Year(
	year INT,
	PRIMARY KEY (year)
);


--CREATE TABLE Country

CREATE TABLE AnnualCountryStats(
	countryCode VARCHAR(5),
	year INT,
	pctUsingInternet FLOAT,
	GDPperCap FLOAT,
	population INT,
	fertRate FLOAT,
	lifeSatisfaction FLOAT,
	PRIMARY KEY (countryCode, year),
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (year) REFERENCES Year(year),
	CHECK (pctUsingInternet >= 0 AND pctUsingInternet <= 100) 
);

CREATE TABLE AnnualDemographicStats(
	countryCode VARCHAR(5),
	year INT,
	sex VARCHAR(10),
	laborForcePartipation FLOAT
	pctAdvancedEdu FLOAT,
	pctBasicEdu FLOAT,
	lifeExpect INT,
	fertRate FLOAT,
	literacyRate FLOAT,
	PRIMARY KEY (countryCode, year, sex),
	FOREIGN KEY (year) REFERENCES Year(year),
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (sex) REFERENCES Demographic(sex),
	CHECK (pctAdvancedEdu >= 0 AND pctAdvancedEdu <= 100),
	CHECK (pctBasicEdu >= 0 AND pctBasicEdu <= 100),
	CHECK (literacyRate >= 0 AND literacyRate <= 100)
);


CREATE TABLE WorksIn(
	sectorID, 
	countryCode, 
	year, 
	sex, 
	monthlyEarnings,
	PRIMARY KEY (sectorID, countryCode, year, sex),
	FOREIGN KEY (year) REFERENCES Year(year),
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (sex) REFERENCES Demographic(sex)
);

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'AnnualDemographicStats-small.txt'
INTO TABLE AnnualDemographicStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Year-small.txt'
INTO TABLE Year
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Demographics-small.txt'
INTO TABLE Demographic
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'WorksIn-small.txt'
INTO TABLE WorksIn
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;
