<?php



?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>NDS Block explore</title>
  </head>
  <body>


 <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
 <div class="container">
  <a class="navbar-brand" href="/explore/">NDS Block explore</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="/explore/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?account=all">Accounts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?tx=pending">Pending Tx</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?uncle=all">Uncels</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Tools
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="?tools=average-block-time">Average block time</a>
        </div>
      </li>
      <li class="nav-item">
      <?php
      
      if (isset($_GET["auto"])) 
      {
      	$html_get = "";
      	$counter = 0;
      	foreach ($_GET as $key => $value) 
		{
			if ($key == "auto") 
			{
				# code...
			}
			else
			{
				if ($counter == 0) 
				{
					$html_get = "?" . $key ."=" . $value;
				}
				else
				{
					$html_get = $html_get . "&" . $key ."=" . $value;
				}
				$counter++;
			}
		}
		if ($html_get == "") 
		{
			$html_get = "/explore";
		}
      	echo '<a class="nav-link" href="' . $html_get . '">Turn auto refesh off</a>';
      }
      else
      {
      	$counter = 0;
      	if (empty($_GET)) 
		{
			$html_get = "?auto=1";
		}
		else
		{
			foreach ($_GET as $key => $value) 
			{
				if ($counter == 0) 
				{
					$html_get = "?" . $key ."=" . $value;
				}
				else
				{
					$html_get = $html_get . "&" . $key ."=" . $value;
				}
				$counter++;
			}
			$html_get = $html_get . "&auto=1";
		}
      	echo '<a class="nav-link" href="' . $html_get . '">Turn auto refesh on</a>';
      }
      ?>
      </li>
      <li class="nav-item">
      	<a class="nav-link" href="?clean=1">Clean cache</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<?php

//var_dump($_GET);

?>