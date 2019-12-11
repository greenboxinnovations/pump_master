<?php


// $d = system("/usr/bin/ffmpeg -version 2>&1");
$d = system("id -gn 2>&1");
print_r($d);
?>