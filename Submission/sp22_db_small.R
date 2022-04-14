worksIn <- read.table("./WorksIn.txt", header = TRUE, sep = "\t")
worksInSml <- worksIn[sample(1:nrow(worksIn), 15), ]
write.table(worksInSml,"./WorksIn-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

country <- read.table("./Country.txt", header = TRUE, sep = "\t")
countrySml <- worksIn[sample(1:nrow(country), 15), ]
write.table(countrySml,"./Country-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

acs <- read.table("./AnnualCountryStats.txt", header = TRUE)
acsSml <- na.omit(acs)
acsSml <- acsSml[sample(1:nrow(acsSml), 15), ] 
write.table(acsSml,"./AnnualCountryStats-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

ads <- read.table("./AnnualDemographicStats.txt", header = TRUE)
adsSml <- na.omit(ads)
adsSml <- adsSml[sample(1:nrow(adsSml), 15), ] 
write.table(acsSml,"./AnnualDemographicStats-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

yearSml <- bind_rows(worksInSml, acsSml, adsSml)
yearSml <- unique(subset(yearSml, select = c(year)))
yearSml <- yearSml[order(yearSml$year), ]
write.table(yearSml,"./Year-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

sex <- c("Male", "Female")
demo <- data.frame(sex)
write.table(demo,"./Demographics-small.txt",sep="\t", row.names=FALSE, col.names = TRUE, quote = FALSE)

