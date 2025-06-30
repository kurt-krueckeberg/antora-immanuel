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

      $found = true;

/*
 Regular expression to match a sequence of given names
Explanation:
* preg_match(...) finds the first match only.

* The (?<!\S) ensures that the match isn't preceded by a non-space character.

* [A-Z][a-zäöü]+ matches a single name.

* (?: [A-Z][a-zäöü]+)* allows additional names, each preceded by a space.

* The /u modifier enables proper handling of umlaut characters like ä, ö, and ü.

*/ 
      $pattern = '/(?<!\S)([A-Z][a-zäöü]+(?: [A-Z][a-zäöü]+)*)/u';  // 'u' for Unicode support

      //--preg_match('/^[^.+\[]+/', substr($line, 2), $m);  
      preg_match($pattern, substr($line, 2), $m);  

      $child_given = trim($m[0]);
  } 
}

