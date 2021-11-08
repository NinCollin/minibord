<?php

//Require the core file for logins and functions and stuff
require ('core.php');

//Lets print the XML header and stuff
print  "<?xml version='1.0' encoding='UTF-8'?>
	<rss version='2.0'>
	<channel>

	<title>minibord</title>
	<link>$siteroot</link>
	<description>latests posts</description>
	<language>en-us</language>";

//Grab our topic and post data
$content=mysqli_query($sql,"SELECT `threads`.`name`, `posts`.`text`, `posts`.`user`, `threads`.`id`, `posts`.`date` FROM `posts` INNER JOIN `threads` ON `threads`.`id` = `posts`.`threadid` ORDER BY `date` DESC");

//nd lets go through it
while($post=mysqli_fetch_array($content))
{
	//and spit it out
	print "
		<item>
		  <title>$post[name]</title>
		  <link>$siteroot"."topic.php?id=$post[id]</link>
		  <description>$post[text]</description>
		  <pubDate>".date(DATE_RSS, $post['date'])."</pubDate>
		</item>";
}

//and then wrap this up
print "
</channel>
</rss>
";