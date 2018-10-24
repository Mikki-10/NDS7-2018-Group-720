<?php

$filename = 'download-' . date("d-m-Y_(G_i_s)") . ".tar.gz";
$mime = "application/x-tgz";


header("Content-Type: " . $mime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
$cmd = "tar -cz " . "../priveth/logs";

passthru($cmd);
exit(0);

?>