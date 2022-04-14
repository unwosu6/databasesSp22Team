library(tidyverse)
library(stringr)

#AnnualCountryStats
happiness <- read.csv("./happiness.csv", header = TRUE) #source file
annCountry <- read.table("./AnnualCountryStats.txt", header = TRUE) #file from country.py
colnames(happiness) <- c("countryName","countryCode", "year", "lifeSatisfaction") #rename columns
happiness <- subset (happiness, select = -countryName) #leave only countryCode, year, and lifeSatisfaction 
acs <- merge(annCountry, happiness, by = c("countryCode", "year"), all.x = TRUE) #merge with AnnualCountryStats.txt, keeping all data from AnnualCountryStats.txt
acs[acs ==".."] <- NA #replace ".." with NA
class(acs$pctUsingInternet) = "numeric" #change class
class(acs$GDPperCap) = "numeric"
write.table(acs,"./AnnualCountryStats.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE) #output


#WorksIn
wages <- read.csv("./monthlywages.csv", header = TRUE) #source file
codes <- read.csv("./countryCode.csv", header = TRUE) #source file for codes
codes <- codes[c("Country", "Alpha.3.code")]  #extract columns
colnames(codes) <- c("countryName", "countryCode") #rename columns
codes$countryCode <- trimws(codes$countryCode)
colnames(wages) <- c("countryName","sex", "sectorID", "year", "monthlyEarnings")
worksIn <- merge(wages, codes, by = "countryName", all.x = TRUE)
worksIn <- subset(worksIn, sex != "Total")
#noCode <- worksIn[is.na(worksIn$countryCode),]
#noCode <- unique(subset(noCode, select = c(countryName, countryCode)))
worksIn$countryCode[worksIn$countryName == "Congo, Democratic Republic of the"] <- "COD"
worksIn$countryCode[worksIn$countryName == "Curaçao"] <- "CUW"
worksIn$countryCode[worksIn$countryName == "Czechia"] <- "CZE"
worksIn$countryCode[worksIn$countryName == "Eswatini"] <- "SWZ"
worksIn$countryCode[worksIn$countryName == "Hong Kong, China"] <- "HKG"
#will occasionally get unexpected string constant here, but for no known reason
#while copy and pasting block, will not have == part for example, but when copying only line, will have
worksIn$countryCode[worksIn$countryName == "North Macedonia"] <- "MKD" 
worksIn$countryCode[worksIn$countryName == "Occupied Palestinian Territory"] <- "PSE"
worksIn <- worksIn[,c(3, 6, 4, 2, 5)]
write.table(worksIn,"./WorksIn.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#Country
country <- read.table("./Country.txt", header = TRUE, fill = TRUE, sep = "\t") #file from country.py
colnames(country) <- c("countryCode", "countryName", "continent", "paidVacDays", "paidHoliday", "paidLeaveTotal") #rename columns
class(country$paidHoliday) <- "integer" #reclass columns
class(country$paidLeaveTotal) <- "integer"
class(country$paidVacDays) <- "integer"
country[country =="NULL"] <- NA 
wagecodes <- unique(subset(worksIn, select = c(countryCode))) #add countries that are in worksIn
country <- merge(wagecodes, country, by="countryCode", all = TRUE) 
country <- country[order(country$countryCode), ]
#setdiff(fullcodes,country)
country$countryName[country$countryCode == "COK"] <- "Cook Islands"
country$continent[country$countryCode == "COK"] <- "Oceania"
country$countryName[country$countryCode == "REU"] <- "Reunion"
country$continent[country$countryCode == "REU"] <- "Africa"
write.table(country,"./Country.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#AnnualDemoStats
ads <- read.table("./AnnualDemoStats.txt", header = TRUE) #from demographic.py
ads[ads ==".."] <- NA #replace .. with NA
class(ads$pctAdvancedEdu) = "numeric" #reclass columns
class(ads$pctBasicEdu) = "numeric"
class(ads$literacyRate) = "numeric"
write.table(ads,"./AnnualDemoStats.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#Year
year <- bind_rows(wages, acs, demo) #collect all years used 
year <- unique(subset(year, select = c(year))) #keep only unique years
year <- year[order(year$year), ] #order sequentially
write.table(year,"./Year.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#Demographics
sex <- c("Male", "Female")
demo <- data.frame(sex)
write.table(demo,"./Demographics.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)