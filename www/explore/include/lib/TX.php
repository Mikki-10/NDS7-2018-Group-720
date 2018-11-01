<?php

/**
 * 
 */
class TX
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["tx"]);
	}

	function make_page($tx_hash)
	{
		$RPC = new RPC();
		$tx_data = $RPC->get_Transaction_By_Hash($tx_hash);

		echo "<pre>"; var_dump($tx_data); echo "</pre>";

		$tx_data = $tx_data["result"];
		
		echo "<pre>"; var_dump($tx_data); echo "</pre>";

		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';

		echo '</div><div class="col-md-10 text-center">';
		echo "<h1>" . $tx_data["hash"] . "</h1>";
		echo '</div><div class="col-md-1">';

		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<tr><td>Hash</td><td><?php echo $tx_data["hash"]; ?></td></tr>
			<tr><td>blockHash</td><td><a href="?block=<?php echo $tx_data["blockHash"] ?>"><?php echo $tx_data["blockHash"]; ?></a></td></tr>
			<tr><td>blockNumber</td><td><a href="?account=<?php echo hexdec($tx_data["blockNumber"]); ?>"><?php echo hexdec($tx_data["blockNumber"]); ?></a></td></tr>
			<tr><td>from</td><td><?php echo $tx_data["from"]; ?></td></tr>
			<tr><td>to</td><td><?php echo $tx_data["to"]; ?></td></tr>
			<tr><td>value</td><td><?php echo number_format(hexdec($tx_data["value"])/1000000000000000000, 18, ",", "."); ?></td></tr>
			<tr><td>nonce</td><td><?php echo hexdec($tx_data["nonce"]); ?></td></tr>
			<tr><td>gas limit</td><td><?php echo "get this value"; ?></td></tr>
			<tr><td>gas</td><td><?php echo hexdec($tx_data["gas"]); ?></td></tr>
			<tr><td>gasPrice</td><td><?php echo number_format(hexdec($tx_data["gasPrice"])/1000000000000000000, 18, ",", "."); ?></td></tr>
			<tr><td>fee</td><td><?php echo $tx_data["gas"]*(hexdec($tx_data["gasPrice"])/1000000000000000000); ?></td></tr>
			<tr><td>data (input)</td><td><?php echo $tx_data["input"]; ?></td></tr>
			<tr><td>v</td><td><?php echo $tx_data["v"]; ?></td></tr>
			<tr><td>r</td><td><?php echo $tx_data["r"]; ?></td></tr>
			<tr><td>s</td><td><?php echo $tx_data["s"]; ?></td></tr>
		</tbody>
		</table>
		</div>
		</div>
		<?php
	}
}

?>