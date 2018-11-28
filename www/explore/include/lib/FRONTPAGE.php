<?php

/**
 * 
 */
class FRONTPAGE
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page();
	}

	function make_page()
	{
		
		//echo "<pre>"; var_dump($block); echo "</pre>";
		$RPC = new RPC();
		$block = $RPC->get_block_hight();

		for ($i=$block-20; $i < $block+1; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block($i);
		}

		krsort($block_data);

		foreach ($block_data as $key => $block) 
		{
			if (is_array($block)) 
			{
				if (array_key_exists($key-1, $block_data)) 
				{
					$var = hexdec($block["result"]["timestamp"])-hexdec($block_data[$key-1]["result"]["timestamp"]);
					$holder[$key] = hexdec($block["result"]["timestamp"])-hexdec($block_data[$key-1]["result"]["timestamp"]);
				}
				else
				{
					$var = "0";
				}

				$difficulty[$key] = hexdec($block["result"]["difficulty"]);
			}
		}

		if(count($holder)) 
		{
		    $holder = array_filter($holder);
		    $average_time = array_sum($holder)/count($holder);
		}

		if(count($difficulty)) 
		{
		    $difficulty = array_filter($difficulty);
		    $average_dif = array_sum($difficulty)/count($difficulty);
		}

		$hashrate = $average_dif/$average_time;

		$hashrate = nice_number($hashrate, "H/s");

		$average_dif = nice_number($average_dif, "H");

		//echo "<pre>"; var_dump($block_data); echo "</pre>";
		echo '<div class="container"><br>';
		echo "<h1>Recent blocks</h1>";
		echo $average_dif . " / " . $average_time . " sek = " . $hashrate;
		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<thead>
		  <tr>
		    <th>Number</th>
		    <th>Miner</th>
		    <th>Timestamp</th>
		    <th>Tx</th>
		    <th>Uncles</th>
		    <th>Size</th>
		    <th>Time to last block</th>
		  </tr>
		</thead>
		<tbody>
		<?php
		foreach ($block_data as $key => $block) 
		{
			if (array_key_exists($key-1, $block_data)) 
			{
				$var = hexdec($block["result"]["timestamp"])-hexdec($block_data[$key-1]["result"]["timestamp"]);
				$holder[$key] = hexdec($block["result"]["timestamp"])-hexdec($block_data[$key-1]["result"]["timestamp"]);

			}
			else
			{
				$var = "0";
			}
			
			echo "<tr>";
			echo '<td><a href="?block='. hexdec($block["result"]["number"]) .'">' . hexdec($block["result"]["number"]) . '</a></td>';
			echo '<td><a href="?account='. $block["result"]["miner"] .'">' . $block["result"]["miner"] . '</a></td>';
			echo "<td>" . date("d-m-Y H:i:s", hexdec($block["result"]["timestamp"])) . "</td>";
			echo "<td>" . count($block["result"]["transactions"]) . "</td>";
			echo "<td>" . count($block["result"]["uncles"]) . "</td>";
			echo '<td>' . hexdec($block["result"]["size"]) . '</td>';
			echo '<td>' . $var . '</td>';
			echo "</tr>";
		}

		if(count($holder)) 
		{
		    $holder = array_filter($holder);
		    $average = array_sum($holder)/count($holder);
		}

		echo "<tr>";
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td>' . $average . '</td>';
		echo "</tr>";
		echo "</tbody>";
		echo "</table>";
		echo "</div>";

		echo "<br>";
		echo "<h4>Recent Transactions</h4>";
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
		foreach ($block_data as $key => $block) 
		{
			foreach ($block["result"]["transactions"] as $key => $value) 
			{
				echo "<tr>";
				echo '<td><a href="?tx=' . $value["hash"] . '">' . substr($value["hash"], 0, 7) . "..." . substr($value["hash"], -7) . '</a></td>';
				echo '<td><a href="?account=' . $value["from"] . '">' . substr($value["from"], 0, 7) . "..." . substr($value["from"], -7) . '</a></td>';
				echo '<td><a href="?account=' . $value["to"] . '">' . substr($value["to"], 0, 7) . "..." . substr($value["to"], -7) . '</a></td>';
				echo '<td>' . rtrim(rtrim(number_format(hexdec($value["value"])/1000000000000000000, 22, ",", "."), 0), ",") . '</td>';
				echo "</tr>";
			}
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
		foreach ($block_data as $key => $block) 
		{
			foreach ($block["result"]["uncles"] as $key2 => $value) 
			{
				echo "<tr>";
				echo '<td><a href="?uncle=' . $block["result"]["hash"] . '&id=' . $key2 . '">' . $value . '</a></td>';
				echo "</tr>";
			}
		}
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		echo "</div>";

	}

}

?>