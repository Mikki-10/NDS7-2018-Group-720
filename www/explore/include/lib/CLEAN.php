<?php

/**
 * 
 */
class CLEAN
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$files = scandir(dirname(__FILE__) . "/../../");

		//echo "<pre>"; var_dump(dirname(__FILE__) . "/../../"); echo "</pre>";
		//echo "<pre>"; var_dump($files); echo "</pre>";

		foreach ($files as $key => $filename) 
		{
			$filetype = explode(".", $filename);

			if (isset($filetype[1]) && $filetype[1] == "json") 
			{
				unlink($filename);
			}
		}
	}
}

?>