### Meshlium Router Info

CPU: 500MHz (Geode(TM) Integrated Processor by AMD PCS)
Memory: 256MB

[The detailed system information](https://github.com/xianlin/WSN/blob/master/Meshlium/meshlium_system_info)

### Manager Web GUI

[The source code](https://github.com/xianlin/WSN/tree/master/Meshlium/ManagerSystem)

### ESRI Restful API Integration

[The source code](https://github.com/xianlin/WSN/tree/master/Meshlium/ESRI-ArcGIS-API)

### Mysql Database
The Mysql Database [schema backup file](https://github.com/xianlin/WSN/blob/master/Meshlium/MeshliumDB_3.1.3.sql) can be used to recover the broken database.

The [sample table file](https://github.com/xianlin/WSN/blob/master/Meshlium/MeshliumDB_table_sample) shows how the sensor datas are stored inside `MeshliumDB` database.


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

The updated/corrected [ESRI API code](https://github.com/xianlin/WSN/tree/master/Meshlium/ESRI-ArcGIS-API)

The updated/corrected [Manager Web GUI code]()


There are some other minor problems we need to fix by using the below solutions.


#### Linux OS Time Drift

Some of the meshlium router may experience time drift after running OS for a while. We can use crontab to sync time with NTP server every 10 minutue:

    crontab -e
    */10 * * * * /usr/sbin/ntpdate -s ntp.comp.nus.edu.sg


#### Move MySQL Database Directory (datadir)

Due to huge amount of sensor measurement data, the database of `MeshliumDB` should be located to a larget disk or directory. If you want to move it on the fly, we should follow this guide:

    /etc/init.d/mysql stop
    mkdir /mnt/user/mysql
    # or
    rm  /mnt/user/mysql/* -rf
    # copy database files
    cp -pr /var/lib/mysql/mysql /mnt/user/mysql
    cp -pr /var/lib/mysql/MeshliumDB /mnt/user/mysql/
    cp -pr /var/lib/mysql/macaddress /mnt/user/mysql/
    # change datadir parameter    
    vim /etc/mysql/my.cnf
    datadir         = /mnt/user/mysql
    # update debian-sys-maint password at the new dir
    vim /etc/mysql/debian.cnf
    # start mysql server
    /etc/init.d/mysql start

Moving DB is a bit tricky if you encoutered other issues. Just Goolge to find those solutions.


