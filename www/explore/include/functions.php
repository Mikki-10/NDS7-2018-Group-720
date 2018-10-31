<?php

// ------------------------------------------------------------ //
// Hvad vil vi gerne "include" af php sider
// ------------------------------------------------------------ //
$includes = array(
					"RPC",
					"FRONTPAGE",
					"BLOCK",
					"ACCOUNT",
					"TX"
				);


// ------------------------------------------------------------ //
// Automatiks inkludering af sider
// ------------------------------------------------------------ //
foreach ($includes as $key => $value) 
{
	require_once __DIR__ . "/lib/" . $value . ".php";
}


// Other functions down here

?>