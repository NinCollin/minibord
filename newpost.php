<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');


//print our header
printheader();

//Sanitize GET data
$id=intval($_GET['id']);

//Declare some variables for making sure input input persists across POSTs
$whoopstext="";
$smiliechecked="";

//This value is used for checking if the post wants smilies disabled
$disablesmilies=0;
if(!empty($_POST['disablesmilies']))
{
	$disablesmilies=1;
}



//If the GET value refers to an invalid topic, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `threads` WHERE `id` = $id")) == 0)
	die(printmessage("topic not found") . printfooter());

//Lets grab our topic and forum data
$thread=mysqli_fetch_array(mysqli_query($sql, "SELECT `threads`.`name`, `threads`.`forumid`, `forums`.`name` AS `forumname` FROM `threads` INNER JOIN `forums` ON `forums`.`id` = `threads`.`forumid` WHERE `threads`.`id` = $id"));




//If the user isn't logged in, dont let them post!
if(empty($user))
{
		printmessage("you must be logged in to post");
		printfooter();
		die();
}


//If the user submitted valid text and username, lets add their post to the database and tell them this
if(!empty($_POST['text']) && !empty($_POST['action']) && $_POST['action'] == "Submit")
{
	
	//Check if html is allowed
	if($options['enableHTML'] == true)
	{
		//If so, check if we're cleaning it up
		if($options['enablehtmLawed'] == true)
		{

			require_once($options['htmLawedPath']);
			$_POST['text'] = htmLawed($_POST['text'], $options['htmLawedconfig']);
	
		}
		//If not, then just...use the unsanitzed html :(
		else
			$_POST['text']=$_POST['text'];
	}
	else
	{
		//If its not enabled, let's just strip everything out
		$_POST['text']=htmlspecialchars($_POST['text']);
	}

	//prepared query time; insert dat data!
	$stmt = mysqli_prepare($sql,"INSERT INTO `posts` ( `threadid`, `date`, `text`, `userid`, `ip`, `disablesmilies`) VALUES (?, ?, ?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt,"iisisi", $id, $time, $_POST['text'], $user['id'], $_SERVER['REMOTE_ADDR'], $disablesmilies);
	mysqli_stmt_execute($stmt);

	//now lets update the thread's last activity
	$stmt = mysqli_prepare($sql,"UPDATE `threads` SET `lastactivity` = ? WHERE `id` = $id");
	mysqli_stmt_bind_param($stmt,"i", $time);
	mysqli_stmt_execute($stmt);

	//print message
	printmessage("Reply has been posted!<br>Click <a href=topic.php?id=$id>here</a> to return to the thread");

	printfooter();
	die();
}


//print the topic name and a link back to the topic list
print "<a href=index.php>minibord</a> - <a href=forum.php?id=$thread[forumid]>$thread[forumname]</a> - <a href=topic.php?id=$id>$thread[name]</a> - new post<br><br>";


if(!empty($_POST['text']) && !empty($_POST['action']) && $_POST['action'] == "Preview")
{


	//Check if html is allowed
	if($options['enableHTML'] == true)
	{
		//If so, check if we're cleaning it up
		if($options['enablehtmLawed'] == true)
		{

			require_once($options['htmLawedPath']);
			$_POST['text'] = htmLawed($_POST['text'], $options['htmLawedconfig']);
	
		}
		//If not, then just...use the unsanitzed html :(
		else
			$_POST['text']=$_POST['text'];
	}
	else
	{
		//If its not enabled, let's just strip everything out
		$_POST['text']=htmlspecialchars($_POST['text']);
	}

	//Grab user input for the input field (after it's been optionally processed, that way a user can correct errors
	$whoopstext=$_POST['text'];


	//Lets make sure those disablesmilies and disablehtml boxes stay checked (or unchecked)
	if($disablesmilies==true)
		$smiliechecked="checked";


	//Lets start our preview table
	print "<table border=1 width=800 $themesettings[tableAttributes] class=\"table\">";

	$avatar="";
	if(!empty($user['avatarurl']))
	{
		$avatar="<img src=$user[avatarurl] style=\"max-width: 150px, max-height: 150px\"><br><br>";
	}

	$title="<br>";
	if(!empty($user['title']))
	{
		$title="<br><div class=\"smalltext\">$user[title]</div>";
	}

	//Check if they want smilies disabled or not
	if($disablesmilies == false)
	{
		$_POST['text']=showsmilies($_POST['text']);
	}


	//Print the post; use nl2br() to process line breaks so they look right
	print  "<tr><td rowspan=2 valign=top nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>".getusername($user['id'])."$title<br>$avatar</td>
		<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" valign=top>$user[postheader]".nl2br($_POST['text'],false)."<br><br>$user[postfooter]</td></tr>
		<tr><td nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" height=10>Date: ".date("Y-m-d H:i:s", time())."</td></tr></table><br>";



}

//If they gave absolutely nothing when something, anything, was expected, then lets tell them this
else if(isset($_POST['text']))
{
	//art thou blind? didst thou not see the entry rectangles?
	printmessage("thou must type before submitting");
}




//Heres our input form so users can add more posts
print  "<table border=1 width=345 $themesettings[tableAttributes] class=\"table\">
	<form method=\"post\">
	<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">reply</th></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=100>text:</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp<textarea cols=21 name=\"text\">$whoopstext</textarea></td></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;
		<input type=submit name=\"action\" value=Submit>
		<input type=submit name=\"action\" value=Preview>
		<input type=\"checkbox\" name=\"disablesmilies\" value=\"1\" $smiliechecked>Disable Smilies
	</td></tr></form></table><br>";

//Now lets grab some post history and order it descending
$posts=mysqli_query($sql, "SELECT `posts`.* FROM `posts` WHERE `threadid` = $id ORDER BY `date` DESC LIMIT 10");

//Lets start our table
print "<table border=1 width=800 $themesettings[tableAttributes] class=\"table\">
	<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">topic history</th></tr>

	";

//go through the posts data
while($post=mysqli_fetch_array($posts))
{
	//Let's clean up HTML just in case HTML was enabled at some point and then disabled
	if($options['enableHTML'] == false && $options['sanitizeExistingIfHTMLDisabled'] == true)
	{
		//The extra options prevent existing HTML entities from being re-escaped
		$post['text']=htmlspecialchars($post['text'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, NULL, false);
	}
	
	if($post['disablesmilies'] == false)
	{
		$post['text']=showsmilies($post['text']);
	}


	//Print each post; use nl2br() to process line breaks so they look right
	print  "<tr><td valign=top nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>".getusername($post['userid'])."</td>
		<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" valign=top>".nl2br($post['text'],false)."</td></tr>
		";
}

//End the post table
print "</table><br>";
//print the topic name and a link back to the topic list
print "<a href=index.php>minibord</a> - <a href=forum.php?id=$thread[forumid]>$thread[forumname]</a> - <a href=topic.php?id=$id>$thread[name]</a> - new post<br><br>";


	

//print our footer and call it a day
printfooter();

?>
