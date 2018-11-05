FROM ubuntu:18.04

RUN apt-get update \
    && apt-get -y install curl \
    && rm -rf /var/lib/apt/lists/*

ENTRYPOINT ["/bin/sh", "/transactor/jank.sh"]
CMD []