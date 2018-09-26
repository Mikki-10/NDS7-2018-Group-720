<?php

error_reporting(E_ALL);
ini_set("display_errors", true);

echo "Hallo World<br>";


try 
{
	#curl -H "Content-Type: application/json" -X POST --data '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}' 172.18.0.3:8545

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "172.18.0.3:8545");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"jsonrpc\":\"2.0\",\"method\":\"admin_peers\",\"params\":[],\"id\":74}");
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = "Content-Type: application/json";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close ($ch);

	$result = json_decode($result, true);

	echo "<pre>"; var_dump($result); echo "</pre>";
} 
catch (Exception $e) 
{
	echo "<pre>"; var_dump($e); echo "</pre>";
}



foreach ($result["result"] as $key => $value) 
{
	echo build_table($value);
}


function build_table($array)
{
    //echo "<pre>"; var_dump($array); echo "</pre>";

    echo "<table>";
    echo "<tr>";
    foreach ($array as $key => $value) 
    {
    	if (is_array($key)) 
    	{
    		# code...
    	}
    	else
    	{
    		echo "<th>". $key ."</th>";
    	}
    }
    echo "</tr>";
    echo "<tr>";
    foreach ($array as $key => $value) 
    {
    	if (is_array($value)) 
    	{
    		build_table($value);
    	}
    	else
    	{
    		echo "<td>". $value ."</td>";
    	}
    }
    echo "</tr>";
    echo "</table>";
}


?>