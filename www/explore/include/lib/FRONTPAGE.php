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
		for ($i=$block-10; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block($i);
		}

		krsort($block_data);

		//echo "<pre>"; var_dump($block_data); echo "</pre>";
		echo '<div class="container"><br>';
		echo "<h1>Recent blocks</h1>";
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
		  </tr>
		</thead>
		<tbody>
		<?php
		foreach ($block_data as $key => $block) 
		{
			echo "<tr>";
			echo '<td><a href="?block='. hexdec($block["result"]["number"]) .'">' . hexdec($block["result"]["number"]) . '</a></td>';
			echo '<td><a href="?account='. $block["result"]["miner"] .'">' . $block["result"]["miner"] . '</a></td>';
			echo "<td>" . date("d-m-Y H:i:s", hexdec($block["result"]["timestamp"])) . "</td>";
			echo "<td>" . count($block["result"]["transactions"]) . "</td>";
			echo "<td>" . count($block["result"]["uncles"]) . "</td>";
			echo "</tr>";
		}

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
			foreach ($block["result"]["uncles"] as $key => $value) 
			{
				echo "<tr>";
				echo '<td><a href="?block=' . $value. '">' . $value . '</a></td>';
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