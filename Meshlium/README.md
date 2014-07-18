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


#### Upgrade MySQL from 5.0 to 5.1 in Debian 5.0

[Reference Website 1](http://www.monkeedev.co.uk/blog/2009/03/23/installing-mysql-51-on-debian-and-ubuntu-using-apt/)

[Reference Website 2](https://forum.linode.com/viewtopic.php?t=6530%3E)

    # You need to disable the read-only file system by issuing the below command
    mount -n -o remount /
    # Stop Mysql
    /etc/init.d/mysql stop
    
    vi /etc/apt/sources.list 
    # change to the below format
    deb http://archive.debian.org/debian/ lenny main contrib non-free
    deb http://security.debian.org/ testing/updates main contrib non-free
    deb http://archive.debian.org/debian-archive/backports.org lenny-backports main
    # Custom repositories
    deb http://www.voyage.hk/dists/0.6 ./
    
    #save the file 
    apt-get update 
    apt-get -t lenny-backports install mysql-server-5.1
    
    # Add the package information so you get updates: 
     nano /etc/apt/preferences 
     
     Add: 
     Package: * 
     Pin: release a=lenny-backports 
     Pin-Priority: 200 
     
    # Then you can edit /etc/mysql/my.cnf to disable "skip-bdb"
    vi /etc/mysql/my.cnf
    # put a # in front of skip-bdb
    # Start Mysql
    /etc/init.d/mysql start

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
    # create / copy necessary user name and password

Moving DB is a bit tricky if you encoutered other issues. Just Goolge to find those solutions.


