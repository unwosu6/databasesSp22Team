i1 = open('annualcountrylifesatis.txt', 'r')
L = i1.readlines()
i1.close()

years = L[0].split('\t')
yearIndDict = {}
o3 = open('Year.txt', 'w')
i = 4
for year in years[4:]:
    year = year[:4]
    yearIndDict[i] = year
    o3.write(year + '\n')
    i += 1
o3.close()

L = L[1:]
 
#find all attributes
attr = []
ind = 0
while True:
    name = L[ind].split('\t')[2]
    if name in attr:
        break
    ind += 1
    attr.append(name)

# create dict to translate country names to codes
i4 = open('countryAndCodes.txt','r')
L4 = i4.readlines()
i4.close()
countryToCode = {}
for line in L4:
    myLine = line[:-1].split('\t')
    country = myLine[0]
    code = myLine[2]
    countryToCode[country] = code

countryDict = {}
for line in L:
    name = line.split('\t')[0]
    code = line.split('\t')[1]
    if code not in countryDict:
        countryDict[code] = []
        countryDict[code].append(name)
    if name not in countryToCode:
        countryToCode[name] = code
countryToCode['Russia'] = 'RUS'
# create Country.txt
i2 = open('continent.txt', 'r')
L2 = i2.readlines()
i2.close()
for line in L2:
    myLine = line[:-1].split('\t')
    country = myLine[1]
    code = myLine[2]
    #if code not in countryDict:
        # print('no info for country: ' + country)
    if code in countryDict:
        countryToCode[country] = code
        continent = myLine[6]
        countryDict[code].append(continent)

i3 = open('vacation-days.csv','r')
L3 = i3.readlines()
i3.close()
for line in L3[1:]:
    myLine = line[:-1].split(',')
    country = myLine[0]
    if country not in countryToCode:
        print("cannot find country: " + country + " look in continent?")
    else:
        code = countryToCode[country]
        if code in countryDict:
            countryDict[code].extend(myLine[1:])
o2 = open('Country.txt', 'w')
o2.write('countryCode\tcountryName\tcontinent\tpaidVacDays\tpaidHolidy\tpaidLeaveTotal\n')
for code, countryList in countryDict.items():
    myTuple = code + '\t'
    for val in countryList:
        myTuple += str(val) + '\t'
    myTuple = myTuple[:-1] + '\n'
    o2.write(myTuple)
o2.close()

#create AnnualCountryStats.txt
o1 = open('AnnualCountryStats.txt', 'w')
attributes = 'countryCode\tyear\tpctUsingInternet\tGDPperCap\tpopulation\tfertRate\n'
o1.write(attributes)
countryStats = {}
for line in L:
    myLine = line[:-1].split('\t')
    code = myLine[1]
    series = myLine[3]
    if code not in countryStats:
        countryStats[code] = {}
    for i in range(4,len(myLine)):
        val = myLine[i]
        if i not in countryStats[code]:
            countryStats[code][i] = []
        countryStats[code][i].append(val)
#print(countryStats['AFG'])
for code, yearDict in countryStats.items():
    if code != '':
        for yearNum, attributes in yearDict.items():
            myTuple = code + '\t' + yearIndDict[yearNum] + '\t'
            for attri in attributes:
                myTuple += str(attri) + '\t'
            myTuple = myTuple[:-1]+'\n'
            # print(myTuple)
            o1.write(myTuple)
o1.close()

o4 = open('countryToCode.txt', 'w')
for country, code in countryToCode.items():
    o4.write(country + '\t' + code + '\n')
o4.close()
