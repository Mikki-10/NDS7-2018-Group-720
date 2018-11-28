<?php

if (isset($_GET["block"])) 
{
	new BLOCK();
}
elseif (isset($_GET["account"])) 
{
	new ACCOUNT();
}
elseif (isset($_GET["tx"])) 
{
	new TX();
}
elseif (isset($_GET["uncle"])) 
{
	new UNCLE();
}
elseif (isset($_GET["tools"])) 
{
	new TOOLS();
}
elseif (isset($_GET["clean"])) 
{
	new CLEAN();
	header('Location: /explore');
}
else
{
	new FRONTPAGE();
}
?>