<?php
/**
 * Shared stuff
 */

if(!function_exists('readline')) {
	function readline($prompt="") {
	   print $prompt;
	   $out = "";
	   $key = "";
	   $key = fgetc(STDIN);        //read from standard input (keyboard)
	   while ($key!="\n")        //if the newline character has not yet arrived read another
	   {
	       $out.= $key;
	       $key = fread(STDIN, 1);
	   }
	   return trim($out);
	}
}


?>