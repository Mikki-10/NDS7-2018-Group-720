<?php

/**
 * 
 */
class UNCLE
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["uncle"], $_GET["id"]);
	}

	function make_page($hash, $index)
	{
		$RPC = new RPC();
		$uncle_data = $RPC->get_Uncle_By_Block_Hash_And_Index($hash, $index);

		//echo "<pre>"; var_dump($uncle_data); echo "</pre>";

		$uncle_data = $uncle_data["result"];
		
		//echo "<pre>"; var_dump($uncle_data); echo "</pre>";

		$uncle_number = number_format(hexdec($uncle_data["number"]), 0, ',', '');

		$uncle_number = "Uncle: " . $uncle_data["hash"];
		

		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4> $uncle_number </h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<tr><td>Hash</td><td><?php echo $uncle_data["hash"]; ?></td></tr>
			<tr><td>parentHash</td><td><a href="?block=<?php echo $uncle_data["parentHash"]; ?>"><?php echo $uncle_data["parentHash"]; ?></a></td></tr>
			<tr><td>miner</td><td><a href="?account=<?php echo $uncle_data["miner"]; ?>"><?php echo $uncle_data["miner"]; ?></a></td></tr>
			<tr><td>gasLimit</td><td><?php echo hexdec($uncle_data["gasLimit"]); ?></td></tr>
			<tr><td>gasUsed</td><td><?php echo hexdec($uncle_data["gasUsed"]); ?></td></tr>
			<tr><td>difficulty</td><td><?php echo hexdec($uncle_data["difficulty"]); ?></td></tr>
			<tr><td>totalDifficulty</td><td><?php echo hexdec($uncle_data["totalDifficulty"]); ?></td></tr>
			<tr><td>timestamp</td><td><?php echo date("d-m-Y H:i:s", hexdec($uncle_data["timestamp"])); ?></td></tr>
			<tr><td>size</td><td><?php echo hexdec($uncle_data["size"]); ?></td></tr>
			<!--<tr><td>transactions</td><td><?php //echo count($uncle_data["transactions"]); ?></td></tr>-->
			<tr><td>uncles</td><td><?php echo count($uncle_data["uncles"]); ?></td></tr>
			<tr><td>extraData</td><td><?php echo $uncle_data["extraData"]; ?></td></tr>
			<tr><td>logsBloom</td><td><?php echo $uncle_data["logsBloom"]; ?></td></tr>
			<tr><td>mixHash</td><td><?php echo $uncle_data["mixHash"]; ?></td></tr>
			<tr><td>nonce</td><td><?php echo $uncle_data["nonce"]; ?></td></tr>
			<tr><td>receiptsRoot</td><td><?php echo $uncle_data["receiptsRoot"]; ?></td></tr>
			<tr><td>sha3Uncles</td><td><?php echo $uncle_data["sha3Uncles"]; ?></td></tr>
			<tr><td>stateRoot</td><td><?php echo $uncle_data["stateRoot"]; ?></td></tr>
			<tr><td>transactionsRoot</td><td><?php echo $uncle_data["transactionsRoot"]; ?></td></tr>
		</tbody>
		</table>
		</div>
		<?php
	}

}

?>