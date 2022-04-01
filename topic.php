<?php

//Require the core file for logins and functions and stuff
require ('core.php');

//Sanitize GET data
$id=intval($_GET['id']);

//Declare some variables to check if a user submitted an empty field
$whoopsuser="";
$whoopstext="";

//print our header
printheader();

//If the GET value refers to an invalid topic, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT * FROM `threads` WHERE `id` = $id")) == 0)
	die(printmessage("no data"));

//Lets grab our topic data
$thread=mysqli_fetch_array(mysqli_query($sql, "SELECT `name` FROM `threads` WHERE `id` = $id"));

//print the topic name and a link back to the topic list
print "<a href=index.php>minibord</a> - $thread[name]<br><br>";

//If the user submitted valid text and username, lets add their post to the database and tell them this
if(!empty($_POST['text']) && !empty($_POST['user']))
{
	//print message
	printmessage("reply posted");
	
	//sanitize user input so it doesn't render <script>'s and junk
	$_POST['text']=htmlspecialchars($_POST['text']);
	$_POST['user']=htmlspecialchars($_POST['user']);

	//prepared query time; insert dat data!
	$stmt = mysqli_prepare($sql,"INSERT INTO `posts` ( `threadid`, `date`, `text`, `user`, `ip`) VALUES (?, ?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt,"iisss", $id, $time, $_POST['text'], $_POST['user'], $_SERVER['REMOTE_ADDR']);
	mysqli_stmt_execute($stmt);

	//now lets update the thread's last activity
	$stmt = mysqli_prepare($sql,"UPDATE `threads` SET `lastactivity` = ? WHERE `id` = $id");
	mysqli_stmt_bind_param($stmt,"i", $time);
	mysqli_stmt_execute($stmt);

}

//If their text field was empty, show an error message
else if(isset($_POST['text']) && empty($_POST['text']) && !empty($_POST['user']))
{
	//print the error
	printmessage("you left your text field empty");

	//lets be nice though and save the username they entered, so they dont have to retype it
	$whoopsuser=htmlspecialchars($_POST['user']);
}

//If their username was empty, show an error message
else if(isset($_POST['user']) && empty($_POST['user']) && !empty($_POST['text']))
{
	//print the error
	printmessage("you left your user field empty");

	//lets be *very* nice and save the text they entered, so they dont have to retype that juicy post
	$whoopstext=htmlspecialchars($_POST['text']);
}

//If they gave absolutely nothing when something, anything, was expected, then lets tell them this
else if(isset($_POST['text']) && isset($_POST['user']))
{
	//art thou blind? didst thou not see the entry rectangles?
	printmessage("thou must type before submitting");
}

//Now lets grab our posts and order them by the date they were submitted
$posts=mysqli_query($sql, "SELECT * FROM `posts` WHERE `threadid` = $id ORDER BY `date`");

//Lets start our table
print "<table border=1 width=800 $themesettings[tableAttributes]>";

//go through the posts data
while($post=mysqli_fetch_array($posts))
{

	//Print each post; use nl2br() to process line breaks so they look right
	print  "<tr><td rowspan=2 valign=top nowrap>$post[user]</td>
		<td>".nl2br($post['text'],false)."</td></tr>
		<tr><td nowrap>Date: ".date("Y-m-d H:i:s", $post['date'])."</td></tr>";
}

//End the post table
print "</table><br>";

//Heres our input form so users can add more posts
print  "<table border=1 width=345 $themesettings[tableAttributes]>
	<form method=\"post\">
	<tr><th colspan=2>reply</th></tr>
	<tr><td>text:</td><td>&nbsp<textarea cols=21 name=\"text\">$whoopstext</textarea></td></tr>
	<tr><td>human name: </td><td>&nbsp<input type=text maxlength=50 value=\"$whoopsuser\" name=\"user\"></td></tr>
	<tr><td colspan=2><input type=submit value=Submit></td></tr></form></table>";

//print our footer and call it a day
printfooter();

?>

