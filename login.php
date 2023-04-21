<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

$showinputboxes=true;


if(!empty($_GET['action']) && $_GET['action'] == "logout" && !empty($user))
{


			
	$stmt4 = mysqli_prepare($sql,"DELETE FROM `tokens` WHERE `token` = ?");
	mysqli_stmt_bind_param($stmt4, "s", $_COOKIE['token']);
	mysqli_stmt_execute($stmt4);
	
 	setcookie("token", "");

	printheader();
	printmessage("thou hast been logged out");
	printfooter();
	die();


}

//print our header
printheader();

if(!empty($user))
{
	printmessage("thou cannot login while logged in");
	printfooter();
	die();
	
}



else if(!empty($_POST['username']) && !empty($_POST['password']) )
{
	
	
	$stmt = mysqli_prepare($sql,"SELECT `id`, `password` FROM `users` WHERE `name` = ?");
	$time=time();	
	mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
	mysqli_stmt_execute($stmt);
	$query=mysqli_stmt_get_result($stmt);

	if(mysqli_num_rows($query) == 0)
	{
		printmessage("user not found");
	}
	else
	{

	
		$userarray=mysqli_fetch_array($query);

		$verified=password_verify(substr($_POST['password'], 0, 70), $userarray['password']);
	
		if(!$verified)
		{
			printmessage("auth failed; please try again");
		}

		else
		{
			$new_token=bin2hex(random_bytes(30));
			$duration=time() + 604800;
			mysqli_query($sql, "INSERT INTO `tokens` ( `token`, `ipverification`, `ip`, `expires`, `userid`) VALUES ('$new_token', 1, '$_SERVER[REMOTE_ADDR]', $duration, $userarray[id])") or die(mysqli_error($sql));
			setcookie("token", $new_token, 0, "", "", false, true);
			printmessage("you are now logged in!<br><br>Click <a href=index.php>here</a> to return to the site.");
			$showinputboxes=false;
		} 



	}

}

else if(!empty($_POST['username']) && empty($_POST['password']))
{
	printmessage("you left your password blank");


	
}

else if(empty($_POST['username']) && !empty($_POST['password']))
{
	printmessage("you left your username blank");


}

else if(isset($_POST['username']) && isset($_POST['password']) )
{
	printmessage("thou must type before submitting");
}


if($showinputboxes)
{

	//lets create our input table
	print  "<form method=\"post\" action=login.php>
		<table $themesettings[tableAttributes] class=\"table\">
		<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">login</th></tr>
		<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>human name:</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=text length=25 width=25 name=username></td></tr>
		<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">password:</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=password length=70 width=25 name=password></td></tr>
		<tr><td colspan=2 $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=submit value=Submit></tr>
		</table>
		</form>";
}	
printfooter();
?>
