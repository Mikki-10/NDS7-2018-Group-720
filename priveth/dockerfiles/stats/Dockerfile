FROM ethereum/client-go

RUN apk update
RUN apk add nodejs
RUN apk add nodejs-npm
RUN apk add git

RUN git clone https://github.com/cubedro/eth-netstats.git

RUN cd /eth-netstats && npm install && npm install -g grunt-cli && grunt

RUN git clone https://github.com/cubedro/eth-net-intelligence-api.git
ADD app.json /eth-net-intelligence-api/app.json

RUN npm install pm2 -g



RUN mkdir /gethdata

ADD run.sh /gethdata
ADD genesis.json /gethdata

RUN chmod +x /gethdata/run.sh
RUN geth --datadir=/gethdata init /gethdata/genesis.json
RUN mkdir -p ~/.ethash && geth --datadir /gethdata makedag 0 ~/.ethash


ENTRYPOINT ["/bin/sh", "/gethdata/run.sh"]