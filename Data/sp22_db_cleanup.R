library(tidyverse)

#AnnualCountryStats
happiness <- read.csv("/Users/joannabi_general/Downloads/happiness.csv", header = TRUE)
annCountry <- read.table("/Users/joannabi_general/Downloads/AnnualCountryStats.txt", header = TRUE)
colnames(happiness) <- c("countryName","countryCode", "year", "lifeSatisfaction")
happiness <- subset (happiness, select = -countryName)
acs <- merge(annCountry, happiness, by = c("countryCode", "year"), all.x = TRUE)
acs[acs ==".."] <- NA
class(acs$pctUsingInternet) = "numeric"
class(acs$GDPperCap) = "numeric"
write.table(acs,"/Users/joannabi_general/Downloads/AnnualCountryStats.txt",sep="\t", row.names=FALSE, col.names = TRUE)

#worksIn
wages <- read.csv("/Users/joannabi_general/Downloads/monthlywages.csv", header = TRUE)
codes <- read.csv("/Users/joannabi_general/Downloads/countryCode.csv", header = TRUE)
codes <- codes[c("Country", "Alpha.3.code")]
colnames(codes) <- c("countryName", "countryCode")
colnames(wages) <- c("countryName","sex", "sectorID", "year", "monthlyEarnings")
worksIn <- merge(wages, codes, by = "countryName", all.x = TRUE)
worksIn <- worksIn[,c(3, 1, 4, 2, 5)]
write.table(worksIn,"/Users/joannabi_general/Downloads/WorksIn.txt",sep="\t", row.names=FALSE, col.names = TRUE)

