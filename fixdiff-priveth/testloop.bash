#!/bin/bash

setDelay() {
    containers=$(docker ps | grep miner | cut -f1 -d" ")

    DELAY=$1
    LOSS=$2

    for c in $containers
    do
            docker exec $c tc qdisc change dev eth0 root netem delay ${DELAY}ms loss ${LOSS}%
    done
}

for repeat in {1..2}
do
    tar czf 0-startup-logs.tar.gz ./logs

    for i in {1..11}
    do

        delay_time=$((40*($i-1)))
        loss_pct=0
        echo "Beginning test: $i with delay: $delay_time and loss: $loss_pct"
        setDelay $delay_time $loss_pct

        sleep 1h

        tar czf ${i}-delay${delay_time}-loss${loss_pct}-logs.tar.gz ./logs

    done

    mkdir $repeat-test-run
    mv *.tar.gz $repeat-test-run
done