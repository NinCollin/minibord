<?php

//Connect to mysql server
$sql=mysqli_connect( 'servername', 'sqluser', 'sqlpassword', 'database') or die('Couldn\'t connect to database!');

//used for rss lol
$siteroot="http://board.rainynight.city/";

//print site header
function printheader()
{
	print "<tt><h1>minibord</h1><a href=rss.php>rss</a><br><br>
	";
}

//print site footer
function printfooter()
{
	print "<br>minibord v0.05 with cleaner code at 12 am edition</tt>";
}

//print a message
function printmessage($string)
{
	print "<table border=1 width=345><tr><td align=center>$string</td></tr></table><br><br>
	";
}
?>
