<?php
    for($i = 1; $i < 17; $i+=2){
        $from = $i;
        $to = $i+1;
        $cmd = "pdftk in.pdf cat {$from}-{$to} output out{$from}-{$to}.pdf";
        echo $cmd.PHP_EOL;
        `{$cmd}`;
    }
