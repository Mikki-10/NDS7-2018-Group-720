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
		$RPC = new RPC();
		$block = $RPC->get_block_hight();
		$this->make_page($block);
	}

	function make_page($block)
	{
		
		$RPC = new RPC();
		for ($i=$block-10; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block_By_Number($i);
		}

		echo "<pre>"; var_dump($block_data); echo "</pre>";

		echo "Recent blocks";
		?>
		<table>
		  <tr>
		    <th>Number</th>
		    <th>Miner</th>
		    <th>Timestamp</th>
		    <th>Tx</th>
		    <th>Uncles</th>
		  </tr>
		<?php
		foreach ($block_data as $key => $block) 
		{
			echo "<tr>";
			echo "<td>$block["result"]["number"]</td>";
			echo "<td>$block["result"]["miner"]</td>";
			echo "<td>$block["result"]["timestamp"]</td>";
			echo "<td>" . count($block["result"]["transactions"]) . "</td>";
			echo "<td>" . count($block["result"]["uncles"]) . "</td>";
			echo "</tr>";
		}
		
		echo "</table>";

		echo "<br>";
		echo "Recent Transactions";
		foreach ($block_data as $key => $block) 
		{
			foreach ($block["result"]["transactions"] as $key => $value) 
			{
				echo $value;
				echo "<br>";
			}
		}

	}

}

?>