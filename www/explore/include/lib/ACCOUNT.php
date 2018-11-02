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

		foreach ($account_data as $key => $value) 
		{
			$account_info[$key] = rtrim(rtrim(number_format(hexdec($value["result"])/1000000000000000000, 18, ",", "."), 0), ",");
		}
		
		echo "<pre>"; var_dump($account_info); echo "</pre>";


		

	}
}

?>