#!/bin/bash
#

: <<'NOTES'
# recommend to delete all programs on the wasmpote first before running the code
# this code does: send program -> activate program for all waspmotes
# program pid name == program file name
# pid/file name string length = 7
# the template pid/file name = "wsn_xxx.hex"

# the below script can change the file name to "wsn_xxx.hex"
# end results: mv WSNA02..hex wsn_a02.hex
ls WSN* | \
while read i
do
mv $i $(echo $i | sed -e "s/\(WSNA0\)\([0-9]\.\)\.\(hex\)/wsn_a0\2\3/")
done
NOTES

# scan all available nodes and loop doing tasks
./otap -scan_nodes --mode BROADCAST --time 30 |  grep "Node" | awk -F "-" '{print $2,$3,$4;}' | awk '{print $2,$3;}' | \
while read i
do
    mac=$(echo $i | awk '{print $1;}') # e.g. 0013a200408bcec5
    wid=$(echo $i | awk '{print tolower(substr($2,length($2)-2,length($2)))}') # e.g. a05
    pid="wsn_$wid" # e.g. wsn_a05
    file="$pid.hex" # e.g. wsn_a05.hex
    # echo "$mac, $pid, $file" # debugging
    ./otap -send --mode UNICAST --mac $mac --pid $pid -file $file
    ./otap -get_boot_list --mode UNICAST --mac $mac
    ./otap -start_new_program --mode UNICAST --mac $mac --pid $pid
    #Loop to the next wasmpte
done
