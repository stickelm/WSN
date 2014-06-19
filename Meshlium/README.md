### Manager Web GUI

### ESRI Restful API Integration

### Mysql Database

### Meshlium Command Line

#### Change Xbee Configuration

We can change the Xbee default configurtion parameter by using [AT command](http://www.digi.com/support/kbase/kbaseresultdetl?id=2205) inside Meshlium router.

First, we need to run the serial port program `/bin/capturer` comes with Meshlium
     
    capturer S0 38400

Then, we will activate AT command mode and execute AT commands to the Xbee
  
    +++OK
    ...Your_AT_Command_Here...Enter_Key

Lastly, we need to write the changed configuration into Xbee memory

    ATWR

#### Running Program At Background

For example, the below command will run the java program `sensorParser.jar` at the background without displaying any error messages or output to the terminal screen even after user logout the terminal.

    nohup java -jar /bin/sensorParser.jar >/dev/null 2>&1 &

### Problem and Solution

The meshlium router software has some bugs (such as display Spanish words instead of English in the API call, wrong regular expression in the php code etc). 

The updated/corrected (ESRI API code](https://github.com/xianlin/WSN/tree/master/Meshlium/ESRI-ArcGIS-API)

The updated/corrected (Manager Web GUI code]()


There are some other minor problems we need to fix by using the below solutions.


#### Linux OS Time Drift

Some of the meshlium router may experience time drift after running OS for a while. We can use crontab to sync time with NTP server every 10 minutue:

    crontab -e
    */10 * * * * /usr/sbin/ntpdate -s ntp.comp.nus.edu.sg






