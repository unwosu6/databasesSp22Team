CREATE TABLE Year(
	year INT,
	PRIMARY KEY (year)
);

CREATE TABLE Demographic(
	sex VARCHAR(10),
	PRIMARY KEY (sex)
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
	monthlyEarnings FLOAT,
	PRIMARY KEY (sectorID, countryCode, year, sex),
	FOREIGN KEY (year) REFERENCES Year(year) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (countryCode) REFERENCES Country(countryCode),
	FOREIGN KEY (sex) REFERENCES Demographic(sex)
);

LOAD DATA LOCAL INFILE 'Year.txt'
INTO TABLE Year
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 2 LINES;

LOAD DATA LOCAL INFILE 'Demographic.txt'
INTO TABLE Demographic
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE 'Country.txt'
INTO TABLE Country
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(countryCode, countryName, @vcontinent, @vpaidVacDays, @vpaidHoliday, @vpaidLeaveTotal)
SET
continent = NULLIF(@vcontinent,''),
continent = NULLIF(@vcontinent,'NA'),
paidVacDays = NULLIF(@vpaidVacDays,'NA'),
paidHoliday = NULLIF(@vpaidHoliday,'NA'),
paidLeaveTotal = NULLIF(@vpaidLeaveTotal,'NA');

LOAD DATA LOCAL INFILE 'AnnualCountryStats.txt'
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

LOAD DATA LOCAL INFILE 'AnnualDemoStats.txt'
INTO TABLE AnnualDemoStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(countryCode, year, sex, @vlaborForcePartipation, @vpctAdvancedEdu, @vpctBasicEdu, @vlifeExpect, @vliteracyRate)
SET
laborForcePartipation = NULLIF(@vlaborForcePartipation,'NA'),
pctAdvancedEdu = NULLIF(@vpctAdvancedEdu,'NA'),
pctBasicEdu = NULLIF(@vpctBasicEdu,'NA'),
lifeExpect = NULLIF(@vlifeExpect,'NA'),
literacyRate = NULLIF(@vliteracyRate, 'NA');

LOAD DATA LOCAL INFILE 'WorksIn.txt'
INTO TABLE WorksIn
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(sectorID, countryCode, year, sex, @vmonthlyEarnings)
SET
monthlyEarnings = NULLIF(@vmonthlyEarnings,'NA');