<?php

/**
 * 
 */
class TX
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["tx"]);
	}

	function make_page($tx_hash)
	{
		$RPC = new RPC();
		$tx_data = $RPC->get_Transaction_By_Hash($tx_hash);

		echo "<pre>"; var_dump($tx_data); echo "</pre>";

		$tx_data = $tx_data["result"];
		
		echo "<pre>"; var_dump($tx_data); echo "</pre>";
	}
}

?>