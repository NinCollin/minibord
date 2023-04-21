<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();

print "<table $themesettings[tableAttributes] class=\"table\">
	<tr><th $themesettings[thRegularAttributes] class=\"thRegular\">username</th><th $themesettings[thRegularAttributes] class=\"thRegular\" width=200>date registered</th><th $themesettings[thRegularAttributes] class=\"thRegular\" width=80># posts</th></tr>";

$users_query=mysqli_query($sql, "SELECT `id`, `regdate`, (SELECT COUNT(*) FROM `posts` WHERE `userid` = `users`.`id`) AS `numposts` FROM `users` ORDER BY `numposts` DESC");

while($users_data=mysqli_fetch_array($users_query))
{

	print "<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">".getusername($users_data['id'])."</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">".date("Y-m-d H:i:s", $users_data['regdate'])."</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">$users_data[numposts]</td></tr>";
}


print "</table><br>";
printfooter();
?>