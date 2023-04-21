<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//Lets print the XML header and stuff
print  "<?xml version='1.0' encoding='UTF-8'?>
	<rss version='2.0'>
	<channel>

	<title>minibord</title>
	<link>$siteroot</link>
	<description>latests posts</description>
	<language>en-us</language>";

//Grab our topic and post data
$content=mysqli_query($sql,"SELECT `threads`.`name`, `posts`.`text`, `posts`.`userid`, `threads`.`id`, `posts`.`date`, `users`.`name` AS `username` FROM `posts` INNER JOIN `threads` ON `threads`.`id` = `posts`.`threadid` INNER JOIN `users` ON `users`.`id` = `posts`.`userid` ORDER BY `date` DESC LIMIT $options[rssLimit]");

//nd lets go through it
while($post=mysqli_fetch_array($content))
{
	//and spit it out
	print "
		<item>
		  <title>$post[name]</title>
		  <link>$siteroot"."topic.php?id=$post[id]</link>
		  <description>$post[username] - $post[text]</description>
		  <pubDate>".date(DATE_RSS, $post['date'])."</pubDate>
		</item>";
}

//and then wrap this up
print "
</channel>
</rss>
";