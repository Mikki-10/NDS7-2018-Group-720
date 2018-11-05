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
		if ($_GET["account"] == "all") 
		{
			$this->find_accounts();
		}
		else
		{
			$this->make_page($_GET["account"]);
		}
	}

	function find_accounts()
	{
		$RPC = new RPC();
		$block = $RPC->get_block_hight();
		for ($i=$block-1000; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block($i);
		}

		krsort($block_data);

		foreach ($block_data as $key => $block) 
		{
			$accounts[$block["result"]["miner"]] = $block["result"]["miner"];
			foreach ($block["result"]["transactions"] as $key2 => $value) 
			{
				$accounts[$value["from"]] = $value["from"];
				$accounts[$value["to"]] = $value["to"];
			}
		}

		//echo "<pre>"; var_dump($accounts); echo "</pre>";

		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>Accounts found in the latest 1000 blocks</h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Account</th>
		    <th>Latest</th>
		    <th>Earliest</th>
		    <th>Pending</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			foreach ($accounts as $key => $value) 
			{
				$account_data = $RPC->get_Account_Balance($value);

				foreach ($account_data as $key => $value) 
				{
					$account_info[$key] = rtrim(rtrim(number_format(hexdec($value["result"])/1000000000000000000, 22, ",", "."), 0), ",");
				}
				
				//echo "<pre>"; var_dump($account_info); echo "</pre>";

				echo '<tr>';
					echo '<td><a href="?account=' . $value . '">' . $value . '</a></td>';
					echo '<td>' . $account_info["latest"] . '</td>';
					echo '<td>' . $account_info["earliest"] . '</td>';
					echo '<td>' . $account_info["pending"] . '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
		</table>
		</div></div>
		<?php
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

		if ($account_tx == NULL) 
		{
			# code...
		}
		else
		{
			?>
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
				foreach ($account_tx as $key => $value) 
				{
					echo "<tr>";
					echo '<td><a href="?tx=' . $value["hash"] . '">' . substr($value["hash"], 0, 7) . "..." . substr($value["hash"], -7) . '</a></td>';
					echo '<td><a href="?account=' . $value["from"] . '">' . substr($value["from"], 0, 7) . "..." . substr($value["from"], -7) . '</a></td>';
					echo '<td><a href="?account=' . $value["to"] . '">' . substr($value["to"], 0, 7) . "..." . substr($value["to"], -7) . '</a></td>';
					echo '<td>' . rtrim(rtrim(number_format(hexdec($value["value"])/1000000000000000000, 22, ",", "."), 0), ",") . '</td>';
					echo "</tr>";
				}
				?>
			</tbody>
			</table>
			</div></div>
			<?php
		}
	}

	function get_recent_trancations_for_account($account)
	{
		$RPC = new RPC();
		$block = $RPC->get_block_hight();
		for ($i=$block-1000; $i < $block; $i++) 
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
		if (isset($account_tx)) 
		{
			return $account_tx;
		}
		else
		{
			return NULL;
		}
	}
}

?>