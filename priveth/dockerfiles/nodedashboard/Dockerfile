FROM ethereum/client-go

RUN apk update
RUN apk add nodejs
RUN apk add nodejs-npm
RUN apk add git
RUN apk add yarn

RUN git clone https://github.com/ethereum/go-ethereum
RUN (cd /go-ethereum/dashboard/assets && yarn install && yarn flow)

RUN mkdir /webstats
ADD run.sh /webstats

ENTRYPOINT ["/bin/sh", "/webstats/run.sh"]