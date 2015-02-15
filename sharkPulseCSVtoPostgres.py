import psycopg2
f = open('sharkPulseMobileRecord.csv', 'r')
conn = psycopg2.connect(database="XXXXXX",user="XXXXX", password="XXXXXX")
cursor = conn.cursor()
for line in f:
    # print len(line.split(","))
    lineSplit = line.split(",")

    dateVal = lineSplit[4]
    timeVal = lineSplit[5]
    userEmailVal = lineSplit[3]
    speciesVal = lineSplit[0]
    latVal = lineSplit[1]
    longVal = lineSplit[2]
    imgVal = '/uploads/'+lineSplit[6]
    cursor.execute("insert into mobile_table (date, time, users_email, species_name, latitude, longitude, img_name) values (%s,%s,%s,%s,%s,%s,%s);",
    (dateVal, timeVal, userEmailVal, speciesVal,latVal, longVal, imgVal,))

conn.commit()
cursor.close()
conn.close()
