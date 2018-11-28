FROM ethereum/client-go:alltools-stable

RUN mkdir /gethdata

# Use this for random bootnode url:
#RUN bootnode --genkey=/gethdata/boot.key

ADD run.sh /gethdata/
ADD boot.key /gethdata/

RUN chmod +x /gethdata/run.sh


ENTRYPOINT ["/bin/sh", "/gethdata/run.sh"]