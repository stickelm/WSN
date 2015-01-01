Direct Access UNICAST - 868/900MHz + Change of Encryption Key
#./otap -send --file prog001.cpp.hex --mac 0013a2004061097c --mode UNICAST --new_enckey secretPassw12345 --deliveries 1 --pid prog001

Direct Access MULTICAST - 868/900MHz + Change of Encryption Key
#./otap -send --file test.cpp.hex --mac 0013a2004061097c,0013a20041615623 --mode MULTICAST --new_enckey secretPassw12345 --deliveries 2 --pid prog001

Direct Access BROADCAST - 868/900MHz
#./otap -send --file test.cpp.hex --mode BROADCAST --deliveries 2 --pid prog001
