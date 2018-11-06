<?php

/**
 * 
 */
class TOOLS
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		if ($_GET["tools"] = "average-block-time") 
		{
			$time_start = microtime(true);

			$this->average_block_time();

			$time_end = microtime(true);
			$time = $time_end - $time_start;

			echo "Calculating average block time: $time seconds\n<br>";
		}
	}

	function average_block_time()
	{
		$RPC = new RPC();
		$block = $RPC->get_block_hight();

		for ($i=0; $i < $block; $i++) 
		{ 
			$block_data[$i] = $RPC->get_Block($i);
		}

		foreach ($block_data as $key => $block) 
		{
			if (array_key_exists($key+1, $block_data)) 
			{
				$holder[$key] = hexdec($block_data[$key+1]["result"]["timestamp"])-hexdec($block["result"]["timestamp"]);
			}
		}

		if(count($holder)) 
		{
		    $holder = array_filter($holder);
		    $average = array_sum($holder)/count($holder);
		}

		echo "<br>Number: " . hexdec($block["result"]["number"]));
		echo "<br>Number (counted): " count($holder);
		echo "<br>Average: " . $average;
	}

}

?>