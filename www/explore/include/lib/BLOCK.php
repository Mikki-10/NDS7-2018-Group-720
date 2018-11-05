<?php

/**
 * 
 */
class BLOCK
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["block"]);
	}

	function make_page($block)
	{
		$RPC = new RPC();
		$block_data = $RPC->get_Block($block);

		//echo "<pre>"; var_dump($block_data); echo "</pre>";

		$block_data = $block_data["result"];
		
		//echo "<pre>"; var_dump($block_data); echo "</pre>";

		$block_number = number_format(hexdec($block_data["number"]), 0, ',', '');

		if ($block_number == "0") 
		{
			$block_number = "Error";
			if ($block_data["hash"] != "") 
			{
				$block_number = "Looks like an other chain, are you comming from a uncle?";
			}
		} 
		else 
		{
			# code...
		}
		

		echo '<div class="container"><br>';

		if (is_numeric($block_number)) 
		{
			echo '<div class="row"><div class="col-md-1">';
			echo '<a class="btn btn-primary" href="?block='; echo $block_number-1; echo '" role="button">Previous</a>';
			echo '</div><div class="col-md-10 text-center">';
			echo "<h1> $block_number </h1>";
			echo '</div><div class="col-md-1">';
			echo '<a class="btn btn-primary" href="?block='; echo $block_number+1; echo '" role="button">Next</a>';
			echo "</div></div>";
		}
		else
		{
			echo '<div class="row"><div class="col-md-1">';
			echo '</div><div class="col-md-10 text-center">';
			echo "<h4> $block_number </h4>";
			echo '</div><div class="col-md-1">';
			echo "</div></div>";
		}

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<tr><td>Hash</td><td><?php echo $block_data["hash"]; ?></td></tr>
			<tr><td>parentHash</td><td><a href="?block=<?php echo $block_data["parentHash"]; ?>"><?php echo $block_data["parentHash"]; ?></a></td></tr>
			<tr><td>miner</td><td><a href="?account=<?php echo $block_data["miner"]; ?>"><?php echo $block_data["miner"]; ?></a></td></tr>
			<tr><td>gasLimit</td><td><?php echo hexdec($block_data["gasLimit"]); ?></td></tr>
			<tr><td>gasUsed</td><td><?php echo hexdec($block_data["gasUsed"]); ?></td></tr>
			<tr><td>difficulty</td><td><?php echo hexdec($block_data["difficulty"]); ?></td></tr>
			<tr><td>totalDifficulty</td><td><?php echo hexdec($block_data["totalDifficulty"]); ?></td></tr>
			<tr><td>timestamp</td><td><?php echo date("d-m-Y H:i:s", hexdec($block_data["timestamp"])); ?></td></tr>
			<tr><td>size</td><td><?php echo hexdec($block_data["size"]); ?></td></tr>
			<tr><td>transactions</td><td><?php echo count($block_data["transactions"]); ?></td></tr>
			<tr><td>uncles</td><td><?php echo count($block_data["uncles"]); ?></td></tr>
			<tr><td>extraData</td><td><?php echo $block_data["extraData"]; ?></td></tr>
			<tr><td>logsBloom</td><td><?php echo $block_data["logsBloom"]; ?></td></tr>
			<tr><td>mixHash</td><td><?php echo $block_data["mixHash"]; ?></td></tr>
			<tr><td>nonce</td><td><?php echo $block_data["nonce"]; ?></td></tr>
			<tr><td>receiptsRoot</td><td><?php echo $block_data["receiptsRoot"]; ?></td></tr>
			<tr><td>sha3Uncles</td><td><?php echo $block_data["sha3Uncles"]; ?></td></tr>
			<tr><td>stateRoot</td><td><?php echo $block_data["stateRoot"]; ?></td></tr>
			<tr><td>transactionsRoot</td><td><?php echo $block_data["transactionsRoot"]; ?></td></tr>
		</tbody>
		</table>
		</div>
		<br></br>
		<h4>Transactions</h4>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Hash</th>
		    <th>From</th>
		    <th>To</th>
		    <th>Value</th>
		  </tr>
		</thead>
		<tbody>
		<?php
		foreach ($block_data["transactions"] as $key => $value) 
		{
			echo "<tr>";
			echo '<td><a href="?tx=' . $value["hash"] . '">' . substr($value["hash"], 0, 7) . "..." . substr($value["hash"], -7) . '</a></td>';
			echo '<td><a href="?account=' . $value["from"] . '">' . substr($value["from"], 0, 7) . "..." . substr($value["from"], -7) . '</a></td>';
			echo '<td><a href="?account=' . $value["to"] . '">' . substr($value["to"], 0, 7) . "..." . substr($value["to"], -7) . '</a></td>';
			echo '<td>' . rtrim(rtrim(number_format(hexdec($value["value"])/1000000000000000000, 22, ",", "."), 0), ",") . '</td>';
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		?>

		<br></br>
		<h4>Uncles</h4>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Hash</th>
		  </tr>
		</thead>
		<tbody>
		<?php
		//var_dump($block_data["uncles"]);
		foreach ($block_data["uncles"] as $key => $value) 
		{
			echo "<tr>";
			echo '<td><a href="?uncle=' . $block_data["hash"] . '&id=' . $key . '">' . $value . '</a></td>';
			//echo '<td>' . $value . '</td>';
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		echo "</div>";
	}
}


?>
