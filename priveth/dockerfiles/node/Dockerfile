FROM ethereum/client-go

RUN mkdir /gethinit
RUN mkdir /gethdata

RUN apk update 
RUN apk add iproute2

RUN mkdir /lib/tc
RUN cp /usr/lib/tc/* /lib/tc

ADD run.sh /gethinit
ADD genesis.json /gethinit

RUN chmod +x /gethinit/run.sh
RUN geth --datadir=/gethinit init /gethinit/genesis.json

ENTRYPOINT ["/bin/sh", "/gethinit/run.sh"]