DROP TABLE IF EXISTS AnnualCountryStats;

CREATE TABLE AnnualCountryStats(
	countryCode VARCHAR(5),
	year INT,
	pctUsingInternet FLOAT,
	GDPperCap FLOAT,
	population INT,
	fertRate FLOAT,
	lifeSatisfaction FLOAT
);

LOAD DATA LOCAL INFILE 'AnnualCountryStats-small.txt'
INTO TABLE AnnualCountryStats
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;