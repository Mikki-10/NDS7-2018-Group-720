if [ ! -d "/gethdata/${DOCKER_NAME}" ]; then
    echo "Doing first setup"
    mkdir /gethdata/${DOCKER_NAME}
    cp -r /gethinit/* /gethdata/${DOCKER_NAME}
fi

# Check if we have an account
count=`ls -1 /gethdata/${DOCKER_NAME}/keystore/UTC* 2>/dev/null | wc -l`
echo $count
if [ $count -lt 1 ]
then
    echo "Making new account."
    echo $ACCOUNTPASS >> /gethdata/${DOCKER_NAME}/accountPass
    geth --datadir=/gethdata/${DOCKER_NAME} account new --password /gethdata/${DOCKER_NAME}/accountPass
    accountNumber=`ls -1 /gethdata/${DOCKER_NAME}/keystore/UTC* 2>/dev/null | awk -F "--" '{print $3}'`
    echo "Raw account number:" $accountNumber
    # Add 0x hex prefix
    accountNumber="0x$accountNumber"
else
    accountNumber=`ls -1 /gethdata/${DOCKER_NAME}/keystore/UTC* 2>/dev/null | awk -F "--" '{print $3}'`
    echo "Raw account number:" $accountNumber
    # Add 0x hex prefix
    accountNumber="0x$accountNumber"
fi

if [ $DELAY == "on" ]
then 
    echo "tc qdisc add dev eth0 root netem delay ${MEAN}ms ${VARIANCE}ms distribution normal"
    tc qdisc add dev eth0 root netem delay ${MEAN}ms ${VARIANCE}ms distribution normal
fi

if [ $PACKET_LOSS == "on" ]
then
    tc qdisc change dev eth0 root netem loss ${LOSS}%
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
        --rpccorsdomain "http://$(hostname -i):8000" \
        --verbosity $VERBOSITY \
        --bootnodes=enode://a93cc3de8693e2ad879df9b3c306c1b9752b49d1550615825e5049528c8b109b5dbdf9847f1b6cae463f4321118b6126c673890c7ad2f706c57b466bbcf66a08@172.18.0.2:30301 \
        --mine \
        --minerthreads=1 \
        --etherbase=$accountNumber \
        2> /logpath/${DOCKER_NAME}_log.txt
else
    geth \
        --identity $DOCKER_NAME \
        --rpcvhosts=* \
        --rpc \
        --rpcaddr $(hostname -i) \
        --ethstats $DOCKER_NAME:nDs2018@webstats-frontend:3000 \
        --rpcapi="db,eth,net,web3,personal,miner,admin" \
        --datadir=/gethdata/${DOCKER_NAME} \
        --rpccorsdomain "http://$(hostname -i):8000" \
        --networkid=44686 \
        --verbosity 4 \
        --bootnodes=enode://a93cc3de8693e2ad879df9b3c306c1b9752b49d1550615825e5049528c8b109b5dbdf9847f1b6cae463f4321118b6126c673890c7ad2f706c57b466bbcf66a08@172.18.0.2:30301 \
        --mine \
        --minerthreads=1 \
        --etherbase=$accountNumber
fi
