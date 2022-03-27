<?php

//Require the core file for logins and functions and stuff
require ('core.php');

//Declare some variables to check if a user submitted an empty field
$whoopsuser="";
$whoopsname="";

//print our header
printheader();

//If the user submitted a valid topic and username, lets add their topic to the database and tell them this
if(!empty($_POST['name']) && !empty($_POST['user']))
{
	//print message
	printmessage("topic posted");

	//sanitize user input so it doesn't render <script>'s and junk
	$_POST['name']=htmlspecialchars($_POST['name']);
	$_POST['user']=htmlspecialchars($_POST['user']);

	//prepared query time; insert dat data!
	$stmt = mysqli_prepare($sql,"INSERT INTO `threads` ( `date`, `name`, `user`, `ip`) VALUES (?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt,"isss", $time, $_POST['name'], $_POST['user'], $_SERVER['REMOTE_ADDR']);
	mysqli_stmt_execute($stmt);
}

//If their topic name was empty, show an error message
else if(isset($_POST['name']) && empty($_POST['name']) && !empty($_POST['user']))
{
	//print the error
	printmessage("you left your topic field empty");
	
	//lets be nice though and save the username they entered, so they dont have to retype it
	$whoopsuser=htmlspecialchars($_POST['user']);
}

//If their username was empty, show an error message
else if(isset($_POST['user']) && empty($_POST['user']) && !empty($_POST['name']))
{
	//print the error
	printmessage("you left your user field empty");

	//lets be nice though and save the topic name they entered, so they dont have to retype it
	$whoopsname=htmlspecialchars($_POST['name']);
}

//If they didnt type *anything,* then they receive yet a different error
else if(isset($_POST['name']) && isset($_POST['user']))
{
	//art thou blind? didst thou not see the entry rectangles?
	printmessage("thou must type before submitting");
}


//Now lets grab our topic data and sort by how recently the topic was created (sorting by date of late post will be implemented lat0rz)
$threads=mysqli_query($sql, "SELECT * FROM `threads` ORDER BY `date` DESC");

//lets create our input table
print  "<form method=\"post\" action=index.php>
	<table border=1 width=345 $themesettings[tableAttributes]>
	<tr><th colspan=2>create new topic</th></tr>
	<tr><td>topic name: </td>  <td>&nbsp<input align=right type=text value=\"$whoopsname\" maxlength=100 name=\"name\"></td></tr>
	<tr><td>human name: </td>  <td>&nbsp<input type=text maxlength=50 value=\"$whoopsuser\" name=\"user\"></td></tr>
	<tr><td colspan=2><input type=submit value=Submit></td></tr>
	</table>
	</form>";

//Lets start our threads table
print  "<table border=1 width=800 $themesettings[tableAttributes]>
	<th>topic name</th>  <th>human name</th>  <th>last human</td>  <th>last reply</th>  <th># posts</th>";

//lets go through our topic data and spit it out
while($thread=mysqli_fetch_array($threads))
{
	//grab number of posts in topic
	$postnum = mysqli_num_rows(mysqli_query($sql, "SELECT `id` FROM `posts` WHERE `threadid` = $thread[id]"));

	//get into on last reply
	$lastreply = mysqli_fetch_array(mysqli_query($sql, "SELECT `date`, `user` FROM `posts` WHERE `threadid` = $thread[id] ORDER BY `date` DESC LIMIT 1"));
	
	//if there aren't any replies, then lets say so
	if(empty($lastreply['date']))
	{
		$lastreplydate="<i>No Posts</i>";
	}
	
	//otherwise, say the date of the last reply
	else
	{
 		$lastreplydate=date("Y-m-d H:i:s", $lastreply['date']);
	}
	
	//if there aren't any replies, then lets say so
	if(empty($lastreply['user']))
	{
		$lasthuman="<i>No Posts</i>";
	}
	
	//otherwise, give the last user to post in the topic
	else
	{
 		$lasthuman=$lastreply['user'];
	}
	
	//spit out all the data in a nice table
	print "<tr>
	  <td><a href=topic.php?id=$thread[id]>$thread[name]</a></td>
	  <td>$thread[user]</td>
	  <td>$lasthuman</td>
	  <td>$lastreplydate</td>
	  <td width=70>$postnum</td>
	</tr>
	";

}

//Lets wrap this table and page up
print "</table>";
printfooter();

?>