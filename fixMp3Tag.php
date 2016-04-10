<?php
$sourceDir = '/data/Music/Rolling Stones/Forty Licks/Disk1';


$it = new FilesystemIterator($sourceDir);
foreach ($it as $fileinfo) {

    	$filename = $fileinfo->getFilename();
//	echo $fileinfo->getPathname()."\n";
	$fullName = $fileinfo->getPathname();
	$newName = str_replace('rolling_stones_(40_licks)_cd2_', '', $fullName);
	//echo $newName."\n";
	//rename($fullName, $newName);
	
	$track = substr($filename, 0, 2);
	$title = str_replace('-', ' ', substr($filename, 3, strpos($filename, ".")-3));
	echo $track."\t".$title."\n";
	$cmd = "id3 -a \"Rolling Stones\" -A \"Forty Licks\" -g \"Rock n Roll\" -T \"{$track}\" -t \"{$title}\" -c \"\" "  . escapeshellarg($fullName) ;
	$cmd = "id3 -t '{$title}' ". escapeshellarg($fullName) ;
	echo $cmd."\n";
	var_dump(shell_exec($cmd));
//	var_dump(shell_exec("id3 -l" .escapeshellarg($fullName)));

}

 
