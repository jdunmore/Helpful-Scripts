<?php

$sourceDir = '/something/'; 

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
            list($y, $m, $d) = dateFromMtime($fileinfo->getPathname()); 
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

    echo "will rename {$fileinfo->getPathname()} to ".$sourceDir."{$y}/{$m}/" .$fileinfo->getBasename() . "\n";   

    rename($fileinfo->getPathname(), $sourceDir."{$y}/{$m}/" .$fileinfo->getBasename());

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


