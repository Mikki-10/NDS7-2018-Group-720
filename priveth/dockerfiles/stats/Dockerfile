FROM ethereum/client-go

RUN apk update
RUN apk add nodejs
RUN apk add nodejs-npm
RUN apk add git

RUN git clone https://github.com/cubedro/eth-netstats
RUN cd eth-netstats && npm install && npm install -g grunt-cli && grunt

RUN mkdir /webstats
ADD run.sh /webstats

ENTRYPOINT ["/bin/sh", "/webstats/run.sh"]