cd eth-netstats
geth --datadir=/gethdata init /setup/genesis.json
geth --rpc --rpcaddr $(hostname -i) --datadir=/gethdata --networkid=44686 --verbosity 4 --bootnodes=enode://a93cc3de8693e2ad879df9b3c306c1b9752b49d1550615825e5049528c8b109b5dbdf9847f1b6cae463f4321118b6126c673890c7ad2f706c57b466bbcf66a08@172.18.0.2:30301 & pm2 start /eth-net-intelligence-api/app.json & WS_SECRET=nDs2018 npm start