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

for repeat in {1..3}
do
    echo "Beginning repeat " $repeat
    tar czf 0-startup-logs.tar.gz ./logs

    for i in {0..10}
    do
        echo "Beginning test " $i
        delay_time=0
        loss_pct=$(($i-1))
        echo "Beginning test: $i with delay: $delay_time and loss: $loss_pct"
        setDelay $delay_time $loss_pct

        sleep 1h

        tar czf ${i}-delay${delay_time}-loss${loss_pct}-logs.tar.gz ./logs
        echo "End of teset " $i
    done

    mkdir $repeat-test-run
    mv *.tar.gz $repeat-test-run
done
