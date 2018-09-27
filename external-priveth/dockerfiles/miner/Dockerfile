FROM ethereum/client-go

RUN mkdir /gethdata

ADD run.sh /gethdata
ADD genesis.json /gethdata

RUN chmod +x /gethdata/run.sh
RUN geth --datadir=/gethdata init /gethdata/genesis.json
RUN mkdir -p ~/.ethash && geth --datadir /gethdata makedag 0 ~/.ethash


ENTRYPOINT ["/bin/sh", "/gethdata/run.sh"]