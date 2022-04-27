i1 = open('textAnnDemoStatsFemale.txt', 'r')
L = i1.readlines()
i1.close()

i2 = open('textAnnDemoStatsMale.txt', 'r')
L2 = i2.readlines()
i2.close()

years = L[0].split('\t')
yearIndDict = {}
i = 4
for year in years[4:]:
    year = year[:4]
    yearIndDict[i] = year
    i += 1
L = L[1:]
L2 = L2[1:]
#find all attributes
attr = []
ind = 0
while True:
    name = L[ind].split('\t')[2]
    if name in attr:
        break
    ind += 1
    attr.append(name)

attr2 = []
ind = 0
while True:
    name = L2[ind].split('\t')[2]
    if name in attr2:
        break
    ind += 1
    attr2.append(name)

#for i in range(len(attr)):
#    print(str(i) + ' ' + attr[i] + ' ' + attr2[i] + '\n')

# create AnnualDeomographicStats.txt
o1 = open('AnnualDemographicStats.txt', 'w')
attributes = 'countryCode\tyear\tsex\tlaborForcePartipation\tpctAdvancedEdu\tpctBasicEdu\tlifeExpect\tliteracyRate\n'
o1.write(attributes)
countryStatsFemale = {}
for line in L:
    myLine = line[:-1].split('\t')
    code = myLine[1]
    series = myLine[3]
    if code not in countryStatsFemale:
        countryStatsFemale[code] = {}
    for i in range(4,len(myLine)):
        val = myLine[i]
        if i not in countryStatsFemale[code]:
            countryStatsFemale[code][i] = []
        countryStatsFemale[code][i].append(val)
countryStatsMale = {}
seriesOrder = [0,2,3,4,1]
num = 0
for line in L2:
    myLine = line[:-1].split('\t')
    code = myLine[1]
    series = myLine[3]
    if code not in countryStatsMale:
        countryStatsMale[code] = {}
    for i in range(4,len(myLine)):
        val = myLine[i]
        if i not in countryStatsMale[code]:
            countryStatsMale[code][i] = ['NULL', 'NULL', 'NULL', 'NULL', 'NULL']
        countryStatsMale[code][i][seriesOrder[num % 5]] = val
        num += 1
#print(countryStatsMale['AFG'])
for code, yearDict in countryStatsFemale.items():
    if code != '':
        for yearNum, attributes in yearDict.items():
            myTuple = code + '\t' + yearIndDict[yearNum] + '\t' + 'Female' + '\t'
            for attri in attributes:
                myTuple += str(attri) + '\t'
            myTuple = myTuple[:-1]+'\n'
            # print(myTuple)
            o1.write(myTuple)

for code, yearDict in countryStatsMale.items():
    if code != '':
        for yearNum, attributes in yearDict.items():
            myTuple = code + '\t' + yearIndDict[yearNum] + '\t' + 'Male' + '\t'
            for attri in attributes:
                myTuple += str(attri) + '\t'
            myTuple = myTuple[:-1]+'\n'
            # print(myTuple)
            o1.write(myTuple)
o1.close()