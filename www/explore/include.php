<?php

// ------------------------------------------------------------ //
// Hvad vil vi gerne "include" af php sider
// ------------------------------------------------------------ //
$includes = array(
					"config",
					"pre_functions",
					"start_gui",
					"functions",
					"api",
					"end_gui"
				);


// ------------------------------------------------------------ //
// Automatiks inkludering af sider
// ------------------------------------------------------------ //
foreach ($includes as $key => $value) 
{
	require_once __DIR__ . "/include/" . $value . ".php";
}

?>