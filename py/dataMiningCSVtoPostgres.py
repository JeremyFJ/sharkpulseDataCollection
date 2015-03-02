import psycopg2
f = open('oceanOcean_shark_2014-01-01_2015-01-01_-180_-90_180_90.csv', 'r')
conn = psycopg2.connect(database="x",user="y", password="y")
cursor = conn.cursor()
for line in f:
    # print len(line.split(","))
    lineSplit = line.split(",")
    latitude = lineSplit[0]
    longitude = lineSplit[1]
    date = lineSplit[2].split()
    dateTaken = date[0]+"\""
    timeTaken = "\"" + date[1]
    imgURL = lineSplit[4]
    print "Record: ", latitude, longitude, dateTaken, timeTaken, imgURL,
    #cursor.execute("insert into data_mining (date, time, latitude, longitude, img_name) values (%s,%s,%s,%s,%s);",
    #(dateTaken, timeTaken,latitude, longitude, imgURL,))

#conn.commit()
#cursor.close()
conn.close()


