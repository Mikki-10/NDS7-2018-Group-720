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
		$this->make_page($_GET["account"]);
	}

	function make_page($account)
	{
		$RPC = new RPC();
		$account_data = $RPC->get_Account_Balance($account);

		echo "<pre>"; var_dump($account_data); echo "</pre>";

		//$tx_data = $tx_data["result"];
		
		//echo "<pre>"; var_dump($tx_data); echo "</pre>";

	}
}

?>