<?php

/**
 * 
 */
class BLOCK
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["block"]);
	}

	function make_page($block)
	{
		$RPC = new RPC();
		$block_data = $RPC->get_Block_By_Number($block);

		echo "<pre>"; var_dump($block_data); echo "</pre>";
	}
}

?>