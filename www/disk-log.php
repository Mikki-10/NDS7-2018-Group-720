<?php

$shell_output = shell_exec("sudo iostat sda -tdx 1 2");

echo "<pre>"; var_dump($shell_output); echo "</pre>";

preg_match_all('/(.*)  (.*)/', $shell_output, $output_array);

echo "<pre>"; var_dump($output_array); echo "</pre>";

?>