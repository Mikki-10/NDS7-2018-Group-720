#!/bin/bash
containers=$(docker ps | grep miner | cut -f1 -d" ")

DELAY=100
LOSS=0

for c in $containers
do
	docker exec -d $c tc qdisc change dev eth0 root netem delay ${DELAY}ms loss ${LOSS}%
done
