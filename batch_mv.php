<?php

	$i = 1;
	$d = 20;
	$dname = 0;
	if ( $handle = opendir('/home/james/Desktop/photoframe') )
	{
   		while (false !== ($file = readdir($handle)))
   		{
   			if( $d == 20 )
   			{
	   			$dname++;
	   			mkdir("/home/james/Desktop/photoframe/{$dname}");   				
   				$d=1;
   			}
			$d++;
			
       	 	if ( ($file != ".") and  ($file != "..") and (strpos($file, '.jpg') !== false))
       	 	{
				print "is file:{$file}" . is_file($file)."\n";
				
            	rename( "/home/james/Desktop/photoframe/{$file}", "/home/james/Desktop/photoframe/{$dname}/{$file}");
            	$i++;            
	        }
	    }
    }
    closedir($handle);
	

