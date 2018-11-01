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
		$block_data = $RPC->get_Block_By_Number($block);

		$block_data = $block_data["result"];
		
		echo "<pre>"; var_dump($block_data); echo "</pre>";

		$block_number = number_format(hexdec($block_data["number"]), 0, ',', '');

		echo "<h1> $block_number </h1>";
		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Data</th>
		  </tr>
		</thead>
		<tbody>
			<tr><td>Hash</td><td><?php echo $block_data["hash"]; ?></td></tr>
			<tr><td>parentHash</td><td><?php echo $block_data["parentHash"]; ?></td></tr>
			<tr><td>miner</td><td><?php echo $block_data["miner"]; ?></td></tr>
			<tr><td>gasLimit</td><td><?php echo $block_data["gasLimit"]; ?></td></tr>
			<tr><td>gasUsed</td><td><?php echo $block_data["gasUsed"]; ?></td></tr>
			<tr><td>difficulty</td><td><?php echo $block_data["difficulty"]; ?></td></tr>
			<tr><td>totalDifficulty</td><td><?php echo $block_data["totalDifficulty"]; ?></td></tr>
			<tr><td>timestamp</td><td><?php echo $block_data["timestamp"]; ?></td></tr>
			<tr><td>size</td><td><?php echo $block_data["size"]; ?></td></tr>
			<tr><td>transactions</td><td><?php echo $block_data["transactions"]; ?></td></tr>
			<tr><td>uncles</td><td><?php echo $block_data["uncles"]; ?></td></tr>
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
		<?php
	}
}

?>