<?php

$sourceDir = '/home/james/tmp/diskstation/Natalie Mobile/'; 

$it = new FilesystemIterator($sourceDir);
foreach ($it as $fileinfo) {

    $filename = $fileinfo->getFilename();


    if($fileinfo->isDir() ) {
    	continue;
    }

    $ext = pathinfo($fileinfo->getPathname(), PATHINFO_EXTENSION);

    echo "{$filename}\n";

    if($ext == 'jpg'){
    	$exifDate =  getExifDate($fileinfo);
        $y = date("Y", $exifDate);
        $m = date("m", $exifDate);
        $d = date("d", $exifDate);

        if (!checkdate($m, $d, $y) || $y == 1970) {
            $dates = dateFromMtime($fileinfo->getPathname()); 
            $y = $dates['Y'];
            $m = $dates['m'];
            $d = $dates['d'];
        }
    } elseif($ext == 'mp4'){
        $dates = dateFromMtime($fileinfo->getPathname()); 
        $y = $dates['Y'];
        $m = $dates['m'];
        $d = $dates['d'];
    } 
    

    if(!is_dir($sourceDir."{$y}/{$m}")){
        mkdir($sourceDir."{$y}/{$m}", 0777, TRUE);
    }

    $newFileName = $sourceDir."{$y}/{$m}/" .$fileinfo->getBasename();
    if(!is_file($newFileName)){
        echo "will rename {$fileinfo->getPathname()} to ".$newFileName . "\n";   

        rename($fileinfo->getPathname(), $newFileName);    
    } else {
        echo "{$newFileName} already exists\n";    
    }

    

}

function dateFromMtime($filePath){
    $stat = stat($filePath);  

    $date = $stat[9];

    $y = date("Y", $date);
    $m = date("m", $date);
    $d = date("d", $date);

    return array('Y' => $y, 'm' => $m, 'd' => $d); 
}


function getExifDate($fileinfo) {


        if ($fileinfo->isFile()) {

            if (exif_imagetype($fileinfo->getPathname()) !== FALSE) {
                //echo $fileinfo->getPathname() . "\n";
                $exif = exif_read_data($fileinfo->getPathname(), 0, true);
                
                
                if (!$exif) {
                    //echo $fileinfo->getPathname();
                    exit;
                }

                if (array_key_exists('IFD0', $exif) && array_key_exists('DateTime', $exif['IFD0'])) {
                    $baseDate = strtotime($exif['IFD0']['DateTime']);
                    //echo $fileinfo->getPathname();
                    return $baseDate;
                }

                if (array_key_exists('EXIF', $exif) && array_key_exists('DateTimeOriginal', $exif['EXIF'])) {
                    $baseDate = strtotime($exif['EXIF']['DateTimeOriginal']);
                    //echo $fileinfo->getPathname();
                    return $baseDate;
                }

                
                

                /* echo $fileinfo->getPathname()."\n";
                  print_r($exif);
                  echo "\n";
                  continue; */
            }
        }


}

exit("\n\n--finished--\n");

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


