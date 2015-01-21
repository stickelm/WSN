### Steps

Format SD card, install noobs raspbian, change timezone/keyboard
```
sudo apt-get update
sudo apt-get install minicom -y
sudo apt-get install ddclient -y
# To run ddclient as a daemon, please set run_daemon to 'true' in /etc/default/ddclient
# And set run_ipup to false
```

Go to dyndns.org or similar site to [generate ddclient configuration file](https://account.dyn.com/tools/clientconfig.html). 

Example as the below: (Notice you can use "Updater Client Key" to replace your password)
```
## ddclient configuration file
daemon=600
# check every 600 seconds
syslog=yes
# log update msgs to syslog
mail-failure=##YOUR_EMAIL## # Mail failed updates to user
pid=/var/run/ddclient.pid
# record PID in file.
## Detect IP with our CheckIP server
use=web, web=checkip.dyndns.com/, web-skip='IP Address'
## DynDNS username and password here
login=##YOUR USERNAME##
password=##YOUR PASSWORD##
## Default options
protocol=dyndns2
server=members.dyndns.org
## Dynamic DNS hosts
domain.dyndns.org
```