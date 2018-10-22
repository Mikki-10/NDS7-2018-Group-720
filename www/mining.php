<?php

error_reporting(E_ALL);
ini_set("display_errors", true);


?>

<head>
<style>
#blocks {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#blocks td, #blocks th {
    border: 1px solid #ddd;
    padding: 8px;
}

#blocks tr:nth-child(even){background-color: #f2f2f2;}

#blocks tr:hover {background-color: #ddd;}

#blocks th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
</style>
</head>


<?php


$output_array = get_data_from_csv();

//var_dump($output_array);

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

			if ($time_event[0] == "" || $time_event[0] == NULL) 
			{
				# code...
			}
			else
			{
				$output_key = new_key($time_event[0], $output_array);
				//echo "Output key for array $output_key\n";

				$output_array[$output_key] = array($time_event[1], $time_event[2], $time_event[3], $time_event[4]);
			}


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
	<table id="blocks">
	  <tr>
	    <th>Time</th>
	<?php

	foreach ($input as $key => $value) 
	{
  		$miners[$value[0]] = 1;
  	}

  	var_dump($miners);

  	krsort($miners);

	foreach ($miners as $key => $value) 
  	{
		$id = scrape_from($key, "miner");
		echo "<th>Miner$id</th>";
	}

	?>
	  </tr>
	  <tr>
	  	<td>
	  		
	  	</td>
	  	<?php
	  	foreach ($miners as $key => $value) 
	  	{
	  		echo "<td>
				    	<table>
						  <tr>
						    <th>Noget 1</th>
							<th>Noget 2</th>
							<th>Noget 3</th>
						  </tr>
						</table>
					</td>";
	  	}
		?>
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
				<td></td>
				<?php
			}
			elseif ($value[0] == "miner3") 
			{
				?>
				<td></td>
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


// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_between($data, $start, $end)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}

// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_to($data, $end)
{
    //$data = stristr($data, $start); // Stripping all data from before $start
    //$data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}

// --------------------------------------------------------- //
// Funktion til at gemme kun den data man har behov for
// --------------------------------------------------------- //
// Defining the basic scraping function
function scrape_from($data, $start)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    //$stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    //$data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;  // Returning the scraped data from the function
}


?>