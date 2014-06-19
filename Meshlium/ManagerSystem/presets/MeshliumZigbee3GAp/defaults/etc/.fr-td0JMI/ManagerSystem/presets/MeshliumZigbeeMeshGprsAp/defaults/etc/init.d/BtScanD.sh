#!/bin/bash

    case "$1" in
      start)
            hwclock --hctosys
            if [ -f /var/lock/BtScanD ]
            then
               exit 1
            else
                touch /var/lock/BtScanD
                /bin/BtScan.sh &
            fi
            ;;
      stop)
            rm  /var/lock/BtScanD
            /usr/bin/killall BtScan.sh
            ;;
      *)
            echo "Uso: $0 start|stop" >&2
            exit 3
            ;;
    esac
    exit 0