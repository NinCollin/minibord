<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();

//If a user is marking a forum read, lets take care of that
if(!empty($_GET['action']) && $_GET['action'] == "markforumread")
{
	//Sanitize the forum id
	$forumid=intval($_GET['id']);

	//Make sure the forum exists
	$doesforumexist=mysqli_fetch_array(mysqli_query($sql, "SELECT COUNT(*) FROM `forums` WHERE `id` = $forumid"));

	//grab da time
	$date=time();		

	//Insert or update the lastforumread entry for the user and forum
	if($doesforumexist[0])
		mysqli_query($sql,"INSERT INTO `lastforumread` (`userid`, `forumid`, `date`) VALUES ('$user[id]', $forumid, $date) ON DUPLICATE KEY UPDATE `date` = $date");


}
//If a user is marking all forums read, lets do that instead
else if(!empty($_GET['action']) && $_GET['action'] == "markallforumsread")
{

	//grab da time
	$date=time();		
		
	//Loop through all the forums, and add (or update) a lastforumread entry for each one
	$forums=mysqli_query($sql,"SELECT `id` FROM `forums`");
	while($forum=mysqli_fetch_array($forums))
	{
		mysqli_query($sql,"INSERT INTO `lastforumread` (`userid`, `forumid`, `date`) VALUES ('$user[id]', $forum[id], $date) ON DUPLICATE KEY UPDATE `date` = $date");

	}
}


print "<table $themesettings[tableAttributes] class=\"table\">
	<tr><th $themesettings[thRegularAttributes] class=\"thRegular\" width=32></th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\">forum name</th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=90># threads</th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=80># posts</th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=150>last human</th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=200>last post</th></tr>";


$categories=mysqli_query($sql, "SELECT * FROM `categories` ORDER BY `displayorder`");

while($category=mysqli_fetch_array($categories))
{

	print "<tr><th colspan=6 $themesettings[thCategoryAttributes] class=\"thCategory\">$category[name]</th></tr>";

	$forums=mysqli_query($sql, "SELECT * FROM `forums` WHERE `catid` = $category[id] ORDER BY `displayorder`");

		
	while($forum=mysqli_fetch_array($forums))
	{

		$numthreads=mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `threads` WHERE `forumid` = $forum[id]"));
		$numposts=mysqli_num_rows(mysqli_query($sql, "SELECT NULL FROM `threads` INNER JOIN `posts` ON `threads`.`id` = `posts`.`threadid` WHERE `threads`.`forumid` = $forum[id]"));
		
		
		$lastpost=mysqli_fetch_array(mysqli_query($sql, "SELECT `posts`.`userid`, `posts`.`date` 
								FROM `threads` 
								INNER JOIN `posts` ON `threads`.`id` = `posts`.`threadid` 
								WHERE `threads`.`forumid` = $forum[id] ORDER BY `posts`.`date` DESC LIMIT 1"));

		$lastactivity=mysqli_fetch_array(mysqli_query($sql, "SELECT `threads`.`lastactivity` AS `date` FROM `threads` WHERE `threads`.`forumid` = $forum[id] ORDER BY `threads`.`lastactivity` DESC LIMIT 1"));
	
		if(!empty($lastpost['userid']))
			$lastpostuser=getusername($lastpost['userid']);
		else
			$lastpostuser="";

		if(!empty($lastpost['date']))
			$lastpostdate=date("Y-m-d H:i:s", $lastpost['date']);
		else
			$lastpostdate="<i>No Posts</i>";


		$new="";
		if(empty($user) && !empty($lastactivity))
		{
			$currenttime = time();
			$timediff = $currenttime - $lastactivity['date'];
			if($timediff <= 3600)
			{
				$new="<img src=images/new.png>";
			}
		}
		else if(!empty($user))
		{
			$userlastforumread=mysqli_fetch_assoc(mysqli_query($sql,"SELECT `date` FROM `lastforumread` WHERE `lastforumread`.`userid` = $user[id] AND `lastforumread`.`forumid` = $forum[id]"));

			if(empty($userlastforumread['date']))
				$userlastforumdate=NULL;
			else
				$userlastforumdate=$userlastforumread['date'];
			$numunread=mysqli_fetch_array(mysqli_query($sql,  "
  SELECT  COUNT(*) FROM `threads`  
  LEFT JOIN 
  (SELECT `lastthreadread`.`date`,`lastthreadread`.`threadid` FROM `lastthreadread` WHERE `lastthreadread`.`userid` = $user[id]) 
  AS `userlastthreadread` 
  ON `userlastthreadread`.`threadid` = `threads`.`id` 
  WHERE `threads`.`forumid` = $forum[id] 
  AND (`threads`.`lastactivity` > '$userlastforumdate' OR '$userlastforumdate' IS NULL) 
  AND (`threads`.`lastactivity` > `userlastthreadread`.`date` OR `userlastthreadread`.`date` IS NULL)
"));
			
			if(!empty($numunread[0]))
			{
			$new="<img src=images/new.png><br>$numunread[0]";

			}

		}

		print "<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle2\">$new</td>

			   <td $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><a href=forum.php?id=$forum[id]>$forum[name]</a><br><div class=\"smalltext\">$forum[description]</div></td>
			   <td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">$numthreads</td><td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">$numposts</td>
			   <td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$lastpostuser</td>
			   <td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$lastpostdate</td></tr>";
				
	}

}


//Lets wrap this table and page up
print "</table><br>";
printfooter();

?>
