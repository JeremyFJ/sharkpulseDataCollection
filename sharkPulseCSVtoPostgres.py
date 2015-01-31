import psycopg2
f = open('sharkPulseWebRecords.csv', 'r')
conn = psycopg2.connect(database="pelagic",user="postgres", password="baselinePostgre$User")
cursor = conn.cursor()
for line in f:
    # print len(line.split(","))
    lineSplit = line.split(",")
    cursor.execute("insert into sharkpulse_temp (date, time, users_email, species_name, latitude, longitude, img_name) values (%s,%s,%s,%s,%s,%s,%s);",
    (lineSplit[4], lineSplit[5], lineSplit[3], lineSplit[0],lineSplit[1], lineSplit[2], '/uploads/'+lineSplit[6],))

conn.commit()
cursor.close()
conn.close()
