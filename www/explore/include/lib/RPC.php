<?php

/**
 * 
 */
class RPC
{
	
	function __construct()
	{
		# code...
	}

	function request($request, $ip = RPC_NODE)
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
			if (curl_errno($ch) && curl_errno($ch) == 0) 
			{
			    if (curl_error($ch) != "") 
			    {
			    	die('CURL Error: ' . curl_error($ch));
			    }
			}
			curl_close ($ch);

			$result = json_decode($result, true);

			//echo "<pre>"; var_dump($result); echo "</pre>";
		} 
		catch (Exception $e) 
		{
			echo "<pre>"; var_dump($e); echo "</pre>";
		}

		return $result;
	}

	function get_block_hight()
	{
		// eth_blockNumber
		$array = $this->request('{"jsonrpc":"2.0","method":"eth_blockNumber","params":[],"id":83}');
		//$json = json_decode($json, true);
		return number_format(hexdec($array["result"]), 0, ',', '');
	}

	function get_Block($block)
	{
		if (substr($block, 0, 2) == "0x") 
		{
			return $this->get_Block_By_Hash($block);
		} 
		else 
		{
			return $this->get_Block_By_Number($block);
		}
	}

	function get_Block_By_Hash($block)
	{
		return $this->request('{"jsonrpc":"2.0","method":"eth_getBlockByHash","params":["'.$block.'", true],"id":1}');
	}

	function get_Block_By_Number($block)
	{
		// eth_getBlockByNumber
		$block = dechex($block);
		$block = "0x" . $block;
		$array = $this->request('{"jsonrpc":"2.0","method":"eth_getBlockByNumber","params":["'.$block.'", true],"id":1}');
		//$json = json_decode($json, true);
		return $array;
	}

	function get_Transaction_By_Hash($hash)
	{
		return $this->request('{"jsonrpc":"2.0","method":"eth_getTransactionByHash","params":["'.$hash.'"],"id":1}');
	}

	function get_Uncle_By_Block_Hash_And_Index($hash, $index)
	{
		$index = dechex($index);
		$index = "0x" . $index;
		return $this->request('{"jsonrpc":"2.0","method":"eth_getUncleByBlockHashAndIndex","params":["'.$hash.'", "'.$index.'"],"id":1}');

	}

	function get_Account_Balance($account)
	{
		$data["latest"] = $this->request('{"jsonrpc":"2.0","method":"eth_getBalance","params":["' . $account . '", "latest"],"id":1}');
		$data["earliest"] = $this->request('{"jsonrpc":"2.0","method":"eth_getBalance","params":["' . $account . '", "earliest"],"id":1}');
		$data["pending"] = $this->request('{"jsonrpc":"2.0","method":"eth_getBalance","params":["' . $account . '", "pending"],"id":1}');

		return $data;
	}

	function get_Pending_Transactions()
	{
		if (file_exists("filter.json")) 
		{
			//var_dump(filectime("filter.json"));
			//var_dump(time()-36000);
			/*
			if (filectime("filter.json") <= time()-36000) 
			{
				$id = $this->request('{"jsonrpc":"2.0","method":"eth_newPendingTransactionFilter","params":[],"id":73}');
				file_put_contents("filter.json", json_encode($id));
				echo "Update file";
				var_dump($id);
				var_dump(hexdec($id["result"]));
				$id = $id["result"];
				return $this->request('{"jsonrpc":"2.0","method":"eth_getFilterChanges","params":["' . $id . '"],"id":73}');
			}
			else
			{
				*/
				$json = json_decode(file_get_contents("filter.json"), true);
				//echo "Reuse file";
				//var_dump($json);
				//var_dump(hexdec($json["result"]));
				$json = $json["result"];
				$result = $this->request('{"jsonrpc":"2.0","method":"eth_getFilterChanges","params":["' . $json . '"],"id":73}');
				if (isset($result["error"]) && $result["error"]["message"] == "filter not found") 
				{
					$id = $this->request('{"jsonrpc":"2.0","method":"eth_newPendingTransactionFilter","params":[],"id":73}');
					file_put_contents("filter.json", json_encode($id));
					//echo "Update file";
					$id = $id["result"];

					//var_dump($this->request('{"jsonrpc":"2.0","method":"eth_getFilterLogs","params":["' . $id . '"],"id":74}'));

					return $this->request('{"jsonrpc":"2.0","method":"eth_getFilterChanges","params":["' . $id . '"],"id":73}');
				}
				else
				{
					//var_dump($this->request('{"jsonrpc":"2.0","method":"eth_getFilterLogs","params":["' . $json . '"],"id":74}'));
					return $result;
				}

			//}
		}
		else
		{
			$id = $this->request('{"jsonrpc":"2.0","method":"eth_newPendingTransactionFilter","params":[],"id":73}');
			file_put_contents("filter.json", json_encode($id));
			//echo "New file";
			//var_dump($id);
			//var_dump(hexdec($id["result"]));
			$id = $id["result"];
			return $this->request('{"jsonrpc":"2.0","method":"eth_getFilterChanges","params":["' . $id . '"],"id":73}');
		}
	}
}



?>