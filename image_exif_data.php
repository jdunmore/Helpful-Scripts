<?php

$sourceDir = '/home/james/Desktop/20120405_Munich/IMG_*';
//$baseDate  = mktime(14, 0, 0, 8, 13, 2009);

foreach(glob($sourceDir) as $file){
	$exif = exif_read_data($file, 0, true);
	$baseDate = strtotime($exif['IFD0']['DateTime']) + 3600;
	$cmd = "jhead -ts" . date("Y:m:d-H:i:s", $baseDate) . " " . str_replace(" ", "\ ",$file);
	print $cmd . "\n";
	$output = array();
	exec($cmd, $output);
	print_r($output);
	print "\n";
}

/*
echo "test1.jpg:<br />\n";
$exif = exif_read_data('tests/test1.jpg', 'IFD0');
echo $exif===false ? "No header data found.<br />\n" : "Image contains headers<br />\n";

$exif = exif_read_data('tests/test2.jpg', 0, true);
echo "test2.jpg:<br />\n";
foreach ($exif as $key => $section) {
    foreach ($section as $name => $val) {
        echo "$key.$name: $val<br />\n";
    }
}


foreach(glob($sourceDir) as $file){
	$output = array();
	$cmd = "jhead -ts" . date("Y:m:d-H:i:s", $baseDate) . " " . str_replace(" ", "\ ",$file);
	print $cmd . "\n";
	exec($cmd, $output);
	print_r($output);
	print "\n";
	$baseDate++;
}*/


