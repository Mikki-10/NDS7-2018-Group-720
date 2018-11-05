<?php

/**
 * 
 */
class ACCOUNT
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["account"]);
	}

	function make_page($account)
	{
		$RPC = new RPC();
		$account_data = $RPC->get_Account_Balance($account);

		//echo "<pre>"; var_dump($account_data); echo "</pre>";

		foreach ($account_data as $key => $value) 
		{
			$account_info[$key] = rtrim(rtrim(number_format(hexdec($value["result"])/1000000000000000000, 22, ",", "."), 0), ",");
		}
		
		//echo "<pre>"; var_dump($account_info); echo "</pre>";


		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>Account: $account </h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<tr><td>account</td><td><?php echo $account; ?></td></tr>
			<tr><td>earliest</td><td><?php echo $account_info["earliest"]; ?></td></tr>
			<tr><td>latest</td><td><?php echo $account_info["latest"]; ?></td></tr>
			<tr><td>pending</td><td><?php echo $account_info["pending"]; ?></td></tr>
		</tbody>
		</table>
		</div>
		<br></br>
		<h4>Recent Activity</h4>
		<?php
		
		$account_tx = $this->get_recent_trancations_for_account($account);

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<?php
				echo "<tr>";
				echo '<td><a href="?tx=' . $account_tx["hash"] . '">' . substr($account_tx["hash"], 0, 7) . "..." . substr($account_tx["hash"], -7) . '</a></td>';
				echo '<td><a href="?account=' . $account_tx["from"] . '">' . substr($account_tx["from"], 0, 7) . "..." . substr($account_tx["from"], -7) . '</a></td>';
				echo '<td><a href="?account=' . $account_tx["to"] . '">' . substr($account_tx["to"], 0, 7) . "..." . substr($account_tx["to"], -7) . '</a></td>';
				echo '<td>' . rtrim(rtrim(number_format(hexdec($account_tx["value"])/1000000000000000000, 22, ",", "."), 0), ",") . '</td>';
				echo "</tr>";
			?>
		</tbody>
		</table>
		</div></div>
		<?php
	}

	function get_recent_trancations_for_account($account)
	{
		$RPC = new RPC();
		$block = $RPC->get_block_hight();
		for ($i=$block-100; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block($i);
		}

		krsort($block_data);


		$counter = 0;
		foreach ($block_data as $key => $block) 
		{
			foreach ($block["result"]["transactions"] as $key2 => $value) 
			{
				if ($value["from"] == $account || $value["to"] == $account) 
				{
					$account_tx[$counter] = $value;
					$counter++;
				}
			}
		}
		return $account_tx;
	}
}

?>