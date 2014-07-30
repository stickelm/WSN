#!/bin/bash
#
# Delete all program (PIDs) on all scanned nodes (MAC) in one command

# scan all nodes in broadcast mode
./otap -scan_nodes --mode BROADCAST |  grep "Node" | awk -F "-" '{print $2,$3,$4;}' | awk '{print $2;}' | \
while read i
do
    mac=$i;
    # get boot list from the waspmote by mac address
    ./otap -get_boot_list --mode UNICAST --mac $mac | grep "PID" | sed -e "s/.*PID:\([A-Za-z0-9]\{7\}\).*/\1/" | \
    while read i
    do
        # specific task such as send/delte file, reset/start_new_program etc.
        ./otap -delete_program --mode UNICAST --mac $mac --pid $i
    done
done
