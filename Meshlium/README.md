## Problem and Solution

The meshlium router software has some bugs and we need to rectify those bugs by using the below solutions.

### Linux OS Time Drift

Some of the meshlium router may experience time drift after running OS for a while.

We can Use crontab to sync time with NTP server every 10 minutue:

    crontab -e
    */10 * * * * /usr/sbin/ntpdate -s ntp.comp.nus.edu.sg
