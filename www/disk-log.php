<?php

$shell_output = shell_exec("iostat sda -tdx 1 2");

echo "<pre>"; var_dump($shell_output); echo "</pre>";

preg_match_all('/(.*)  (.*)/', $shell_output, $output_array);

echo "<pre>"; var_dump($output_array); echo "</pre>";

$output_to_log = time() . ";" . $output_array[2][3] . "\n";

file_put_contents("/home/nds/NDS7-2018-Group-720/www/logs/disk-IO".date('Y-m-d').".txt", $output_to_log, FILE_APPEND);

?>