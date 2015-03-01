import psycopg2
f = open('output.csv', 'r')
conn = psycopg2.connect(database="******",user="********", password="*******")
cursor = conn.cursor()
for line in f:
    # print len(line.split(","))
    lineSplit = line.split(",")
    latitude = lineSplit[0]
    longitude = lineSplit[1]
    dateTaken = lineSplit[2]
    timeTaken = lineSplit[3]
    imgURL = lineSplit[4]
    cursor.execute("insert into data_mining (date, time, latitude, longitude, img_url) values (%s,%s,%s,%s,%s);",
    (dateTaken, timeTaken,latitude, longitude, imgURL,))

conn.commit()
cursor.close()
conn.close()
