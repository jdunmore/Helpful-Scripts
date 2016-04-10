<?php

	$i = 1;
	if ( $handle = opendir('/home/james/Desktop/photoframe') )
	{
   		while (false !== ($file = readdir($handle)))
   		{
       	 	if ($file != "." && $file != "..")
       	 	{
            	rename( $file, "{$i}.jpg");
            	$i++;
	        }
	    }
    }
    closedir($handle);
	

