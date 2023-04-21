<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//Sanitize GET data
$id=intval($_GET['id']);

if(!empty($_GET['page']) && empty($_GET['page']) >= 0)
	$page=intval($_GET['page']);
else
	$page=0;

//Declare some variables to check if a user submitted an empty field
$whoopsuser="";
$whoopstext="";

//print our header
printheader();

//Let's set the user's lastthreadview if they're logged in, and also show the "new posts" link
$newpost="";
if(!empty($user))
{
	$date=time();
	mysqli_query($sql,"INSERT INTO `lastthreadread` (`userid`, `threadid`, `date`) VALUES ('$user[id]', $id, $date) ON DUPLICATE KEY UPDATE `date` = $date");
	$newpost="<span class=\"smalltext\" style=\"float: right\"><a href=newpost.php?id=$id>new post</a></span>";

}






//If the GET value refers to an invalid topic, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `threads` WHERE `id` = $id")) == 0)
	die(printmessage("no data"));



//Lets grab our topic and forum data
$thread=mysqli_fetch_array(mysqli_query($sql, "SELECT `threads`.`name`, `threads`.`forumid`, `forums`.`name` AS `forumname` FROM `threads` INNER JOIN `forums` ON `forums`.`id` = `threads`.`forumid` WHERE `threads`.`id` = $id "));


//Calculate range of data to grab
$low = 20 * $page;

//count number of posts
$numposts=mysqli_fetch_array(mysqli_query($sql, "SELECT COUNT(*) FROM `posts` WHERE `threadid` = $id"));

$pagelist="<div class=\"smalltext\">Pages: ";
$numpages=(ceil($numposts[0]/20) - 1);
for($i = 0; $i <= $numpages; $i++)
{
	$p = $i + 1;
	if($i == $page)
		$pagelist.="$p ";
	else
		$pagelist.="<a href=topic.php?id=$id&page=$i>$p</a> ";

}
$pagelist.="</div>";

//print the topic name and a link back to the topic list
print "<a href=index.php>minibord</a> - <a href=forum.php?id=$thread[forumid]>$thread[forumname]</a> - $thread[name]$newpost<br>$pagelist";


//Now lets grab our posts and order them by the date they were submitted
$posts=mysqli_query($sql, "SELECT `posts`.* FROM `posts` WHERE `threadid` = $id ORDER BY `date` LIMIT $low,20");
//$posts=mysqli_query($sql, "SELECT `posts`.* FROM `posts` WHERE `threadid` = $id ORDER BY `date`");

//Lets start our table
print "<table border=1 width=800 $themesettings[tableAttributes] class=\"table\">";

//go through the posts data
while($post=mysqli_fetch_array($posts))
{
	$userdata=mysqli_fetch_assoc(mysqli_query($sql,"SELECT `avatarurl`, `lastview`, `title`, `postheader`, `postfooter` FROM `users` WHERE `id` = $post[userid]"));
	$avatar="";
	if(!empty($userdata['avatarurl']))
	{
		$avatar="<img src=$userdata[avatarurl] style=\"max-width: 150px, max-height: 150px\"><br><br>";
	}

	$title="<br>";
	if(!empty($userdata['title']))
	{
		$title="<br><div class=\"smalltext\">$userdata[title]</div>";
	}
	

	//Clean up pre-stored HTML if HTML is disabled and sanitizeExistingIfHTMLDisabled is true
	if($options['enableHTML'] == false && $options['sanitizeExistingIfHTMLDisabled'] == true)
	{
		//The extra options prevent existing HTML entities from being re-escaped
		$post['text']=htmlspecialchars($post['text'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
		$userdata['postheader']=htmlspecialchars($userdata['postheader'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
		$userdata['postfooter']=htmlspecialchars($userdata['postfooter'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
	}




	
	

	if($post['disablesmilies'] == false)
	{
		$post['text']=showsmilies($post['text']);
	}
	
	



	//Print each post; use nl2br() to process line breaks so they look right
	print  "<tr><td rowspan=2 valign=top nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>".getusername($post['userid'])."$title<br>$avatar</td>
		<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" valign=top>$userdata[postheader]".nl2br($post['text'],false)."<br><br>$userdata[postfooter]</td></tr>
		<tr><td nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" height=10>Date: ".date("Y-m-d H:i:s", $post['date'])."</td></tr>";
}

//End the post table
print "</table>";
//print the topic name and a link back to the topic list

print "$pagelist<a href=index.php>minibord</a> - <a href=forum.php?id=$thread[forumid]>$thread[forumname]</a> - $thread[name]$newpost<br><br>";

if(!empty($user))
{
//Heres our input form so users can add more posts
print  "<table border=1 width=345 $themesettings[tableAttributes] class=\"table\">
	<form method=\"post\" action=\"newpost.php?id=$id\">
	<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">quick reply</th></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=100>text:</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp<textarea cols=21 name=\"text\">$whoopstext</textarea></td></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;
		<input type=submit name=\"action\" value=Submit>
		<input type=submit name=\"action\" value=Preview>
		<input type=\"checkbox\" name=\"disablesmilies\" value=\"1\">Disable Smilies
	</td></td></tr></form></table><br>";

}

//print our footer and call it a day
printfooter();

?>

