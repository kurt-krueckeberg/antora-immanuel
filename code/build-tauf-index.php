<?php
declare(strict_types=1);

if ($argc != 2) {
   die("2nd argument must be file name.\n");
}

function goto_regex(\SplFileObject $file, string $regex) : void
{
  foreach($file as $line) {

      if (preg_match( "@|$@", $line) !== false)   
         break;
  }
}

$fname = $argv[1];

$file = new \SplFileObject($fname, "r");

$found = false;

$child_given = '';

goto_regex($file, "@\|$@");

foreach($file as $line) {

  if (preg_match('@|$@', $line) === 1) break;
 
  if ($found)  {

     if (substr($line, 0, 6) == 'Eltern') {

         $line = $file->fgets();

         $father_name_pattern = '/(?<!\S)((?:[A-Z][a-zäöüß]+ ){1,4})([A-Z][a-zäöüß]+)(?!\S)/u';

         if (preg_match($father_name_pattern, $line, $matches)) {

             $givenNames = trim($matches[1]); // The space after the last given name is captured, so trim it.
             $surname = $matches[2];
             
             echo "Given names: $givenNames\n";
             echo "Surname: $surname\n";

         } else {
             echo "No full name found.\n";
         }

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
      $given_name_pattern = '/(?<!\S)([A-Z][a-zäöü]+(?: [A-Z][a-zäöü]+)*)/u';  // 'u' for Unicode support

      preg_match($given_name_pattern, substr($line, 2), $m);  

      $child_given = trim($m[0]);
  } 
}

