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
#include "ns3/trace-helper.h"
#include "ns3/traffic-control-module.h"
#include "ns3/random-variable-stream.h"

using namespace ns3;

NS_LOG_COMPONENT_DEFINE("MiniProjectSimulation");

int main (int argc, char *argv[])
{
    // Users may find it convenient to turn on explicit debugging
  // for selected modules; the below lines suggest how to do this
  //
#if 0 
  LogComponentEnable ("MiniProjectSimulation", LOG_LEVEL_INFO);
#endif

  // Allow the user to override any of the defaults and the above Bind() at
  // run-time, via command-line arguments
  //
  CommandLine cmd;
  cmd.Parse (argc, argv);

  //create nodes
  NS_LOG_INFO("Creating nodes..");
  // 9 nodes (4 wheels,eps, hud , ec ,  mm, rc)
  NodeContainer terminals;
  terminals.Create(9);
  
  //2 switches
  NodeContainer csmaSwitches;
  csmaSwitches.Create(2);

  NS_LOG_INFO("Building topology...");
  CsmaHelper csma;
  csma.SetChannelAttribute ("DataRate", StringValue ("10Mbps"));
  csma.SetChannelAttribute("Delay", TimeValue( MilliSeconds(1)));

  //Create links

  //init
  NetDeviceContainer terminalDevices;
  NetDeviceContainer switchDevices1;
  NetDeviceContainer switchDevices2;

  //Sw1
  for (int i = 0; i < 4; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminals.Get(i), csmaSwitches.Get(0)));
      terminalDevices.Add(link.Get(0));
      switchDevices1.Add(link.Get(1));
    }

  //Sw2
   for (int i = 4; i < 9; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminals.Get(i), csmaSwitches.Get(1)));
      terminalDevices.Add(link.Get(0));
      switchDevices2.Add(link.Get(1));
    }

   //connection between switches
   NetDeviceContainer link = csma.Install(NodeContainer(csmaSwitches.Get(0), csmaSwitches.Get(1)));
   switchDevices1.Add(link.Get(0));
   switchDevices2.Add(link.Get(1));



   // Create the bridge netdevice, which will do the packet switching
  Ptr<Node> switchNode1 = csmaSwitches.Get (0);
  Ptr<Node> switchNode2 = csmaSwitches.Get (1);
  BridgeHelper bridge;
  bridge.Install (switchNode1, switchDevices1);
  bridge.Install (switchNode2, switchDevices2);

  // Add internet stack to the terminals
  InternetStackHelper internet;
  internet.Install (terminals);

  //IP adresses
  //NS_LOG_INFO("Assigning IP Adresses");
  //Ipv4AdressHelper ipv4;
  //ipv4.SetBase("10.1.1.0", "255.255.255.0");
  //ipv4.Assign(terminalDevicesSw1);
  //ipv4.Assign(terminalDevicesSw2);
}

