<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();

//Sanitize GET data
$id=intval($_GET['id']);

//Declare some variables to check if a user submitted an empty field
$whoopstext="";
$whoopsname="";
$smiliechecked="";


//This value is used for checking if the post wants smilies disabled
$disablesmilies=0;
if(!empty($_POST['disablesmilies']))
{
	$disablesmilies=1;
}



//If the GET value refers to an invalid forum, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `forums` WHERE `id` = $id")) == 0)
	die(printmessage("forum not found") . printfooter());

//Lets grab our forum data
$forum=mysqli_fetch_array(mysqli_query($sql, "SELECT `forums`.`name` FROM `forums` WHERE `forums`.`id` = $id"));


//print the forum name and a link back to the thread list
print "<a href=index.php>minibord</a> - <a href=forum.php?id=$id>$forum[name]</a> - new topic<br><br>";


//If the user isn't logged in, dont let them post!
if(empty($user))
{
		printmessage("you must be logged in to create a new topic");
		printfooter();
		die();
}


//If the user submitted valid text and username, lets add their post to the database and tell them this
if(!empty($_POST['text']) && !empty($_POST['name']) && !empty($_POST['action']) && $_POST['action'] == "Submit")
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

	//sanitize thread title so it doesn't render <script>'s and junk; the post's text is sanitized at run-time
	$_POST['name']=htmlspecialchars($_POST['name']);

	
	//prepared query time; insert dat data!
	//first, add the thread
	$stmt = mysqli_prepare($sql,"INSERT INTO `threads` ( `forumid`, `name`, `date`, `userid`, `ip`, `lastactivity`) VALUES (?, ?, ?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt,"isiisi", $id, $_POST['name'], $time, $user['id'], $_SERVER['REMOTE_ADDR'], $time);
	mysqli_stmt_execute($stmt);
	
	//get the thread id from that
	$threadid=mysqli_insert_id($sql);

	//then the post
	$stmt = mysqli_prepare($sql,"INSERT INTO `posts` ( `threadid`, `date`, `text`, `userid`, `ip`, `disablesmilies`) VALUES (?, ?, ?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt,"iisisi", $threadid, $time, $_POST['text'], $user['id'], $_SERVER['REMOTE_ADDR'], $disablesmilies);
	mysqli_stmt_execute($stmt);

	//print message
	printmessage("Topic has been posted!<br>Click <a href=topic.php?id=$threadid>here</a> to return to go to the thread");

	printfooter();
	die();
}



else if(!empty($_POST['text']) && !empty($_POST['name']) && !empty($_POST['action']) && $_POST['action'] == "Preview")
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


	//Grab the topic name too
	$whoopsname=htmlspecialchars($_POST['name']);
	
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

	print  "<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">topic preview</th></tr>
		<tr><td colspan=2 $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><b>".htmlspecialchars($_POST['name'])."</b></th></tr>
		<tr><td rowspan=2 valign=top nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>".getusername($user['id'])."$title<br>$avatar</td>
		<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" valign=top>$user[postheader]".nl2br($_POST['text'],false)."<br><br>$user[postfooter]</td></tr>
		<tr><td nowrap  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" height=10>Date: ".date("Y-m-d H:i:s", time())."</td></tr></table><br>";



}
else if(!empty($_POST['text']) && empty($_POST['name']) && !empty($_POST['action']))
{

	$whoopstext=$_POST['text'];
	printmessage("thou must type a name for thou topic");


}

else if(empty($_POST['text']) && !empty($_POST['name']) && !empty($_POST['action']))
{
	$whoopsname=$_POST['name'];
	printmessage("thou must type a message for thou topic");


}

else if(empty($_POST['text']) && empty($_POST['name']) && !empty($_POST['action']))
{

	printmessage("thou must type before submitting");


}


//Heres our input form so users can add a new topic
print  "<table border=1 width=345 $themesettings[tableAttributes] class=\"table\">
	<form method=\"post\">
	<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">new topic</th></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=100>name:</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp<input type=\"text\" maxlength=100 name=\"name\" value=\"$whoopsname\"></td></tr>

	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=100>text:</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp<textarea cols=21 name=\"text\">$whoopstext</textarea></td></tr>
	<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;
		<input type=submit name=\"action\" value=Submit>
		<input type=submit name=\"action\" value=Preview>
		<input type=\"checkbox\" name=\"disablesmilies\" value=\"1\" $smiliechecked>Disable Smilies
	</td></tr></form></table><br>";


//print the forum name and a link back to the thread list
print "<a href=index.php>minibord</a> - <a href=forum.php?id=$id>$forum[name]</a> - new topic<br><br>";


//print our footer and call it a day
printfooter();

?>
