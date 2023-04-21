<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();


//Sanitize our data
$id=intval($_GET['id']);

//If the GET value refers to an invalid user, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `users` WHERE `id` = $id")) == 0)
	printdiemessage("no data");



$userdata=mysqli_fetch_array(mysqli_query($sql, "SELECT `avatarurl`, `regdate`, `birthday`, `homepageurl`, `homepagename`, `email`, `bio`, `postheader`, `postfooter`, `lastview`, `name`,
				(SELECT COUNT(*) FROM `posts` WHERE `userid` = $id) AS `numposts`, 
				(SELECT COUNT(*) FROM `threads` WHERE `userid` = $id) AS `numthreads`
				FROM `users` 
				WHERE `id` = $id LIMIT 1"));

//Clean up pre-stored HTML if HTML is disabled and sanitizeExistingIfHTMLDisabled is true
if($options['enableHTML'] == false && $options['sanitizeExistingIfHTMLDisabled'] == true)
{
	//The extra options prevent existing HTML entities from being re-escaped
	$userdata['bio']=htmlspecialchars($userdata['bio'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
	$userdata['postheader']=htmlspecialchars($userdata['postheader'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
	$userdata['postfooter']=htmlspecialchars($userdata['postfooter'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
}

$lastpost=mysqli_fetch_array(mysqli_query($sql, "SELECT `posts`.`date`, `threads`.`name`, `posts`.`threadid` FROM `posts` INNER JOIN `threads` ON `posts`.`threadid` = `threads`.`id` WHERE `posts`.`userid` = $id ORDER BY `posts`.`date` DESC LIMIT 1"));

	$avatar="";
	if(!empty($userdata['avatarurl']))
	{
		$avatar="<img src=$userdata[avatarurl] style=\"max-width: 150px, max-height: 150px\"><br><br>";
	}	


	if(!empty($userdata['homepagename']) && !empty($userdata['homepageurl']))
	{
		$homepage="<a href=\"$userdata[homepageurl]\">$userdata[homepagename]</a> - $userdata[homepageurl]";
	}
	elseif(!empty($userdata['homepageurl']))
	{
		$homepage="<a href=\"$userdata[homepageurl]\">$userdata[homepageurl]</a>";
	}
	else
	{
		$homepage="";
	}


	$email="";
	if(!empty($userdata['email']))
	{
		$email=str_replace("@", " guess what ", $userdata['email']);
	}

	$birthday="";
	if(!empty($userdata['birthday']))
	{
		$birthday=date("Y-m-d", $userdata['birthday']);
	}

	$lastview="<i>Never Active</i>";
	if(!empty($userdata['lastview']))
	{
		$lastview=date("Y-m-d H:i:s", $userdata['lastview']);
	}

	$lastpostdate="<i>No Posts</i>";
	if(!empty($lastpost['date']))
	{
		$lastpostdate=date("Y-m-d H:i:s", $lastpost['date'])." in <a href=topic.php?id=$lastpost[threadid]>$lastpost[name]</a>";
	}

	$title="<br>";
	if(!empty($userdata['title']))
	{
		$title="<br><div class=\"smalltext\">$userdata[title]</div>";
	}



print "<table $themesettings[tableAttributes] class=\"table\" >
	<tr><th colspan=2 $themesettings[thRegularAttributes]  class=\"thRegular\" >Profile for $userdata[name]</th></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\" width=200># posts</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$userdata[numposts]</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\"># threads</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$userdata[numthreads]</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">registered on</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">".date("Y-m-d H:i:s", $userdata['regdate'])."</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">last post</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$lastpostdate</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">last page view</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$lastview</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">Birthday</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$birthday</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">Homepage</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$homepage</td></tr>
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\">Email</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$email</td></tr>

	</table>


	<br><br>	

<table $themesettings[tableAttributes] class=\"table\" >
	<tr><th $themesettings[thRegularAttributes]  class=\"thRegular\" >Bio</th></tr>

	<tr><td $themesettings[tdStyle1Attributes]  class=\"tdStyle1\">".nl2br($userdata['bio'])."</td></tr>
	</table>


	<br><br>

	<table $themesettings[tableAttributes] class=\"table\" >
	<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\" >Sample post</th></tr>
		
	<tr><td rowspan=2 valign=top nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>".getusername($id)."$title<br>$avatar</td>
	<td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" class=\"thRegular\" valign=top>$userdata[postheader]
This is a test post.
<br>Blah.
<br>Blah..
<br>Blah...
<br><br>$userdata[postfooter]
	</td></tr>
	<tr><td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle1\"  height=10>Date: ".date("Y-m-d H:i:s", 1530964800)."</td></tr>

	</table>
<br>

";




printfooter();
?>