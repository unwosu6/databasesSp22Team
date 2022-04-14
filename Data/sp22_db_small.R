worksIn <- read.table("./worksIn.txt", header = TRUE)
worksInSml <- worksIn[sample(1:nrow(worksIn), 15), ]
write.table(worksInSml,"./WorksIn-small.txt",sep="\t", row.names=FALSE, col.names = TRUE)

acs <- read.table("./AnnualCountryStats.txt", header = TRUE)
acsSml <- na.omit(acs)
acsSml <- acsSml[sample(1:nrow(acsSml), 15), ] 
write.table(acsSml,"./AnnualCountryStats-small.txt",sep="\t", row.names=FALSE, col.names = TRUE)

ads <- read.table("./AnnualDemographicStats.txt", header = TRUE)
adsSml <- na.omit(ads)
adsSml <- adsSml[sample(1:nrow(adsSml), 15), ] 
write.table(acsSml,"./AnnualDemographicStats-small.txt",sep="\t", row.names=FALSE, col.names = TRUE)

yearSml <- bind_rows(worksInSml, acsSml, adsSml)
yearSml <- unique(subset(yearSml, select = c(year)))
yearSml <- yearSml[order(yearSml$year), ]
write.table(yearSml,"./Year-small.txt",sep="\t", row.names=FALSE, col.names = TRUE)

sex <- c("Male", "Female")
demo <- data.frame(sex)
write.table(demo,"./Demographics-small.txt",sep="\t", row.names=FALSE, col.names = TRUE)