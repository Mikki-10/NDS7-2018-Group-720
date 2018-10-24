FROM ethereum/client-go

RUN mkdir /gethinit
RUN mkdir /gethdata

ADD run.sh /gethinit
ADD genesis.json /gethinit

RUN chmod +x /gethinit/run.sh
RUN geth --datadir=/gethinit init /gethinit/genesis.json

ENTRYPOINT ["/bin/sh", "/gethinit/run.sh"]