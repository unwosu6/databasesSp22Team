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
countryDict = {}
for line in L:
    name = line.split('\t')[0]
    code = line.split('\t')[1]
    countryDict[code] = name

# create Country.txt
o2 = open('Country.txt', 'w')
o2.write('countryCode countryName\n')
for code in countryDict:
    o2.write(code + '\t' + countryDict[code] + '\n')
o2.close()

#create AnnualCountryStats.txt
o1 = open('AnnualCountryStats.txt', 'w')
attributes = 'countryCode\tyear\tpctUsingInternet\tGDPperCap\tpopulation\tfertRate\tlifeExpectMale\tlifeExpectFem\n'
# o1.write(attributes)
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
