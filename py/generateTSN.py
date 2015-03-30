__author__ = 'edsan'

import psycopg2

pelagicConnection = psycopg2.connect(database="pelagic", user="baselineuser", password="baseline3")
itisConnection = psycopg2.connect(database="ITIS", user="baselineuser", password="baseline3")


