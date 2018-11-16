<?php

/**
 * 
 */
class TOOLS
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		if ($_GET["tools"] == "average-block-time") 
		{
			$time_start = microtime(true);

			echo '<div class="container"><br>';
			
			$this->average_block_time();

			$time_end = microtime(true);
			$time = $time_end - $time_start;

			echo "<br>PHP time to calculate average block time: $time seconds\n<br></div>";
		}
	}

	function average_block_time()
	{
		$RPC = new RPC();
		$block_hight = $RPC->get_block_hight();

		if (file_exists("blocktime-filter.json")) 
		{
			$time_filter = json_decode(file_get_contents("blocktime-filter.json"), true);
			$max = max(array_keys($time_filter));

			for ($i=$max; $i < $block_hight+1; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}
		else
		{
			for ($i=0; $i < $block_hight+1; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}

		foreach ($block_data as $key => $block) 
		{
			if (array_key_exists($key+1, $block_data)) 
			{
				$time_filter[$key+1] = array(
											hexdec($block_data[$key+1]["result"]["timestamp"])*1000, 
											hexdec($block_data[$key+1]["result"]["timestamp"])-hexdec($block["result"]["timestamp"])
										);
			}
		}

		foreach ($time_filter as $key => $value) 
		{
			if ($value[1] >= 1000 || $value[1] <= -1000) 
			{
				// Must be a pause in the hole blockchain (1000 sec)
				unset($time_filter[$key]);
			}

		}

		foreach ($time_filter as $key => $value) 
		{
			$time_filter_for_avg[$key] = $value[1];
		}


		if(count($time_filter_for_avg)) 
		{
		    $time_filter_for_avg = array_filter($time_filter_for_avg);
		    $average = array_sum($time_filter_for_avg)/count($time_filter_for_avg);
		}

		file_put_contents("blocktime-filter.json", json_encode($time_filter));

		$i = 0;
		foreach ($time_filter as $key => $value) 
		{
			if ($value[0] == "" || $value[0] == 0 || $value[1] == "" || $value[1] == 0) 
			{
				# code...
			}
			else
			{
				$json_chart[$i] = $value;
				$i++;
			}
		}

		file_put_contents("chart.json", json_encode($json_chart));

		$this->make_a_chart();

		echo "<br>Number: " . $block_hight;
		echo "<br>Number (counted): " . count($time_filter_for_avg);
		$dif = $block_hight - count($time_filter_for_avg);
		echo "<br>Blocks over or under 1000 sec: " . $dif;
		echo "<br>Average: " . $average;
	}

	function make_a_chart()
	{
		?>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>

		<?php
		$a_random_int = random_int(1000, 10000);
		?>
		<div id="container<?php echo $a_random_int;?>" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

		<script type="text/javascript">
		$.getJSON(
		    'http://192.168.20.3/explore/chart.json',
		    function (data) {

		        Highcharts.chart('container<?php echo $a_random_int;?>', {
		            chart: {
		                zoomType: 'x'
		            },
		            title: {
		                text: 'Time between blocks'
		            },
		            subtitle: {
		                text: document.ontouchstart === undefined ?
		                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
		            },
		            xAxis: {
		                type: 'datetime',
		                max: 1000
		            },
		            yAxis: {
		                title: {
		                    text: 'Sec'
		                },
		                min: 0 // this sets minimum values of y to 0
		            },
		            legend: {
		                enabled: false
		            },
		            plotOptions: {
		                area: {
		                    fillColor: {
		                        linearGradient: {
		                            x1: 0,
		                            y1: 0,
		                            x2: 0,
		                            y2: 1
		                        },
		                        stops: [
		                            [0, Highcharts.getOptions().colors[0]],
		                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
		                        ]
		                    },
		                    marker: {
		                        radius: 2
		                    },
		                    lineWidth: 1,
		                    states: {
		                        hover: {
		                            lineWidth: 1
		                        }
		                    },
		                    threshold: null
		                }
		            },

		            series: [{
		                type: 'area',
		                name: 'Time between blocks',
		                data: data
		            }]
		        });
		    }
		);
		</script>

		<?php

	}

}

?>