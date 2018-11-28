<?php

// ------------------------------------------------------------ //
// Hvad vil vi gerne "include" af php sider
// ------------------------------------------------------------ //
$includes = array(
					"RPC",
					"FRONTPAGE",
					"BLOCK",
					"ACCOUNT",
					"TX",
					"UNCLE",
					"TOOLS",
					"CLEAN"
				);


// ------------------------------------------------------------ //
// Automatiks inkludering af sider
// ------------------------------------------------------------ //
foreach ($includes as $key => $value) 
{
	require_once __DIR__ . "/lib/" . $value . ".php";
}


// Other functions down here


function build_table($array)
{
    //echo "<pre>"; var_dump($array); echo "</pre>";

    echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
    echo "<tr>";
    foreach ($array as $key => $value) 
    {
    	if (is_array($key)) 
    	{
    		# code...
    	}
    	else
    	{
    		echo "<th>" . $key . "</th>";
    	}
    }
    echo "</tr>";
    echo "<tr>";
    foreach ($array as $key => $value) 
    {
    	if (is_array($value)) 
    	{
    		echo "<td>";
    		build_table($value); 
    		echo "</td>";
    	}
    	elseif (is_bool($value)) 
    	{
    		if ($value === true) 
    		{
    			echo "<td> true </td>";
    		}
    		elseif ($value === false) 
    		{
    			echo "<td> false </td>";
    		}
    		else
    		{
    			echo "<td>";
    			echo var_dump($value);
    			echo "</td>";
    		}
    	}
    	elseif (strpos($value, '0x') === 0) 
    	{
   			// It starts with '0x'
    		echo "<td>";
    		echo $value;
    		echo "<br>";
    		echo hexdec($value);
    		echo "<br>";
    		echo number_format(hexdec($value));
    		echo "</td>";
    	}
    	else
    	{
    		echo "<td>" . $value . "</td>";
    	}
    }
    echo "</tr>";
    echo "</table>";
}


function nice_number($n, $type) 
{
    // first strip any formatting;
    $n = (0+str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n)) return false;

    // now filter it;
    if ($n > 1000000000000) return round(($n/1000000000000), 2).' T' . $type;
    elseif ($n > 1000000000) return round(($n/1000000000), 2).' G' . $type;
    elseif ($n > 1000000) return round(($n/1000000), 2).' M' . $type;
    elseif ($n > 1000) return round(($n/1000), 2).' K' . $type;

    return number_format($n);
}

?>