/* -*- Mode:C++; c-file-style:"gnu"; indent-tabs-mode:nil; -*- */
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 as
 * published by the Free Software Foundation;
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Script adapted from other examples by Rasmus L. Bruun

// Network topology
//
//        t0     t1               t4    t5
//        |      |                |     |
//       ----------             ----------
//       | Switch |  ---------  | Switch | -- t8
//       ----------             ----------
//        |      |                |     |
//        t2     t3               t6    t7
//
// - CBR UDP flows from t0, t1, t4, t5 and t6
// - VBR UDP flows from t2 and t3
// - DropTail queues in switches
// - Tocken buckets limiting the outflow from t2 and t3
// - Tracing of tocken bucket quein and dropping to file 
//   "tocken-bucket-on-t2.tr"

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

NS_LOG_COMPONENT_DEFINE ("MiniProject");

// Here we define some functions used for tracing

void
PacketEnqueued(Ptr<OutputStreamWrapper> stream, Ptr< const QueueDiscItem > item)
{
  *stream->GetStream () << "+ " << Simulator::Now().GetNanoSeconds() << " [ns] Enqueued a packet " << std::endl;
}

void
PacketDequeued(Ptr<OutputStreamWrapper> stream, Ptr< const QueueDiscItem > item)
{
  *stream->GetStream () << "- " << Simulator::Now().GetNanoSeconds() << " [ns] Dequeued a packet" << std::endl;
}

void
PacketDropped(Ptr<OutputStreamWrapper> stream, Ptr< const QueueDiscItem > item)
{
  *stream->GetStream () << "d " << Simulator::Now().GetNanoSeconds() << " [ns] Dropped a packet" << std::endl;
}

/*>>> Are there any other interesting things we could trace? <<<*/

//
// Script starts here 
//

