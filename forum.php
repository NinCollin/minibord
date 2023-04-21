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
$whoopstext="";

//print our header
printheader();


//If the GET value refers to an invalid topic, then spew a generic error and end page execution
if(mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `forums` WHERE `id` = $id")) == 0)
	printdiemessage("no data");




//Calculate range of data to grab
$low = 20 * $page;

//count number of threads
$numthreads=mysqli_fetch_array(mysqli_query($sql, "SELECT COUNT(*) FROM `threads` WHERE `forumid` = $id"));

$pagelist="<div class=\"smalltext\">Pages: ";
$numpages=(ceil($numthreads[0]/20) - 1);
for($i = 0; $i <= $numpages; $i++)
{
	$p = $i + 1;
	if($i == $page)
		$pagelist.="$p ";
	else
		$pagelist.="<a href=forum.php?id=$id&page=$i>$p</a> ";

}
$pagelist.="</div>";


//Now lets grab our topic data and sort by how recently the topic was created (sorting by date of late post will be implemented lat0rz)
$threads=mysqli_query($sql, "SELECT * FROM `threads` WHERE `forumid` = $id ORDER BY `lastactivity` DESC LIMIT $low,20 ");

$forumname=mysqli_fetch_array(mysqli_query($sql, "SELECT `name` FROM `forums` WHERE `id` = $id"));



//if a user is logged in, show the "new topic" link
$newtopic="";
if(!empty($user))
{
$newtopic="<span class=\"smalltext\" style=\"float: right\"><a href=newtopic.php?id=$id>new topic</a></span>";

}




print "<a href=index.php>minibord</a> - $forumname[name]$newtopic<br>$pagelist";


//Lets start our threads table
print  "<table $themesettings[tableAttributes] class=\"table\">
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=16></th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\">topic name</th>  
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=150>human name</th>  
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=150>last human</td>  
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=200>last reply</th>  
	<th nowrap $themesettings[thRegularAttributes] class=\"thRegular\" width=80># posts</th>";

//lets go through our topic data and spit it out
while($thread=mysqli_fetch_array($threads))
{
	//grab number of posts in topic
	$postnum = mysqli_num_rows(mysqli_query($sql, "SELECT `id` FROM `posts` WHERE `threadid` = $thread[id]"));

	//get into on last reply
	$lastreply = mysqli_fetch_array(mysqli_query($sql, "SELECT `date`, `userid` FROM `posts` WHERE `threadid` = $thread[id] ORDER BY `date` DESC LIMIT 1"));
	
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
	if(empty($lastreply['userid']))
	{
		$lasthuman="<i>No Posts</i>";
	}
	
	//otherwise, give the last user to post in the topic
	else
	{
 		$lasthuman=getusername($lastreply['userid']);
	}
	
	$new="";
	if(empty($user))
	{
		$currenttime = time();
		$timediff = $currenttime - $thread['lastactivity'];
		if($timediff <= 3600)
		{
			$new="<img src=images/new.png>";
		}
	}
	else
	{
		$userlastforumread=mysqli_fetch_assoc(mysqli_query($sql,"SELECT `date` FROM `lastforumread` WHERE `lastforumread`.`userid` = $user[id] AND `lastforumread`.`forumid` = $thread[forumid]"));
		$userlastthreadread=mysqli_fetch_assoc(mysqli_query($sql,"SELECT `date` FROM `lastthreadread` WHERE `lastthreadread`.`userid` = $user[id] AND `lastthreadread`.`threadid` = $thread[id]"));
		
		if(empty($userlastthreadread['date']))
			$userlastthreaddate=NULL;
		else
			$userlastthreaddate=$userlastthreadread['date'];

		if(empty($userlastforumread['date']))
			$userlastforumdate=NULL;
		else
			$userlastforumdate=$userlastforumread['date'];


		if(!($userlastforumdate > $thread['lastactivity'] || $userlastthreaddate > $thread['lastactivity']))
		{
			$new="<img src=images/new.png>";
		
			
		}

	}

	//spit out all the data in a nice table
	print "<tr>
	  <td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle2\">$new</td>
	  <td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><a href=topic.php?id=$thread[id]>$thread[name]</a></td>
	  <td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle2\">".getusername($thread['userid'])."</td>
	  <td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle2\">$lasthuman</td>
	  <td nowrap $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$lastreplydate</td>
	  <td width=70 $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$postnum</td>
	</tr>
	";

}

//Lets wrap this table and page up
print "</table>";

print "$pagelist<a href=index.php>minibord</a> - $forumname[name]$newtopic<br><br>";

printfooter();

?>
