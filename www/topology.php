<?php

error_reporting(E_ALL);
ini_set("display_errors", true);

// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_between($data, $start, $end)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}

// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_to($data, $end)
{
    //$data = stristr($data, $start); // Stripping all data from before $start
    //$data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}

// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_from($data, $start)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    //$stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    //$data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}




/**
 * 
 */
class RPC
{
	
	function __construct()
	{
		# code...
	}

	function request($request, $ip = RPC_NODE)
	{
		try 
		{
			#curl -H "Content-Type: application/json" -X POST --data '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}' 172.18.0.3:8545

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $ip);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_POST, 1);

			$headers = array();
			$headers[] = "Content-Type: application/json";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch) && curl_errno($ch) == 0) 
			{
			    if (curl_error($ch) != "") 
			    {
			    	die('CURL Error: ' . curl_error($ch));
			    }
			}
			curl_close ($ch);

			$result = json_decode($result, true);

			//echo "<pre>"; var_dump($result); echo "</pre>";
		} 
		catch (Exception $e) 
		{
			echo "<pre>"; var_dump($e); echo "</pre>";
		}

		return $result;
	}
}


function encodep($text) {
	 $data = utf8_encode($text);
	 $compressed = gzdeflate($data, 9);
	 return encode64($compressed);
}

function encode6bit($b) {
	 if ($b < 10) {
	      return chr(48 + $b);
	 }
	 $b -= 10;
	 if ($b < 26) {
	      return chr(65 + $b);
	 }
	 $b -= 26;
	 if ($b < 26) {
	      return chr(97 + $b);
	 }
	 $b -= 26;
	 if ($b == 0) {
	      return '-';
	 }
	 if ($b == 1) {
	      return '_';
	 }
	 return '?';
}

function append3bytes($b1, $b2, $b3) {
	 $c1 = $b1 >> 2;
	 $c2 = (($b1 & 0x3) << 4) | ($b2 >> 4);
	 $c3 = (($b2 & 0xF) << 2) | ($b3 >> 6);
	 $c4 = $b3 & 0x3F;
	 $r = "";
	 $r .= encode6bit($c1 & 0x3F);
	 $r .= encode6bit($c2 & 0x3F);
	 $r .= encode6bit($c3 & 0x3F);
	 $r .= encode6bit($c4 & 0x3F);
	 return $r;
}

function encode64($c) {
	 $str = "";
	 $len = strlen($c);
	 for ($i = 0; $i < $len; $i+=3) {
	        if ($i+2==$len) {
	              $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)), 0);
	        } else if ($i+1==$len) {
	              $str .= append3bytes(ord(substr($c, $i, 1)), 0, 0);
	        } else {
	              $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)),
	                  ord(substr($c, $i+2, 1)));
	        }
	 }
	 return $str;
}




//{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74} 
//return $this->request('{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}', $ip);

$ips = array(
			'node_0' => '172.18.0.4:8545',
			'node_1' => '172.18.0.5:8545',
			'node_2' => '172.18.0.6:8545',
			'node_3' => '172.18.0.7:8545',
			'node_4' => '172.18.0.8:8545',
			'node_5' => '172.18.0.9:8545',
			'node_6' => '172.18.0.10:8545',
			'node_7' => '172.18.0.11:8545',
			'node_8' => '172.18.0.12:8545',
			'node_9' => '172.18.0.13:8545',
			'node_10' => '172.18.0.14:8545'
			);


$RPC = new RPC();
foreach ($ips as $miner => $ip) 
{
	$temp = $RPC->request('{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}', $ip);
	foreach ($temp["result"] as $key => $results) 
	{
		$connections[$miner][$key] = $results["network"]["remoteAddress"];
	}
	//$connections[$miner]
}

//echo "<pre>"; var_dump($connections); echo "</pre>"; 

foreach ($ips as $key => $value) 
{
	$ips[$key] = scrape_to($value, ":");
}


foreach ($connections as $source_node => $node_con) 
{
	foreach ($node_con as $key => $value) 
	{
		$ip = scrape_to($value, ":");
		//var_dump($ip);
		$node_dest = array_search($ip, $ips);
		$connections_uml[$source_node][$key] = $node_dest;
	}
}

//echo "<pre>"; var_dump($connections_uml); echo "</pre>"; 

$output = "";
foreach ($connections_uml as $source_node => $miners) 
{
	$output = $output . "artifact $source_node\n";
}
foreach ($connections_uml as $source_node => $miners) 
{
	foreach ($miners as $key => $miner) 
	{
		$output = $output . "$source_node -> $miner\n";
	}
}

//echo "<pre>"; var_dump($output); echo "</pre>"; 


//$encode = encodep('Alice -> Bob: hello');
$encode = encodep($output);
$encode_url = "https://www.plantuml.com/plantuml/svg/{$encode}";

//echo file_get_contents("http://www.plantuml.com/plantuml/svg/{$encode}");


//echo '<img src="' . $encode_url . '">';


foreach ($connections_uml as $source_node => $miners) 
{
	$connections_uml[$source_node . " - (" . count($miners) . ")"] = $miners;
	unset($connections_uml[$source_node]);
}


$output = NULL;

$i = 0;
$j = 0;
foreach ($connections_uml as $source_node => $miners) 
{
	$output["nodes"][$i] = array('name' => $source_node);
	$i++;
	foreach ($miners as $key => $miner) 
	{
		$output["links"][$j] = array(
										'source' => intval(scrape_from($source_node, "_")),
										'target' => intval(scrape_from($miner, "_")),
										);
		$j++;
	}
}
file_put_contents("graphFile.json", json_encode($output));

/*
{
  "nodes":[
		{"name":"node1"},
		{"name":"node2"},
		{"name":"node3"},
		{"name":"node4"}
	],
	"links":[
		{"source":2,"target":1,"weight":1},
		{"source":0,"target":2,"weight":3}
	]
}
*/


?>


<!DOCTYPE html>
<meta charset="utf-8">
<script src="http://d3js.org/d3.v2.min.js?2.9.3"></script>
<style>

.link {
  stroke: #aaa;
}

.node text {
stroke:#333;
cursos:pointer;
}

.node circle{
stroke:#fff;
stroke-width:3px;
fill:#555;
}

</style>
<body>
<script>

var width = 1400,
    height = 900

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

var force = d3.layout.force()
    .gravity(.05)
    .distance(400)
    .charge(-100)
    .size([width, height]);

d3.json("graphFile.json", function(json) {
  force
      .nodes(json.nodes)
      .links(json.links)
      .start();

  var link = svg.selectAll(".link")
      .data(json.links)
    .enter().append("line")
      .attr("class", "link")
    .style("stroke-width", function(d) { return Math.sqrt(d.weight); });

  var node = svg.selectAll(".node")
      .data(json.nodes)
    .enter().append("g")
      .attr("class", "node")
      .call(force.drag);

  node.append("circle")
      .attr("r","5");

  node.append("text")
      .attr("dx", 12)
      .attr("dy", ".35em")
      .text(function(d) { return d.name });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
  });
});

</script>