# Private Ethereum network in Docker
This folder contains everything needed for creating a Private Ethereum network.
Simply clone the repo to a computer running docker and docker-compose.

To build the docker images, run:

```docker-compose build```

To start the network run:

```docker-compose up```

## Contents
The directory contains the following:
```
priveth
├── docker-compose.yml
└── dockerfiles
    ├── bootnode
    │   ├── Dockerfile
    │   ├── boot.key
    │   └── run.sh
    ├── miner
    │   ├── Dockerfile
    │   ├── genesis.json
    │   └── run.sh
    └── node
        ├── Dockerfile
        ├── genesis.json
        └── run.sh

```
docker-compose.yml is the file which defines the network setup and configures each container.

The directory contains three containers which are built from Dockerfiles:

##### Bootnode
This is the meeting point where all nodes initially connects. The bootnode exchanges address information so the nodes can see each other. The bootnode needs to generate a cryptographic key, which in turn is used to generate an enode URL. The nodes use the enode URL when connecting. To make the network easier to use, the key has been pre-generated and is stored in the boot.key file. When the bootnode is built, the boot.key is included in the image.

When the bootnode starts it will print its IP address and enode URL. This is controlled by the run.sh script. 
On first start it is necessary to note down the IP and enode URL which needs to be written into the miner and node run.sh scripts.
NOTE THIS should already be done. The enode URL should be static, only the bootnode IP might need updating.

##### Node
This is a regular Ethereum node. It is able to join the private network through the bootnode.
When it is built, the node will initialize its local copy of the blockchain sccording to the genesis.json. This MUST be done on all nodes and miners. Otherwise they will be unable to join the network.
The genesis.json also specifies a network id which is needed by all nodes to be able to join the network.

##### Miner
This is a variant of the node. It performs the mining operation but otherwise works just as the regular node.
NOTE: When built, the miner will pregenerate the DAG which takes some time. This is done to save time when running miners based on the image.

In the docker-compose file, the miner has been throttled by setting the cpu_quota key. This can be tweaked to make it run faster/slower.

##### URLs for reading about this:
https://hub.docker.com/r/ethereum/client-go/
https://github.com/ethereum/go-ethereum/wiki/Private-network
https://medium.com/cybermiles/running-a-quick-ethereum-private-network-for-experimentation-and-testing-6b1c23605bce

Something about the different network types. Don't know if relevant
https://ethereum.stackexchange.com/questions/10311/what-is-olympic-frontier-morden-homestead-and-ropsten-ethereum-blockchain
http://ethdocs.org/en/latest/network/test-networks.html