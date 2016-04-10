<?php
exit();
$sourceDir = '/data/Images/photos/';
$years = range(1998, 2012);

$prevDirs = array();
//$baseDate  = mktime(14, 0, 0, 8, 13, 2009);

$it = new FilesystemIterator($sourceDir);
foreach ($it as $fileinfo) {

    $filename = $fileinfo->getFilename();
    if ($fileinfo->isDir() && !in_array($filename, $years)) {

        $exifDate = "";
        $noExifDate = TRUE;
        $searchDir = $baseSearch = $fileinfo->getPathname();

        do {
            try {
                $exifDate = getExifDateInDir($searchDir);
                $y = date("Y", $exifDate);
                $m = date("m", $exifDate);
                $d = date("d", $exifDate);

                if (checkdate($m, $d, $y)) {
                    $noExifDate = FALSE;
                }
            } catch (Exception $e) {
                $prevDirs[] = $searchDir = getSubDir($searchDir, $prevDirs);
                if (!$searchDir)
                    $noExifDate = FALSE;
            }
        }while ($noExifDate);

        if(!is_dir($sourceDir."{$y}/{$m}")){
            mkdir($sourceDir."{$y}/{$m}", 0777, TRUE);
        }
        
        //echo "{$baseSearch}: {$y} {$m} {$d}\n";
        //echo "will rename {$baseSearch} to ".$sourceDir."{$y}/{$m}/" .$fileinfo->getBasename() . "\n"; 
        rename($baseSearch, $sourceDir."{$y}/{$m}/" .$fileinfo->getBasename());
        
    }
}

function getSubDir($dir, $prevDirs) {


    $it = new FilesystemIterator($dir);
    foreach ($it as $fileinfo) {
        if ($fileinfo->isDir() and !in_array($fileinfo->getPathname(), $prevDirs))
            return $fileinfo->getPathname();
    }

    RETURN FALSE;
}

function getExifDateInDir($dir) {
    $it = new FilesystemIterator($dir);
    foreach ($it as $fileinfo) {
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

    throw new Exception("no dates found in dir");
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


