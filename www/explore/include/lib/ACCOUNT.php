<?php

/**
 * 
 */
class ACCOUNT
{
	
	function __construct()
	{
		$this->start();
	}

	function start()
	{
		$this->make_page($_GET["account"]);
	}

	function make_page($account)
	{
		$RPC = new RPC();
		$account_data = $RPC->get_Account_Balance($account);

		//echo "<pre>"; var_dump($account_data); echo "</pre>";

		foreach ($account_data as $key => $value) 
		{
			$account_info[$key] = rtrim(rtrim(number_format(hexdec($value["result"])/1000000000000000000, 18, ",", "."), 0), ",");
		}
		
		//echo "<pre>"; var_dump($account_info); echo "</pre>";


		echo '<div class="container"><br>';

		echo '<div class="row"><div class="col-md-1">';
		echo '</div><div class="col-md-10 text-center">';
		echo "<h4>Account: $account </h4>";
		echo '</div><div class="col-md-1">';
		echo "</div></div>";

		?>
		<div class="table-responsive">
		<table class="table table-hover">
		<tbody>
			<tr><td>account</td><td><?php echo $account; ?></td></tr>
			<tr><td>earliest</td><td><?php echo $account_info["earliest"]; ?></td></tr>
			<tr><td>latest</td><td><?php echo $account_info["latest"]; ?></td></tr>
			<tr><td>pending</td><td><?php echo $account_info["pending"]; ?></td></tr>
		</tbody>
		</table>
		</div>
		<br></br>
		<h4>Recent Activity</h4>
		<?php

	}
}

?>