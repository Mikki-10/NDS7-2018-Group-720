FROM ethereum/client-go

RUN apk update
RUN apk add git
RUN apk add nodejs
RUN apk add nodejs-npm

RUN git clone https://github.com/cubedro/eth-net-intelligence-api.git
ADD app.json /eth-net-intelligence-api
RUN cd /eth-net-intelligence-api &&\
    npm install &&\
    npm install -g pm2

RUN mkdir stats
ADD run.sh /stats

ENTRYPOINT [ "/bin/sh", "/stats/run.sh" ]