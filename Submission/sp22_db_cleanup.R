library(tidyverse)

#AnnualCountryStats
happiness <- read.csv("./happiness.csv", header = TRUE)
annCountry <- read.table("./AnnualCountryStats.txt", header = TRUE)
colnames(happiness) <- c("countryName","countryCode", "year", "lifeSatisfaction")
happiness <- subset (happiness, select = -countryName)
acs <- merge(annCountry, happiness, by = c("countryCode", "year"), all.x = TRUE)
acs[acs ==".."] <- NA
class(acs$pctUsingInternet) = "numeric"
class(acs$GDPperCap) = "numeric"
write.table(acs,"./AnnualCountryStats.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#WorksIn
wages <- read.csv("./monthlywages.csv", header = TRUE)
codes <- read.csv("./countryCode.csv", header = TRUE)
codes <- codes[c("Country", "Alpha.3.code")]
colnames(codes) <- c("countryName", "countryCode")
colnames(wages) <- c("countryName","sex", "sectorID", "year", "monthlyEarnings")
worksIn <- merge(wages, codes, by = "countryName", all.x = TRUE)
worksIn <- subset(worksIn, sex != "Total")
worksIn <- worksIn[,c(3, 6, 4, 2, 5)]
write.table(worksIn,"./WorksIn.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#AnnualDemographicsStats
ads <- read.table("./AnnualDemographicStats.txt", header = TRUE)
ads[ads ==".."] <- NA
class(ads$pctAdvancedEdu) = "numeric"
class(ads$pctBasicEdu) = "numeric"
class(ads$literacyRate) = "numeric"
write.table(ads,"./AnnualDemographicStats.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#Year
year <- bind_rows(wages, acs, demo)
year <- unique(subset(year, select = c(year)))
year <- year[order(year$year), ]
write.table(year,"./Year.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

#Demographics
sex <- c("Male", "Female")
demo <- data.frame(sex)
write.table(demo,"./Demographics.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)