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

			$this->average_block_time();

			$time_end = microtime(true);
			$time = $time_end - $time_start;

			echo "Calculating average block time: $time seconds\n<br>";
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

			for ($i=$max; $i < $block_hight-35000; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}
		else
		{
			for ($i=0; $i < $block_hight-35000; $i++) 
			{ 
				$block_data[$i] = $RPC->get_Block($i);
			}
		}

		$counter = 0;
		foreach ($block_data as $key => $block) 
		{
			if (array_key_exists($key+1, $block_data)) 
			{
				if ($time_filter[$key] >= 1000) 
				{
					// Must be a pause in the hole blockchain (1000 sec)
					$counter++;
				}
				else
				{
					$time_filter[$key] = hexdec($block_data[$key+1]["result"]["timestamp"])-hexdec($block["result"]["timestamp"]);
				}
			}
		}

		if(count($time_filter)) 
		{
		    $time_filter = array_filter($time_filter);
		    $average = array_sum($time_filter)/count($time_filter);
		}

		file_put_contents("blocktime-filter.json", json_encode($time_filter));

		/*
		$data = array(
					'' => , 
					);

		make_a_chart($data);
		*/

		echo "<br>Number: " . $block_hight;
		echo "<br>Number (counted): " . count($time_filter);
		echo "<br>Counted over 1000 sec" . $counter;
		echo "<br>Average: " . $average;
	}

	function make_a_chart($data)
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
		    'https://cdn.rawgit.com/highcharts/highcharts/057b672172ccc6c08fe7dbb27fc17ebca3f5b770/samples/data/usdeur.json',
		    function (data) {

		        Highcharts.chart('container<?php echo $a_random_int;?>', {
		            chart: {
		                zoomType: 'x'
		            },
		            title: {
		                text: 'USD to EUR exchange rate over time'
		            },
		            subtitle: {
		                text: document.ontouchstart === undefined ?
		                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
		            },
		            xAxis: {
		                type: 'datetime'
		            },
		            yAxis: {
		                title: {
		                    text: 'Exchange rate'
		                }
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
		                name: 'USD to EUR',
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