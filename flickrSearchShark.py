
# Working now...!
# Redirect output to html file and open in browser...
# Usage-----------
# python flickrSearchShark.py > test.html

# Based on a script by Tamara Berg and James Hays
# Grab lat/lon, medium thumbnail for a search term within a bounding box
#
# UPDATED TO USE flickrapi instead of ..2
# install with sudo easy_install flickrapi


import sys, getopt, time, socket
import csv
import subprocess
from flickrapi import FlickrAPI
from datetime import datetime

socket.setdefaulttimeout(30)  #30 second time out on sockets before they throw
#an exception.  I've been having trouble with urllib.urlopen hanging in the
#flickr API.  This will show up as exceptions.IOError.

#the time out needs to be pretty long, it seems, because the flickr servers can be slow
#to respond to our big searches.

############################################################
####  CHANGE THESE VALUES FOR SEARCH TERMS AND BOUNDING BOX
############################################################
#form the query string.

query_string = "shark"

# bounding box in lat/lon
# west, south, east, north
mybb = "-180, -90, 180, 90"
#mybb = "-40, -20, 30, 20"



current_image_num = 0


def query_flickr(fapi, key, secret):
	"""Return xml element with search query"""

	try:
		rsp = fapi.photos_search(api_key=key,
							ispublic="1",
							media="photos",
							per_page="500", # here I can modify the number of pic per page
							page="1",
							has_geo = "1",
							#bbox=mybb,
							extras = "tags, original_format, license, geo, date_taken, date_upload, o_dims, views",
							text=query_string,
							sort="date-posted-asc",
							min_taken_date = "2005-01-01",
							#accuracy="6", #6 is region level.  most things seem 10 or better.
							#min_upload_date=str(mintime),
							#max_upload_date=str(maxtime))
							##min_taken_date=str(datetime.fromtimestamp(mintime)),
							##max_taken_date=str(datetime.fromtimestamp(maxtime))
							)

		#we want to catch these failures somehow and keep going.
		time.sleep(1)
		# fapi.testFailure(rsp)

		return rsp

	except KeyboardInterrupt:
		sys.stderr.write('Keyboard exception while querying for images, exiting\n')
		raise
	except:
		raise
		#print type(inst)     # the exception instance
		#print inst.args      # arguments stored in .args
		#print inst           # __str__ allows args to printed directly
		sys.stderr.write ('Exception encountered while querying for images\n')


def print_table(photos):
	"""Print a table of query in tabular format to the console"""

	global current_image_num
	print "Latitude\tLongitude\tdatetaken\ttitle\tURL"
	for p in photos:
		if p!=None:
			b = p.attrib
			if b:

				outstring =  b['latitude'].encode("ascii","replace")
				outstring += '\t'+  b['longitude'].encode("ascii","replace")
				outstring += '\t'+  b['datetaken'].encode("ascii","replace")
				outstring += '\t'+ b['title'].encode("ascii","replace")
				MyURL = 'http://farm3.static.flickr.com/' + b['server'] + '/'+ b['id'] + "_" + b['secret'] + '.jpg'
				outstring += '\t' + MyURL
				print outstring
				current_image_num = current_image_num + 1;

def output_csv(path, photos):
	"""Create csv file of query. Takes in a desired file name and a list as parameters.
		fields: #latitude - longitutde - datetaken - dateupload - url"""

	global current_image_num
	with open(path, "wb") as csv_file:
		writer = csv.writer(csv_file, escapechar=' ', quoting=csv.QUOTE_NONE)
		for p in photos:
			if p!= None:
				b = p.attrib
				if b:
					MyURL = 'http://farm3.static.flickr.com/' + b['server'] + '/'+ b['id'] + "_" + b['secret'] + '.jpg'
					timeTaken = b['datetaken'].split()[1]
					dateTaken = b['datetaken'].split()[0]
					line = b['latitude'] + "," + b['longitude'] + "," + dateTaken+","+timeTaken + "," + MyURL
					writer.writerow([line])
					current_image_num += 1
		print "CSV file created under file name: " + path


def print_html(photos):
	"""Print html table of query. Usage: python flickSearchShark.py > example.html"""


	print '''


	<html>
	<body>
	<code>
	<table border="1">
	<tr>
	<th>Lat</th>
	<th>Lon</th>
	<th>Date</th>
	<th>Date Upload</th>
	<th>Img</th>
	</tr>
	'''
	global current_image_num
	for p in photos:
		if p!=None:
			b = p.attrib
			if b:
					#What I can do here is add a subprocess to perl script. if true, then write html to file.
					# pipe = subprocess.Popen(["perl", "shapefiles.pl", b['latitude'], b['longitude']], stdout=subprocess.PIPE)
					# result = pipe.stdout.read()
					# if(result == "true"):
					#print("latitude: " + b['latitude'] +  " , longitude: " + b['longitude'])
					#MyURL = 'http://farm3.static.flickr.com/' + b['server'] + '/'+ b['id'] + "_" + b['secret'] + "_" + b['owner'] + '.jpg'
					MyURL = 'http://farm3.static.flickr.com/' + b['server'] + '/'+ b['id'] + "_" + b['secret'] + '.jpg'
					outline = '\t<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><img src="%s" width = "300"></td></tr>' \
					%(b['latitude'],b['longitude'],b['datetaken'],b['dateupload'],MyURL)
					print outline
					current_image_num = current_image_num + 1;
	print '''</table>
	</code>
	</body>
	</html>'''
	sys.stderr.write("HTML table created.\n")

def usage():
	print "Usage - python flickSearchShark.py -q [query_string] -s [start_date] -e[end_date] -g[geo_box]"

def main(argv):
	"""
		# flickr auth information:
		# change these to your flickr api keys and secret:
		# flickrAPIKey - key
		# flickrSecret - secret
	"""

	#global current_image_num  #uncomment line to reset global variable if more than one function call is made.
	flickrAPIKey = "7bf4ce840f517255cce4295b2c753b63"  # API key of sharkPulse account
	flickrSecret = "e44408e82cb422fc"
	path = "output.csv"

	fapi = FlickrAPI(flickrAPIKey, flickrSecret)                # shared "secret"
	rsp = query_flickr(fapi, flickrAPIKey, flickrSecret)

	photos = list(rsp[0])
	total_images = len(photos)
	sys.stderr.write("Found {0} images\n".format(total_images))
	i = getattr(rsp,'photos',None)

	output_csv(path, photos)

	sys.stderr.write("Showing {0} out of {1} images...\n".format(str(current_image_num),total_images))

main(sys.argv[1:])
