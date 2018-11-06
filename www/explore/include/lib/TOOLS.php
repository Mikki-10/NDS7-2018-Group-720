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
		$block_hight = $RPC->get_block_hight();

		if (file_exists("blocktime-filter.json")) 
		{
			$time_filter = json_decode(file_get_contents("blocktime-filter.json"), true);
			$max = max(array_keys($time_filter));

			for ($i=$max; $i < $block_hight; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}
		else
		{
			for ($i=0; $i < $block_hight; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}

		foreach ($block_data as $key => $block) 
		{
			if (array_key_exists($key+1, $block_data)) 
			{
				if ($time_filter[$key] >= 1000) 
				{
					// Must be a pause in the hole blockchain (1000 sec)
				}
				else
				{
					$time_filter[$key] = hexdec($block_data[$key+1]["result"]["timestamp"])-hexdec($block["result"]["timestamp"]);
				}
			}
		}

		if(count($time_filter)) 
		{
		    $time_filter = array_filter($time_filter);
		    $average = array_sum($time_filter)/count($time_filter);
		}

		file_put_contents("blocktime-filter.json", json_encode($time_filter))

		echo "<br>Number: " . $block_hight;
		echo "<br>Number (counted): " . count($time_filter);
		echo "<br>Average: " . $average;
	}

}

?>