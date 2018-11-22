#!/bin/bash
containers=$(docker ps | grep miner | cut -f1 -d" ")

echo $containers

for i in {1..4}
do
    clist[i]=$(echo $containers | cut -d" " -f$i )
    tmp=$(docker exec ${clist[$i]} sh -c 'geth --datadir=/gethdata/${DOCKER_NAME} --exec "admin.nodeInfo.enode" attach 2>&1')
    ip=$(docker exec ${clist[$i]} sh -c 'hostname -i')
    enodes[i]=$(echo $tmp | sed 's/.*\(enode.*\)\@.*/\1/')"@"$ip":30303"
done

echo "Got info needed."

for i in {1..3}
do
    tmpenode=${enodes[$(($i+1))]}
    echo $tmpenode
    docker exec ${clist[$i]} geth --datadir=/gethdata/\${DOCKER_NAME} --exec "admin.addPeer(\"$tmpenode\")" attach
done

#first=$(echo $containers | cut -d" " -f1 )

#enode=$(docker exec $first sh -c 'geth --datadir=/gethdata/${DOCKER_NAME} --exec "admin.nodeInfo.enode" attach 2>&1')

#echo $enode | sed 's/.*\(enode.*\)\@.*/\1/'
