# Meshlium Command Line

## Change Xbee Configuration

We can change the Xbee default configurtion parameter by using [AT command](http://www.digi.com/support/kbase/kbaseresultdetl?id=2205) inside Meshlium router.

First, we need to run the serial port program `/bin/capturer` comes with Meshlium
     
    capturer S0 38400

Then, we will activate AT command mode and execute AT commands to the Xbee
  
    +++OK
    ...Your_AT_Command_Here...Enter_Key

Lastly, we need to write the changed configuration into Xbee memory

    ATWR

# Problem and Solution

The meshlium router software has some bugs and we need to rectify those bugs by using the below solutions.

## Linux OS Time Drift

Some of the meshlium router may experience time drift after running OS for a while. We can use crontab to sync time with NTP server every 10 minutue:

    crontab -e
    */10 * * * * /usr/sbin/ntpdate -s ntp.comp.nus.edu.sg

## 
