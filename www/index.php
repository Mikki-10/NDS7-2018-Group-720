<?php

error_reporting(E_ALL);
ini_set("display_errors", true);

?>

<style>
table, td, th {    
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 5px;
}
</style>

Documentation https://github.com/ethereum/wiki/wiki/JSON-RPC<br>
172.18.0.3:8545<br>
{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}

<br>

<form action="/" method="post">
  IP: <input type="text" name="ip" value="<?php if (isset($_POST["ip"])){ echo $_POST["ip"]; } ?>"><br>
  Request: <input type="text" name="request" style="width: 400px;" value="<?php if (isset($_POST["request"])){ echo htmlspecialchars($_POST["request"]); } ?>"><br>
  <input type="submit" value="Submit">
</form>



<br><br>

<?php


if (isset($_POST["ip"]) && isset($_POST["request"])) 
{
	$ip = $_POST["ip"];
	$request = $_POST["request"];

	make_rpc_request($ip, $request);
}
elseif (isset($_POST["demo"])) 
{
	$ip = "172.18.0.3:8545";
	$request = '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}';

	make_rpc_request($ip, $request);
}
else
{

}

function make_rpc_request($ip, $request)
{
	try 
	{
		#curl -H "Content-Type: application/json" -X POST --data '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}' 172.18.0.3:8545

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $ip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
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

		//echo "<pre>"; var_dump($result); echo "</pre>";
	} 
	catch (Exception $e) 
	{
		echo "<pre>"; var_dump($e); echo "</pre>";
	}

	build_table($result);
	echo "<br>";
	echo "<br>";


	foreach ($result["result"] as $key => $value) 
	{
		build_table($value);
		echo "<br>";
	}
}



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

echo "</body>";


?>