int 
main (int argc, char *argv[])
{

  //
  // Users may find it convenient to turn on explicit debugging
  // for selected modules; the below lines suggest how to do this
  //
#if 0
  LogComponentEnable ("MiniProject", LOG_LEVEL_INFO);
  LogComponentEnable ("BridgeNetDevice", LOG_LEVEL_INFO);
  LogComponentEnable ("CsmaNetDevice", LOG_LEVEL_INFO);
  LogComponentEnable ("CsmaChannel", LOG_LEVEL_INFO);
  LogComponentEnable ("CsmaNetDevice", LOG_LEVEL_INFO);
#endif

  //
  // Allow the user to override any of the defaults and the above Bind() at
  // run-time, via command-line arguments
  //
  CommandLine cmd;
  cmd.Parse (argc, argv);

  // Controll the random number generator
  RngSeedManager::SetSeed (1);
  RngSeedManager::SetRun (1);   

  //
  // Explicitly create the nodes required by the topology (shown above).
  //
  NS_LOG_INFO ("Create nodes.");
  NodeContainer terminals;
  terminals.Create (9);

  NS_LOG_INFO("Create switches.");
  NodeContainer csmaSwitches;
  csmaSwitches.Create (2);

  NS_LOG_INFO ("Build Topology");
  CsmaHelper csma;
  csma.SetChannelAttribute ("DataRate", StringValue ("10Mbps"));
  csma.SetChannelAttribute("Delay", TimeValue( MilliSeconds(1)));

  // Create the csma links, from terminals 0-3 to switch 0, from terminal 4-8 to switch 1
  // and between the switches. OBS: Be carefull about where the devices are going

  NetDeviceContainer terminalDevices;
  NetDeviceContainer switchDevices;
  NetDeviceContainer switch2Devices;

  // Connections to switch 1
  for (int i = 0; i < 4; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminals.Get(i), csmaSwitches.Get(0)));
      terminalDevices.Add(link.Get(0));
      switchDevices.Add(link.Get(1));
    }
  // Connections to switch 2
  for (int i = 4; i < 9; i++)
    {
      NetDeviceContainer link = csma.Install(NodeContainer(terminals.Get(i), csmaSwitches.Get(1)));
      terminalDevices.Add(link.Get(0));
      switch2Devices.Add(link.Get(1));
    }
  
  // Link the switches
  NetDeviceContainer link = csma.Install (NodeContainer (csmaSwitches.Get (0), csmaSwitches.Get (1)));
  switchDevices.Add (link.Get (0));
  switch2Devices.Add (link.Get (1));

  // Create the bridge netdevices, which will do the packet switching
  Ptr<Node> switchNode = csmaSwitches.Get (0);
  BridgeHelper bridge;
  bridge.Install (switchNode, switchDevices);
  switchNode = csmaSwitches.Get (1);
  bridge.Install (switchNode, switch2Devices);

  // Add internet stack to the terminals
  InternetStackHelper internet;
  internet.Install (terminals);

  //
  // Configure and install tocken bucket filters (tbf) (a queue discipline)
  //

  // Configurations
  uint32_t burst = 10000;//tokenbucket burst parameter (original value 10000)
  uint32_t mtu = 0;
  QueueSize maxS = QueueSize("20p");
  DataRate rate = DataRate ("48Mbps"); //original value 48Mbps
  DataRate peakRate = 0;

  TrafficControlHelper tch;
  tch.SetRootQueueDisc ("ns3::TbfQueueDisc",
                        "Burst", UintegerValue (burst),
                        "Mtu", UintegerValue (mtu),
                        "Rate", DataRateValue (DataRate (rate)),
                        "PeakRate", DataRateValue (DataRate (peakRate)),
                        "MaxSize", QueueSizeValue(QueueSize(maxS)));
  // Install tocken bucket on the net device connecting t2 and
  QueueDiscContainer qdiscs = tch.Install (NetDeviceContainer (terminals.Get (2)->GetDevice(0), terminals.Get (3)->GetDevice(0)));


  // We've got the "hardware" in place.  Now we need to add IP addresses.
  NS_LOG_INFO ("Assign IP Addresses.");
  Ipv4AddressHelper ipv4;
  ipv4.SetBase ("10.1.1.0", "255.255.255.0");
  Ipv4InterfaceContainer Ips = ipv4.Assign (terminalDevices);


  //
  // Create applications to send UDP datagrams
  //
  NS_LOG_INFO ("Create Applications.");
  uint16_t port = 9;   // Discard port (RFC 863)

  // Configure the various applications
  OnOffHelper wheel ("ns3::UdpSocketFactory", 
                     Address (InetSocketAddress (Ipv4Address ("10.1.1.7"), port)));
  wheel.SetConstantRate (DataRate ("10kB/s"));
  wheel.SetAttribute ("PacketSize",UintegerValue (20));
  
  OnOffHelper esc ("ns3::UdpSocketFactory", 
                     Address (InetSocketAddress (Ipv4Address ("10.1.1.8"), port)));
  esc.SetConstantRate (DataRate ("400B/s"));
  esc.SetAttribute ("PacketSize",UintegerValue (8));
  
  OnOffHelper rearCamera ("ns3::UdpSocketFactory", 
                     Address (InetSocketAddress (Ipv4Address ("10.1.1.9"), port)));
  rearCamera.SetConstantRate (DataRate ("10Mbps"));
  rearCamera.SetAttribute ("PacketSize",UintegerValue (1400));
  rearCamera.SetAttribute ("OnTime",  StringValue ("ns3::ConstantRandomVariable[Constant=0.00112]")); // 11200/10000000 [s]
  rearCamera.SetAttribute ("OffTime", StringValue ("ns3::ExponentialRandomVariable[Mean=0.00088]")); // 0.002-ontime 
  
  OnOffHelper multiMedia ("ns3::UdpSocketFactory", 
                     Address (InetSocketAddress (Ipv4Address ("10.1.1.9"), port)));
  multiMedia.SetConstantRate (DataRate ("10Mbps"));
  multiMedia.SetAttribute ("PacketSize",UintegerValue (1400));
  multiMedia.SetAttribute ("OnTime",  StringValue ("ns3::ConstantRandomVariable[Constant=0.00112]"));// 11200/10000000 [s]
  multiMedia.SetAttribute ("OffTime", StringValue ("ns3::ExponentialRandomVariable[Mean=0.00138]"));// 0.0025-ontime 

  // Install the apps on the specific nodes and collect the apps in a an appcontainer
  ApplicationContainer apps = wheel.Install (terminals.Get (0)); // Wheel 1 on t0
  apps.Add (wheel.Install (terminals.Get (1))); // Wheel 2 on t1
  apps.Add (wheel.Install (terminals.Get (4))); // Wheel 3 on t4
  apps.Add (wheel.Install (terminals.Get (5))); // Wheel 4 on t5
  apps.Add (esc.Install (terminals.Get (6))); // Electronic stability control on t6
  apps.Add (rearCamera.Install (terminals.Get (2))); // Rear camera on t2
  apps.Add (multiMedia.Install (terminals.Get (3))); // Multi media on t3

  // Specify application start and stop times
  apps.Start (Seconds (0));
  apps.Stop (Seconds (2));
  


  // Create packet sinks
  PacketSinkHelper sink ("ns3::UdpSocketFactory",
                         Address (InetSocketAddress (Ipv4Address::GetAny (), port)));
  ApplicationContainer sinks = sink.Install (terminals.Get (6));
  sinks.Add(sink.Install (terminals.Get (7)));
  sinks.Add(sink.Install (terminals.Get (8)));
  sinks.Start (Seconds (0));


  NS_LOG_INFO ("Configure Tracing.");
  
  // Configure tracing. We are interested in mean/max queue lengths and mean/max 
  // waiting times. Here, we only grab the tocken bucket filter queue discs on
  // the netdevice on terminal t2 (the rear camera). This queue disc is
  // the first one in the queueDiscContainer 'qdiscs'

  // Create stream to file
  AsciiTraceHelper ascii;
  Ptr<OutputStreamWrapper> outFile = ascii.CreateFileStream("tocken-bucket-on-t2.tr");
  /*>>> Are we interested in tracing multiple queues? should they be printed to different files? <<<*/
  Ptr<QueueDisc> q = qdiscs.Get (0);
  Ptr<QueueDisc> q2 = qdiscs.Get (1);
    /*>>> Any other queues we want to track? <<<*/

  // Setup tracing of the tocken bucket related events
  q->TraceConnectWithoutContext ("Enqueue", MakeBoundCallback (&PacketEnqueued, outFile));
  q->TraceConnectWithoutContext ("Dequeue", MakeBoundCallback (&PacketDequeued, outFile));
  q->TraceConnectWithoutContext ("Drop", MakeBoundCallback (&PacketDropped, outFile));
  /*>>> any other things we could be interested in tracing? <<<*/

  // Trace the csma events on t0 (the first wheel)
  Ptr<OutputStreamWrapper> outFile1 = ascii.CreateFileStream("wheel1.tr");  
  csma.EnableAscii (outFile1,terminalDevices.Get(0));
  // Trace the csma events on t1 (the 2nd wheel)
  Ptr<OutputStreamWrapper> outFile2 = ascii.CreateFileStream("wheel2.tr");  
  csma.EnableAscii (outFile2,terminalDevices.Get(1));
  // Trace the csma events on t4 (the 3rd wheel)
  Ptr<OutputStreamWrapper> outFile3 = ascii.CreateFileStream("wheel3.tr");  
  csma.EnableAscii (outFile3,terminalDevices.Get(4));
  // Trace the csma events on t5 (the 4th wheel)
  Ptr<OutputStreamWrapper> outFile4 = ascii.CreateFileStream("wheel4.tr");  
  csma.EnableAscii (outFile4,terminalDevices.Get(5));
  // Trace the csma events on t4 (ESP)
  Ptr<OutputStreamWrapper> outFile5 = ascii.CreateFileStream("esp.tr");  
  csma.EnableAscii (outFile5,terminalDevices.Get(6));
  //
  // Now, do the actual simulation.
  //
  NS_LOG_INFO ("Run Simulation.");
  Simulator::Stop (Seconds(2));
  Simulator::Run ();
  Simulator::Destroy ();

  // The queueDisc in the tocken bucket gathers statistics. These can be printed 
  // for inspection.
  std::cout << std::endl << "*** TC Layer statistics ***" << std::endl;
  std::cout << q->GetStats () << std::endl;
  NS_LOG_INFO ("Done.");
}
