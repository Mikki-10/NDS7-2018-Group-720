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
		$files = scandir(dirname(__FILE__));

		foreach ($files as $key => $filename) 
		{
			$filetype = explode(".", $filename);

			if ($filetype[1] == "json") 
			{
				unlink($filename);
			}
		}
	}
}

?>