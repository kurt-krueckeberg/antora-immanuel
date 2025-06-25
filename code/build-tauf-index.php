<?php
declare(strict_types=1);

$file = new \SplFileObject($fname, "r");

$found = false;

foreach($file as $line) {

  
  if ($found)  {

     if (substr($line, 0, 6) == "Eltern") {

         $father_line = $file->fgets();

         $rc = preg_match('/([A-Z][a-zöäü]+) (?:([A-Z][a-zöäü]+) ){1,4}/', $father_line, $matches);
     }

  } else if ($line[0] == "." && $line[1] == " ") {

       $found  = true; 

  } 
}

