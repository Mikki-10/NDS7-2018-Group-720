if [ ! -d "/gethdata/${DOCKER_NAME}" ]; then
    echo "Doing first setup"
    mkdir /gethdata/${DOCKER_NAME}
    cp -r /gethinit/* /gethdata/${DOCKER_NAME}
fi

#if [ $DELAY == "on" ]
#then 
#    echo "tc qdisc add dev eth0 root netem delay ${MEAN}ms ${VARIANCE}ms distribution normal"
#    tc qdisc add dev eth0 root netem delay ${MEAN}ms ${VARIANCE}ms distribution normal
#fi


if [ $LOGGING == "on" ]
then
    geth \
        --identity $DOCKER_NAME \
        --rpcvhosts=* \
        --rpc \
        --rpcaddr $(hostname -i) \
        --ethstats $DOCKER_NAME:nDs2018@webstats-frontend:3000 \
        --rpcapi="db,eth,net,web3,personal,miner,admin" \
        --datadir=/gethdata/${DOCKER_NAME} \
        --networkid=45686 \
        --rpccorsdomain "http://$(hostname -i):8000" \
        --verbosity $VERBOSITY \
        --nodiscover \
        2> /logpath/${DOCKER_NAME}_log.txt
else
    geth \
        --identity $DOCKER_NAME \
        --rpcvhosts=* \
        --rpc \
        --rpcaddr $(hostname -i) \
        --ethstats $DOCKER_NAME:nDs2018@webstats-frontend:3000 \
        --rpcapi="db,eth,net,web3,personal,miner,admin" \
        --networkid=45686 \
        --verbosity 4 \
        --rpccorsdomain "http://$(hostname -i):8000" \
fi