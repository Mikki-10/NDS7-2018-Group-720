<?php

error_reporting(E_ALL);
ini_set("display_errors", true);



$time_start_total = microtime(true);


$hash_color_array = array('hash' => "color");

?>

<head>
<style>

#full {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    text-align: center;
}

#blocks {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#blocks td, #blocks th {
    border: 1px solid #ddd;
    padding: 4px;
}

#blocks tr:nth-child(even){background-color: #f2f2f2;}

#blocks tr:hover {background-color: #ddd;}

#blocks th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    background-color: #4CAF50;
    color: white;
}
</style>
</head>


<?php

$time_start = microtime(true);

$output_array = get_data_from_csv();

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "get_data_from_csv: $time seconds\n<br>";



$time_start = microtime(true);

show_data($output_array);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "show_data: $time seconds\n<br>";


give_interval($output_array);




function get_data_from_csv()
{
	$files = scandir(dirname(__FILE__));

	foreach ($files as $key => $filename) 
	{
		$filetype = explode(".", $filename);

		if ($filetype[1] == "csv") 
		{
			$input = file_get_contents($filename);
			$input_array[$key] = explode(PHP_EOL, $input);
		}
	}

	$output_array = array();

	foreach ($input_array as $key => $miner_array) 
	{
		foreach ($miner_array as $key2 => $time_event) 
		{
			$time_event = explode(";", $time_event);

			if ($time_event[0] == "" || $time_event[0] == NULL) 
			{
				# code...
			}
			else
			{
				$output_key = new_key($time_event[0], $output_array);

				$output_array[$output_key] = array($time_event[1], $time_event[2], $time_event[3], $time_event[4]);
			}
		}
	}

	ksort($output_array);

	return $output_array;
}


function new_key($key, $array, $counter = 0)
{
	
	$check = $key . "-" . $counter;

	if (array_key_exists($check, $array)) 
	{
		$counter++;
		return new_key($key, $array, $counter);
	}
	else
	{
		return $key . "-" . $counter;
	}
}


function show_data($input)
{
	?>
	<table id="blocks">
	  <tr>
	    <th style="min-width:155px; width:155px;  max-width:155px;">Time</th>
	<?php

	foreach ($input as $key => $value) 
	{
  		$miners[$value[0]] = 1;
  	}

  	ksort($miners);


  	$miners_count = count($miners);
  	$miner_th_width = 100 / $miners_count;

  	$id = 1;
	foreach ($miners as $key => $value) 
  	{
		//$id = scrape_from($key, "miner");

		echo '<th style="width:'.$miner_th_width.'%">Miner'.$id.'</th>';
		$id++;
	}

	?>
	  </tr>
	  <tr>
	  	<td>
	  		
	  	</td>
	  	<?php
	  	foreach ($miners as $key => $value) 
	  	{
	  		echo '<td>
				    	<table id="full">
						  <tr>
						    <td style="width:100%" bgcolor="#2992cd"><font color="#ffffff">Message</font></td>
							<td style="min-width:40px;" bgcolor="#2992cd"><font color="#ffffff">Block Hight</font></td>
							<td style="min-width:120px;" bgcolor="#2992cd"><font color="#ffffff">Block Hash</font></td>
						  </tr>
						</table>
					</td>';
	  	}
		?>
	  </tr>

		<?php
		foreach ($input as $key => $value) 
		{
			if ( (isset($_GET["from"]) && is_numeric($_GET["from"]) ) || ( isset($_GET["to"]) && is_numeric($_GET["to"]) ) ) 
			{
				if ($key <= $_GET["from"] && $key >= $_GET["to"]) 
				{
					echo "<tr>";
					echo '<td style="min-width:155px; width:155px; max-width:155px;">'.$key.'</td>';

					$id = scrape_from($value[0], "miner");
					$id = $id - 1;
					for ($i=0; $i < $id; $i++) 
					{ 
						echo "<td></td>";
					}
					?>
					<td>
						<table id="full">
					  <tr>
					    <td style="width:100%" bgcolor="<?php echo define_color($value[3]); ?>"><font color="#ffffff"><?php echo $value[1]; ?></font></td>
					    <td style="min-width:40px; width:40px; max-width:40px;" bgcolor="<?php echo define_color($value[3]); ?>"><font color="#ffffff"><?php echo $value[2] ?></font></td>
					    <td style="min-width:120px; width:120px; max-width:120px;" bgcolor="<?php echo define_color($value[3]); ?>"><font color="#ffffff"><?php echo $value[3] ?></font></td>
					  </tr>
					</table>
					</td>
					<?php

					$id2 = count($miners);
					$id2 = $id2 - 1;
					$id3 = $id2 - $id;
					for ($i=0; $i < $id3; $i++) 
					{ 
						echo "<td></td>";
					}
				}
			}
			else
			{
				if (isset($no_num) && $no_num == 1) 
				{
					echo "No numeric from, and to.";
					$no_num = 1;
				}
			}
		}
}


// Defining the basic scraping function
function scrape_from($data, $start)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    return $data;  // Returning the scraped data from the function
}


function define_color($hash)
{
	$colors = array(
					"#c51d51",
					"#c31b20",
					"#c53020",
					"#c88822",
					"#e3aa1a",
					"#f0c813",
					"#95b339",
					"#66a041",
					"#448641",
					"#31763e",
					"#256236",
					"#2992cd",
					"#0a83bf",
					"#0871ac",
					"#1b6499",
					"#264d78",
					"#274166",
					"#64488f",
					"#6e448e",
					"#7e3f91",
					"#8a3189",
					"#911a7e",
					"#9a1477",
					"#a70c74"
					);

	$colors = array('#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#9a6324', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080');

	if (array_key_exists($hash, $GLOBALS['hash_color_array'])) 
	{
		return $GLOBALS['hash_color_array'][$hash];
	}
	else
	{
		$key = count($GLOBALS['hash_color_array']) % count($colors);
		$GLOBALS['hash_color_array'][$hash] = $colors[$key];
		return $colors[$key];
	}
}


function give_interval($input)
{
	//$count = count($input);
	$amount = count($input) / 100;
	$amount = ceil($amount);
	//var_dump($amount);
	for ($i=0; $i < $amount; $i++) 
	{ 
		echo '<a href="?from=' . (100*$i) . '&to=100">' . 1+$i . '</a>  ';
	}
}

$time_end_total = microtime(true);
$time_total = $time_end_total - $time_start_total;

echo "Total: $time_total seconds\n<br>";


$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

echo "Request time: $time seconds\n<br>";

?>
