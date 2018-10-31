<?php

/**
 * 
 */
class FRONTPAGE
{
	
	function __construct()
	{
		# code...
	}

	function start()
	{
		$RPC = new RPC();
		$block = $RPC->get_block_hight();
		$this->make_page($block);
	}

	function make_page($block)
	{
		
		for ($i=$block-10; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block_By_Number($i);
		}

		echo "Recent blocks";
		foreach ($block_data as $key => $block) 
		{
			echo $block["result"]["number"] . " - ";
			echo $block["result"]["miner"] . " - ";
			echo $block["result"]["timestamp"] . " - ";
			echo count($block["result"]["transactions"]) . " - ";
			echo count($block["result"]["uncles"]);
			echo "<br>";
		}

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