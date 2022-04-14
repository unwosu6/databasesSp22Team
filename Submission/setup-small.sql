CREATE TABLE Demographic(
	sex VARCHAR(10),
	PRIMARY KEY (sex)
);

CREATE TABLE Year(
	year INT,
	PRIMARY KEY (year)
);

CREATE TABLE Country(
	countryCode VARCHAR(3), 
	countryName VARCHAR(200) NOT NULL, 
	continent VARCHAR(200) NOT NULL, 
	paidVacDays INT, 
	paidHoliday INT, 
	paidLeaveTotal INT,
	PRIMARY KEY (countryCode)
);

CREATE TABLE AnnualCountryStats(
	countryCode VARCHAR(3),
	year INT,
	pctUsingInternet FLOAT,
	GDPperCap FLOAT,
	population INT,
	fertRate FLOAT,
	lifeSatisfaction FLOAT,
	PRIMARY KEY (countryCode, year),
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (year) REFERENCES Year(year) ON DELETE CASCADE ON UPDATE CASCADE,
	CHECK (pctUsingInternet >= 0 AND pctUsingInternet <= 100) 
);


CREATE TABLE AnnualDemoStats(
	countryCode VARCHAR(3),
	year INT,
	sex VARCHAR(10),
	laborForcePartipation FLOAT,
	pctAdvancedEdu FLOAT,
	pctBasicEdu FLOAT,
	lifeExpect INT,
	literacyRate FLOAT,
	PRIMARY KEY (countryCode, year, sex),
	FOREIGN KEY (year) REFERENCES Year(year) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (sex) REFERENCES Demographic(sex),
	CHECK (pctAdvancedEdu >= 0 AND pctAdvancedEdu <= 100),
	CHECK (pctBasicEdu >= 0 AND pctBasicEdu <= 100),
	CHECK (literacyRate >= 0 AND literacyRate <= 100)
);


CREATE TABLE WorksIn(
	sectorID VARCHAR(200), 
	countryCode VARCHAR(3), 
	year INT, 
	sex VARCHAR(10), 
	monthlyEarnings FLOAT NOT NULL,
	PRIMARY KEY (sectorID, countryCode, year, sex),
	FOREIGN KEY (year) REFERENCES Year(year) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (sex) REFERENCES Demographic(sex)
);

LOAD DATA LOCAL INFILE 'Year-small.txt'
INTO TABLE Year
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Country-small.txt'
INTO TABLE Country
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Demographics-small.txt'
INTO TABLE Demographic
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'AnnualDemoStats-small.txt'
INTO TABLE AnnualDemographicStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'WorksIn-small.txt'
INTO TABLE WorksIn
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;
