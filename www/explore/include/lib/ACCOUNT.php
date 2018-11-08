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
		$block_hight = $RPC->get_block_hight();

		if (file_exists("accounts.json")) 
		{
			$file_accounts = json_decode(file_get_contents("accounts.json"), true);

			if ($file_accounts["block_hight"] != $block_hight) 
			{
				for ($i=$file_accounts["block_hight"]; $i < $block_hight; $i++) 
				{ 
					$block_data[$i] = $RPC->get_Block($i);
				}
			}
		}
		else
		{
			for ($i=0; $i < $block_hight; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}

		if (isset($block_data) && ($block_data != NULL || $block_data != "")) 
		{
			krsort($block_data);

			foreach ($block_data as $key => $block) 
			{
				$accounts[$block["result"]["miner"]] = $block["result"]["miner"];

				if (array_key_exists("transactions", $block["result"])) 
				{
					foreach ($block["result"]["transactions"] as $key2 => $value) 
					{
						if ($value["from"] != "" || $value["to"] != "") 
						{
							$accounts[$value["from"]] = $value["from"];
							$accounts[$value["to"]] = $value["to"];
						}
					}
				}
			}

			foreach ($accounts as $key => $value) 
			{
				$file_accounts["accounts"][$key] = $value;
			}
		}

		$accounts = $file_accounts["accounts"];

		ksort($accounts);

		$file_accounts["block_hight"] = $block_hight;

		file_put_contents("accounts.json", json_encode($file_accounts));

		//echo "<pre>"; var_dump($accounts); echo "</pre>";

		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>" . count($accounts) . " accounts found in the blockchain</h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Account</th>
		    <th>Earliest</th>
		    <th>Latest</th>
		    <th>Pending</th>
		    <th>Differences</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			foreach ($accounts as $key => $account) 
			{
				if ($account != "" || $account != " " || $account != NULL || is_array($account) == false) 
				{
					$account_data = $RPC->get_Account_Balance($account);

					foreach ($account_data as $key2 => $account_value) 
					{
						if (
							array_key_exists("result", $account_value) && 
							($account_value != NULL || $account_value != "") && 
							($account_value["result"] != "" || $account_value["result"] != NULL || is_array($account_value["result"]) == false)) 
						{
							$account_info[$key2] = rtrim(rtrim(number_format(hexdec($account_value["result"])/1000000000000000000, 22, ",", "."), 0), ",");
						}
					}

					$account_info["differences"] = hexdec($account_data["latest"]["result"]) - hexdec($account_data["pending"]["result"]);
					
					//echo "<pre>"; var_dump($account_info); echo "</pre>";

					echo '<tr>';
						echo '<td><a href="?account=' . $account . '">' . $account . '</a></td>';
						echo '<td>' . $account_info["earliest"] . '</td>';
						echo '<td>' . $account_info["latest"] . '</td>';
						echo '<td>' . $account_info["pending"] . '</td>';
						echo '<td>' . $account_info["differences"] . '</td>';
					echo '</tr>';
				}
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