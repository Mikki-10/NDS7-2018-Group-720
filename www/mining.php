<?php

error_reporting(E_ALL);
ini_set("display_errors", true);


$output_array = get_data_from_csv();

var_dump($output_array);

show_data($output_array);


function get_data_from_csv()
{
	$files = scandir(dirname(__FILE__));

	//echo dirname(__FILE__);

	//var_dump($files);

	foreach ($files as $key => $filename) 
	{
		$filetype = explode(".", $filename);

		//var_dump($filetype);

		if ($filetype[1] == "csv") 
		{
			$input = file_get_contents($filename);
			$input_array[$key] = explode(PHP_EOL, $input);

			//var_dump($input_array);

			/*
			foreach ($input_array as $key2 => $line) 
			{
				//$input_array[$key] = str_getcsv($input, $delimiter = ";", '"', "\\");
				$input_array2[$key] = explode(";", $line);
			}
			var_dump($input_array2);
			*/
		}
	}

	//var_dump($input_array);

	$output_array = NULL;

	foreach ($input_array as $key => $miner_array) 
	{
		//var_dump($miner_array);
		foreach ($miner_array as $key2 => $time_event) 
		{
			$time_event = explode(";", $time_event);

			//var_dump($time_event);

			$output_key = new_key($time_event[0], $output_array);
			//echo "Output key for array $output_key\n";

			$output_array[$output_key] = array($time_event[1], $time_event[2], $time_event[3], $time_event[4]);


			/*
			if (array_key_exists($time_event[0] . "-2", $output_array)) 
			{
				$output_array[$time_event[0] . "-3"] = array($time_event[1], $time_event[2], $time_event[3]);
			}
			elseif (array_key_exists($time_event[0], $output_array)) 
			{
				$output_array[$time_event[0] . "-2"] = array($time_event[1], $time_event[2], $time_event[3]);
			}
			else
			{
				$output_array[$time_event[0]] = array($time_event[1], $time_event[2], $time_event[3]);
			}
			*/
		}
	}

	//var_dump($output_array);

	ksort($output_array);


	//var_dump($output_array);

	return $output_array;
}


function new_key($key, $array, $counter = 0)
{
	
	$check = $key . "-" . $counter;

	if (array_key_exists($check, $array)) 
	{
		//echo "key $key-$counter found\n";
		$counter++;
		return new_key($key, $array, $counter);
	}
	else
	{
		//echo "key $key-$counter do not exsist\n";
		return $key . "-" . $counter;
	}
}


function show_data($input)
{
	?>
	<table>
	  <tr>
	    <th>Time</th>
	    <th>Miner1</th>
	    <th>Miner2</th>
	  </tr>
	  <tr>
	  	<td>
	  		
	  	</td>
	    <td>
	    	<table>
			  <tr>
			    <th>Noget 1</th>
				<th>Noget 2</th>
				<th>Noget 3</th>
			  </tr>
			</table>
		</td>
	    <td>
	    	<table>
			  <tr>
			    <th>Noget 1</th>
				<th>Noget 2</th>
				<th>Noget 3</th>
			  </tr>
			</table>
		</td>
	  </tr>

	  <?php
	  foreach ($input as $key => $value) 
	  {
	  	echo "<tr>";
	  	echo "<td>$key</td>";
	  	if ($value[0] == "miner1") 
	  	{
	  		?>
	  			<td>
	  			<table>
				  <tr>
				    <td><?php echo $value[1] ?></td>
				    <td><?php echo $value[2] ?></td>
				    <td><?php echo $value[3] ?></td>
				  </tr>
				</table>
				</td>
				<td></td>
	  		<?php
	  	}
	  	elseif ($value[0] == "miner2") 
	  	{
	  		?>
				<td></td>
				<td>
	  			<table>
				  <tr>
				    <td><?php echo $value[1] ?></td>
				    <td><?php echo $value[2] ?></td>
				    <td><?php echo $value[3] ?></td>
				  </tr>
				</table>
				</td>
	  		<?php
	  	}
	  }
}

?>