FROM ubuntu:18.04

RUN apt-get update \
    && apt-get -y install iproute2 \
    && rm -rf /var/lib/apt/lists/*

ENTRYPOINT ["/bin/sh", "/transactor/jank.sh"]
CMD []