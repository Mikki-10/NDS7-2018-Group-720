FROM ethereum/client-go

RUN apk update
RUN apk add nodejs
RUN apk add nodejs-npm
RUN apk add git
RUN npm install -g bower
RUN apk add python make

RUN git clone https://github.com/carsenk/explorer
RUN cd explorer && npm install && bower install

RUN mkdir /setup
ADD run.sh /setup

RUN mkdir /gethinit
ADD genesis.json /gethinit
RUN geth --datadir=/gethinit init /gethinit/genesis.json

ENTRYPOINT ["/bin/sh", "/setup/run.sh"]