<?php

/**
 * 
 */
class UNCLE
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["uncle"], $_GET["id"]);
	}

	function make_page($hash, $index)
	{
		$RPC = new RPC();
		$uncle_data = $RPC->get_Uncle_By_Block_Hash_And_Index($hash, $index);

		echo "<pre>"; var_dump($uncle_data); echo "</pre>";

		$uncle_data = $uncle_data["result"];
		
		echo "<pre>"; var_dump($uncle_data); echo "</pre>";
	}

}

?>