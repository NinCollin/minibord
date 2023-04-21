<?php


if(empty($run))
	die('This file may not be run directly');


//This file stores (almost) all the custom functions for the bord


//print site footer
function printfooter()
{
	global $starttime;
	global $themesettings;
	global $sql;

	$endtime = hrtime(true);
	$rendertime = ($endtime - $starttime) / 1e+9;


//$exec_time_result=mysqli_query($sql,"SELECT query_id, SUM(duration) FROM information_schema.profiling GROUP BY query_id ORDER BY query_id DESC LIMIT 1;");
//$exec_time_row = mysqli_fetch_array($exec_time_result);

//echo "<p>Query executed in ".$exec_time_row[1].' seconds';
	
	print "</div><div class=\"footer\">minibord v1.03 - lots 'o junkz plus optional html after a decent hiatus edition <a href=https://github.com/NinCollin/minibord>(github)</a><br>PHP execution time: $rendertime</div>";
}


//Get username
function getusername($userid)
{
	global $sql;
	
	$userdata=mysqli_fetch_array(mysqli_query($sql, "SELECT `name`, `id`, `minipicurl`, `namecolor` FROM `users` WHERE `id` = $userid"));
	
	if($userdata['namecolor'] == 0)
		$namecolor = "#9da4f5";
		
	elseif($userdata['namecolor'] == 1)
		$namecolor = "#f59de5";
	
	elseif($userdata['namecolor'] == 2)
		$namecolor = "#c69df5";


	return "<b><a href=profile.php?id=$userdata[id]><font color=$namecolor>$userdata[name]</font></a></b>";
	
}

//convert text to smilies
function showsmilies($text)
{
	global $sql;

	$smiliequery = mysqli_query($sql, "SELECT * FROM `smilies`");
	while($smilie=mysqli_fetch_array($smiliequery))
	{
		$text=str_replace($smilie['code'], "<img src=\"$smilie[image]\" alt=\"$smilie[code]\">", $text);

	}

	return $text;
}

//print a message
function printmessage($string)
{	
	global $themesettings;

	print "<table width=345 $themesettings[tableAttributes] class=\"table\"><tr><td align=center $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$string</td></tr></table><br>
	";
}

//print a message and die
function printdiemessage($string)
{	
	global $themesettings;

	printmessage($string);
	
	printfooter();

	die();
}


?>
