<?php
declare(strict_types=1);

if ($argc != 2) {
   die("2nd argument must be file name.\n");
}

$fname = $argv[1];

$file = new \SplFileObject($fname, "r");

$found = false;

$child_given = '';

foreach($file as $line) {
  
  if ($found)  {

     if (substr($line, 0, 6) == "Eltern") {

         $line = $file->fgets();

         $rc = preg_match_all('/([A-Z][a-zöäü]+)/', $line, $matches);
                  
         $surname = trim($matches[1][count($matches[1]) - 1]);
         
         echo "Child name: $child_given $surname\n";
         
         $found = false;

     }

  } else if ($line[0] == "." && $line[1] == " ") {

       $found  = true; 

       preg_match('/^[^.+\[]+/', substr($line, 2), $m);  

       $child_given = trim($m[0]);
  } 
}

