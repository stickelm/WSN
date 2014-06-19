#!/bin/bash

killall wvdial

route del default

eth0=$(ifconfig eth0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')

route add default gw `cat /mnt/user/.ethGateway`

iptables -F

ath0=$(ifconfig ath0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')

/usr/local/sbin/nat.sh ath0 eth0 $ath0/24