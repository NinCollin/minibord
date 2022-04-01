<?php

//This file stores all the custom functions for the bord


//print site header
function printheader()
{
	global $boardname;
	global $themesettings;

	//If our theme wants to use an image banner, and the banner url isn't empty, show image banner
	if($themesettings['useImageBanner'] && !empty($themesettings['banner']))
	{
		print "<body $themesettings[bodyAttributes]><tt><img src=$themesettings[banner]><br><br>";
	}
	//Otherwise, just print the board name
	else
	{
		print "<body $themesettings[bodyAttributes]><tt><font size=7>$boardname</font><br>";
	}
	
	//Print the headlinks
	print "<a href=index.php>main</a> <a href=settings.php>settings</a> <a href=rss.php>rss</a><br><br>";

}

//print site footer
function printfooter()
{
	global $themesettings;
	
	print "<br><font size=3>minibord v0.07 with sorted posts at 2 am edition <a href=https://github.com/NinCollin/minibord>(github)</a></font></tt>";
}

//print a message
function printmessage($string)
{	
	global $themesettings;

	print "<table border=1 width=345 $themesettings[tableAttributes]><tr><td align=center>$string</td></tr></table><br>
	";
}

?>
