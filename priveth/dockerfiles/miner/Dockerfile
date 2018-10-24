FROM ethereum/client-go

RUN mkdir /gethinit
RUN mkdir /logpath
RUN mkdir /gethdata

ADD run.sh /gethinit
ADD genesis.json /gethinit

RUN chmod +x /gethinit/run.sh
RUN geth --datadir=/gethinit init /gethinit/genesis.json
RUN mkdir -p ~/.ethash && geth --datadir /gethinit makedag 0 ~/.ethash

ENTRYPOINT ["/bin/sh", "/gethinit/run.sh"]