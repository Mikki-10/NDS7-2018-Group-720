if [ ! -d "/gethdata/${DOCKER_NAME}" ]; then
    echo "Doing first setup"
    mkdir /gethdata/${DOCKER_NAME}
    cp -r /gethinit/* /gethdata/${DOCKER_NAME}
fi

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
        --networkid=44686 \
        --verbosity $VERBOSITY \
        #--rpccorsdomain "http://$(hostname -i):8000"
        --bootnodes=enode://a93cc3de8693e2ad879df9b3c306c1b9752b49d1550615825e5049528c8b109b5dbdf9847f1b6cae463f4321118b6126c673890c7ad2f706c57b466bbcf66a08@172.18.0.2:30301 \
        2> /logpath/${DOCKER_NAME}_log.txt
else
    geth \
        --identity $DOCKER_NAME \
        --rpcvhosts=* \
        --rpc \
        --rpcaddr $(hostname -i) \
        --ethstats $DOCKER_NAME:nDs2018@webstats-frontend:3000 \
        --rpcapi="db,eth,net,web3,personal,miner,admin" \
        #--datadir=/gethdata/${DOCKER_NAME} \
        --networkid=44686 \
        --verbosity 4 \
        --rpccorsdomain "http://$(hostname -i):8000"
        --bootnodes=enode://a93cc3de8693e2ad879df9b3c306c1b9752b49d1550615825e5049528c8b109b5dbdf9847f1b6cae463f4321118b6126c673890c7ad2f706c57b466bbcf66a08@172.18.0.2:30301
fi