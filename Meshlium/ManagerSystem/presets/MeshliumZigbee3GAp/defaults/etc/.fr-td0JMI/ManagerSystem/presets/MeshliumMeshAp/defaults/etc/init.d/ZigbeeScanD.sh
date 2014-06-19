#!/bin/bash

    case "$1" in
      start)
            if [ -f /var/lock/ZigbeeScanD ]
            then
               exit 1
            else
                touch /var/lock/ZigbeeScanD
                /bin/zigbeeStorer &
            fi
            ;;
      stop)
            rm  /var/lock/ZigbeeScanD
            /usr/bin/killall zigbeeStorer
            ;;
      *)
            echo "Uso: $0 start|stop" >&2
            exit 3
            ;;
    esac
    exit 0