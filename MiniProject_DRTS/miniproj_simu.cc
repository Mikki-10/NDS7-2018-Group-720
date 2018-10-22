// Network topology
//
//        w3     w4
//        |      | 
//       ---------------eps1
//       | Switch |-----hud1
//       -----------------ec1
//        |      |
//        |      |
//       ----------
//       | Switch |-----mm1
//       ----------
//        |   |  |
//        |   |  |
//        w1 rc1 w2
//
//

#include <iostream>
#include <fstream>

#include "ns3/core-module.h"
#include "ns3/network-module.h"
#include "ns3/applications-module.h"
#include "ns3/bridge-module.h"
#include "ns3/csma-module.h"
#include "ns3/internet-module.h"

using namespace ns3;

NS_LOG_COMPONENT_DEFINE("MiniProjectSimulation");

int main (int argc, char *argv[])
{
    // Users may find it convenient to turn on explicit debugging
  // for selected modules; the below lines suggest how to do this
  //
#if 0 
  LogComponentEnable ("CsmaBridgeExample", LOG_LEVEL_INFO);
#endif

  // Allow the user to override any of the defaults and the above Bind() at
  // run-time, via command-line arguments
  //
  CommandLine cmd;
  cmd.Parse (argc, argv);

  //create nodes
  NS_LOG_INFO("Creating nodes..");
  // 9 nodes (4 wheels,eps, hud , ec ,  mm, rc)
  NodeContainer terminalsSw1;
  NodeContainer terminalsSw2;
  terminalsSw1.Create(4);
  terminalsSw2.Create(5);
  
  //2 switches
  NodeContainer csmaSwitches;
  csmaSwitches.Create(2);

  NS_LOG_INFO("Building topology...");
  CsmaHelper csma;
  csma.SetChannelAttribute ("DataRate", DataRateValue (100000000));
  csma.SetChannelAttribute("Delay", TimeValue(MiliSeconds(0)));

  //Create links

  //init
  NetDeviceContainer terminalDevicesSw1;
  NetDeviceContainer terminalDevicesSw2;
  NetDeviceContainer switchDevices1;
  NetDeviceContainer switchDevices2;

  //Sw1
  for (int i = 0; i < 4; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminalsSw1.Get(i), csmaSwitches(0)));
      terminalDevicesSw1.add(link.Get(0));
      switchDevices1.Add(link.Get(1));
    }

  //Sw2
   for (int i = 0; i < 5; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminalsSw2.Get(i), csmaSwitches(1)));
      terminalDevicesSw2.add(link.Get(0));
      switchDevices2.Add(link.Get(1));
    }

   //connection between switches
   NetDeviceContainer link = csma.Install(csmaSwitches(0), csmaSwitches(1));
   switchDevices1.Add(link.Get(0));
   switchDevices2.Add(link.Get(1));



   // Create the bridge netdevice, which will do the packet switching
  Ptr<Node> switchNode1 = csmaSwitches.Get (0);
  Ptr<Node> switchNode2 = csmaSwitches.Get (1);
  BridgeHelper bridge;
  bridge.Install (switchNode1, switchDevices1);
  bridge.Install (switchNode2, switchDevices2);
  bridge.Install (switchNode1 , switchNode2 );

  // Add internet stack to the terminals
  InternetStackHelper internet;
  internet.Install (terminalsSw1);
  internet.Install (terminalsSw2);

  //IP adresses
  NS_LOG_INFO("Assigning IP Adresses");
  Ipv4AdressHelper ipv4;
  ipv4.SetBase("10.1.1.0", "255.255.255.0");
  ipv4.Assign(terminalDevicesSw1);
  ipv4.Assign(terminalDevicesSw2);
}

