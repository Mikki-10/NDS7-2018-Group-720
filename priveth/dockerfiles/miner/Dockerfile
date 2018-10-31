FROM ethereum/client-go

RUN mkdir /gethinit
RUN mkdir /logpath
RUN mkdir /gethdata

ADD genesis.json /gethinit

RUN geth --datadir=/gethinit init /gethinit/genesis.json
RUN mkdir -p ~/.ethash && geth --datadir /gethinit makedag 0 ~/.ethash

ADD run.sh /gethinit
RUN chmod +x /gethinit/run.sh

ENTRYPOINT ["/bin/sh", "/gethinit/run.sh"]