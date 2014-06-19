#!/bin/bash


case "$1" in
  start)
        s=`cat /mnt/lib/.info/total-starts`
        total=`expr 1 + $s`
        echo $total > /mnt/lib/.info/total-starts

        if [ -f /etc/network/interfaces.confirmation ]
        then
           echo "Quedan 5 minutos para reestablecer";
           sleep 60;
           echo "Quedan 4 minutos para reestablecer";
           sleep 60;
           echo "Quedan 3 minutos para reestablecer";
           sleep 60;
           echo "Quedan 2 minutos para reestablecer";
           sleep 60;
           echo "Quedan 1 minutos para reestablecer";
           sleep 60;
           if [ -f /etc/network/interfaces.confirmation ]
           then
                remountrw
              cp /etc/network/interfaces /etc/network/interfaces.backup
              cp /etc/network/interfaces.default /etc/network/interfaces
                cp /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf.default /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf
                cp /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf.default /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf
                cp /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf.default /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf
              rm /etc/network/interfaces.confirmation
                remountro
           fi
        fi

        s=`cat /mnt/lib/.info/total-starts`
        total=`expr 1 + $s`
        echo $total > /mnt/lib/.info/total-starts
        ;;
  stop)
        echo "stop" >&2
        ;;
  *)
        echo "Uso: $0 start|stop" >&2
        exit 3
        ;;
esac
exit 0