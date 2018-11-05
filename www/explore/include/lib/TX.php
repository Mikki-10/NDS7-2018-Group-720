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
		if (isset($_GET["tx"]) && $_GET["tx"] == "pending") 
		{
			$this->pending();
		}
		else
		{
			$this->make_page($_GET["tx"]);
		}
	}

	function make_page($tx_hash)
	{
		$RPC = new RPC();
		$tx_data = $RPC->get_Transaction_By_Hash($tx_hash);

		//echo "<pre>"; var_dump($tx_data); echo "</pre>";

		$tx_data = $tx_data["result"];
		
		//echo "<pre>"; var_dump($tx_data); echo "</pre>";

		$block_data = $RPC->get_Block($tx_data["blockHash"]);
		$block_data = $block_data["result"];

		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>" . $tx_data["hash"] . "</h4>";
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
			<tr><td>value</td><td><?php echo rtrim(rtrim(number_format(hexdec($tx_data["value"])/1000000000000000000, 22, ",", "."), 0), ","); ?></td></tr>
			<tr><td>nonce</td><td><?php echo hexdec($tx_data["nonce"]); ?></td></tr>
			<tr><td>gas limit</td><td><?php echo hexdec($block_data["gasLimit"]); ?></td></tr>
			<tr><td>gas</td><td><?php echo hexdec($tx_data["gas"]); ?></td></tr>
			<tr><td>gasPrice</td><td><?php echo rtrim(rtrim(number_format(hexdec($tx_data["gasPrice"])/1000000000000000000, 22, ",", "."), 0), ","); ?></td></tr>
			<tr><td>fee</td><td><?php echo rtrim(rtrim(number_format(hexdec($tx_data["gas"])*(hexdec($tx_data["gasPrice"])/1000000000000000000), 22, ",", "."), 0), ","); ?></td></tr>
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

	function pending()
	{
		$this->process_pending();
		
		$RPC = new RPC();
		$tx_data = $RPC->get_Pending_Transactions();

		if (isset($tx_data["result"]) && is_array($tx_data["result"]) && empty($tx_data["result"]) == false) 
		{
			$this->save_pending($tx_data["result"]);
		}

		$this->print_pending();

		$this->process_pending();

		//var_dump($tx_data);
	}

	function save_pending($tx_data)
	{
		foreach ($tx_data as $key => $value) 
		{
			file_put_contents("pending-tx/" . $value . ".tx", $value);
		}
	}

	function process_pending()
	{
		$files = scandir("pending-tx/");

		foreach ($files as $key => $filename) 
		{
			$filetype = explode(".", $filename);

			if ($filetype[1] == "tx") 
			{
				$tx_hash = file_get_contents("pending-tx/" . $filename);

				$RPC = new RPC();
				$tx_data = $RPC->get_Transaction_By_Hash($tx_hash);

				$tx_data = $tx_data["result"];

				if (isset($tx_data["blockHash"]) && empty($tx_data["blockHash"]) == false) 
				{
					unlink("pending-tx/" . $filename);
				}
			}
		}
	}

	function print_pending()
	{
		$files = scandir("pending-tx/");	


		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>Pending transactions</h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>TX</th>
		    <th>From</th>
		    <th>To</th>
		    <th>Amount</th>
		    <th>Fee</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			
			$RPC = new RPC();

			foreach ($files as $key => $filename) 
			{
				$filetype = explode(".", $filename);

				if ($filetype[1] == "tx") 
				{
					$tx_hash = file_get_contents("pending-tx/" . $filename);

					$tx_data = $RPC->get_Transaction_By_Hash($tx_hash);

					//echo "<pre>"; var_dump($tx_data); echo "</pre>";

					$tx_data = $tx_data["result"];
					
					echo '<tr>';
					echo '<td><a href="?tx=' . $tx_hash . '>' . $tx_hash . '</a></td>';
					echo '<td>from</td><td>' . $tx_data["from"] . '</td>';
					echo '<td>from</td><td>' . $tx_data["to"] . '</td>';
					echo '<td>from</td><td>' . rtrim(rtrim(number_format(hexdec($tx_data["value"])/1000000000000000000, 22, ",", "."), 0), ",") . '</td>';
					echo '<td>from</td><td>' . rtrim(rtrim(number_format(hexdec($tx_data["gas"])*(hexdec($tx_data["gasPrice"])/1000000000000000000), 22, ",", "."), 0), ",") . '</td>';
					echo '</tr>';

				}
			}
			?>
		</tbody>
		</table>
		</div>
		</div>
		<?php
	}
}

?>