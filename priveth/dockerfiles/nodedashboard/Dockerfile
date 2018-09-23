FROM ethereum/client-go

RUN apk update
RUN apk add nodejs
RUN apk add nodejs-npm
RUN apk add git
RUN apk add yarn

RUN git clone https://github.com/ethereum/go-ethereum

# Make web server accessible from anywhere
RUN sed -i -e 's/webpack-dev-server --port 8081/webpack-dev-server --host 0.0.0.0 --port 8081/g' /go-ethereum/dashboard/assets/package.json

RUN (cd /go-ethereum/dashboard/assets && yarn install)

RUN mkdir /webstats
ADD run.sh /webstats

ENTRYPOINT ["/bin/sh", "/webstats/run.sh"]