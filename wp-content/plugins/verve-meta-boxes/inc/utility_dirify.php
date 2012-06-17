<?php
# PHP Dirifyplus 1.5
#
# A PHP port of Dirifyplus 1.5 by Crys Clouse
# http://mt-stuff.fanworks.net/plugin/dirifyplus.phtml
# for dynamic publishing wiht MovableType 3.1 and Smarty
#
# Last Modified 24 May 2005


/*
code is a 1 to 3 letter indication of the dirify style.



The first character defines how slashes are treated.
p - plain: Eliminate all slashes.
s - slashes: Allow slashes. (for use with Unix file systems)
b - backslashes: Allow backslashes. (for use with Windows file systems)
c - convert: Convert backslashes to slashes. (to switch from Windows to Unix file systems)
r - reverse: Convert slashes to backslashes. (to switch from Unix to Windows file systems)

The second character defines the capitalization. (defaults to s)
l - lower: Convert all characters to lower case.
s - same: Maintain original case.
i - initial: Capitalize first letter of each word, and convert the rest to lowercase.
c - capital: Capitalize first letter of each word, and maintain the original case for the rest.

The third character defines how spaces are converted. (defaults to u)
u - underscore: Convert spaces to underscores.
n - nothing: Eliminate all whitespace. (best with i or c as the second character)
d - dash: Spaces covert to dashes.

In addition to the above conversions dirifyplus converts high ascii characters to their equivalents, and strips html & html entities.
Examples:

$string = "<b>Foo</b>/bar Bar";
$code = "plu";
dirify_plus_for_php($string,$code);

returns "foobar_bar"



*/

function dirify_plus_for_php($s,$t) {

	$a = substr($t, 0, 1);
	$b = substr($t, 1, 1);
	$c = substr($t, 2, 1);
	convert_high_ascii($s);
	$s = strip_tags($s);						## remove HTML tags.
	$s = preg_replace('!&[^;\s]+;!', '', $s); 	## remove HTML entities.
	$s = preg_replace('![^\w\s\\\/]!', '', $s);	## remove non-word/space chars.
	$s = str_replace('  ', ' ', $s);			## remove 2 spaces in a row
	if ($b == "l")
	{
		$s =	strtolower($s);					## lower-case.
	}
	elseif ($b == "i")	{
		$s =	strtolower($s);					## lower-case.
		$s = ucwords($s);						## captialize words
	}
	elseif ($b == "c")
	{
		$s = ucwords($s);						## captialize words
	}
	if ($a == "p")
	{
		$s = preg_replace('![^\w\s]!', '', $s);			## remove non-word/space chars.
	}
	elseif ($a == "s")
	{
		$s = preg_replace('![^\w\s\/]!', '', $s);		## remove non-word/space'/' chars.
	}
	elseif ($a == "b")
	{
		$s = preg_replace('![^\w\s\\\]!', '', $s);		## remove non-word/space'\' chars.
	}
	elseif ($a == "c")
	{
		$s = preg_replace('![^\w\s\\\]!', '', $s);		## remove non-word/space'\' chars.
		$s = str_replace('\\','/',$s);					## reverse backslashes
	}
	elseif ($a == "r")
	{
		$s = preg_replace('![^\w\s\/]!', '', $s);		## remove non-word/space'/' chars.
		$s = str_replace('/','\\',$s);					## reverse slashes
	}
	if (($c == "u") || (!$c))
	{
		$s = str_replace(' ','_',$s);					## change space chars to underscores.
	}
	elseif ($c == "n")
	{
		$s = str_replace(' ','',$s);					## delete space
	}
	elseif ($c == "d")
	{
		$s = str_replace(' ','-',$s);					## change space chars to dashes.
	}
	return($s);
}
?>
