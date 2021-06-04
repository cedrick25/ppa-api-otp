#!/usr/bin/python
import MySQLdb
import time
import serial

#time.sleep(10)
#f2=open('/proc/pilot/module1/enable_gsm', 'w')
#print >>f2,"1"
#f2.close()

#f3=open('/home/smsmodule/sendsms/logs', 'w')
#f3.close();

#time.sleep(60);

db = MySQLdb.connect("ppa.otp","ppa_otp","pp@0tp2k21","ppa_otp" )
phone = serial.Serial("/dev/ttyUSB0", 115200, timeout=5)
phone.write('ATZ\r')
time.sleep(0)
#phone.write('AT+CSQ?\r')
#time.sleep(2)
#response = phone.readline()
#print(response)

phone.write('AT+CMGF=1\r')
time.sleep(0)

print "STARTED"
while True:
	f = None
	try:
		f1=open('/home/smsmodule/sendsms/logs', 'w+')
		#print >>f1,"STARTED"
		cursor = db.cursor()
		db.begin();
		sql = "SELECT * FROM message WHERE message_STATUS = 0 "
		print "fetching";
		#print >>f1,"fetching"
		cursor.execute(sql)
		results = cursor.fetchall()
		
		for row in results:
			print row
			id = row[0]
			to = str(row[5])
			msg = str(row[6])
			print "id=%d,to=%s,msg=%s" % \
			(id, to, msg)
			print  >>f1,"id=%d,to=%s,msg=%s" % \
			(id, to, msg)
			
			try:
				print "PREPARING"
				phone.write('AT+CMGS="' + str(to) + '"\r')
				response = phone.readline()
				phone.write(msg+"\r")
				phone.write(chr(26))
				time.sleep(5);
				print "SENDING"
				while True:
				#while phone.readline() !='':
					data_raw = phone.readline()
					print(data_raw)
					timenow = time.strftime('%Y-%m-%d %H:%M:%S')
					if "+CMGS:" in data_raw:
						print "MESSAGE SENT"
						print >>f1,"MESSAGE SENT"
						sql = "UPDATE message SET message_STATUS = 1, message_DELIVERYTIME = '%s' WHERE message_ID = '%d'" % (timenow,id) 
						try:
							cursor.execute(sql)
							db.commit()
						except:
							db.rollback()
						break;
					if "ERROR" in data_raw:
						print data_raw
						print >>f1,"ERROR HAS BEEN OCCURED"+data_raw
						print "ERROR HAS BEEN OCCURED"
						break;
			
			finally:
				print "";
	except serial.SerialException as e:
			print e
			print "Error: unable to fecth data"
	finally:
		f1.close()
	time.sleep(0)
	
db.close()
phone.close() 
