#!/bin/bash

setDelay() {
    containers=$(docker ps | grep miner | cut -f1 -d" ")

    DELAY=$1
    LOSS=$2

    for c in $containers
    do
            docker exec -d $c tc qdisc change dev eth0 root netem delay ${DELAY}ms loss ${LOSS}%
    done
}

tar czf 0-startup-logs.tar.gz ./logs

i=1

#for i in {1..10}
#do

delay_time=$((25*$i))
loss_pct=0
echo "Beginning test: $i with delay: $delay_time and loss: $loss_pct"
#setDelay $delay_time $loss_pct

sleep 10h

tar czf ${i}-delay${delay_time}-loss${loss_pct}-logs.tar.gz ./logs

#done
