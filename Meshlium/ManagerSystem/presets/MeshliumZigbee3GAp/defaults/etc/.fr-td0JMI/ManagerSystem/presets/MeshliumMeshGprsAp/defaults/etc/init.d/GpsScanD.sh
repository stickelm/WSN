#!/bin/bash

    case "$1" in
      start)
            if [ -f /var/lock/GpsScanD ]
            then
               exit 1
            else
                touch /var/lock/GpsScanD
                /bin/GpsScan.sh &
            fi
            ;;
      stop)
            rm  /var/lock/GpsScanD
            /usr/bin/killall GpsScan.sh
            ;;
      *)
            echo "Uso: $0 start|stop" >&2
            exit 3
            ;;
    esac
    exit 0