<?php

$run=1;


//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();

$showinputboxes=true;
$whoopsuser="";
$whoopspassword="";

$userlist=mysqli_query($sql, "SELECT `regip` FROM `users`");


$accountsonip=0;
while($userrow=mysqli_fetch_array($userlist))
{
	if($_SERVER['REMOTE_ADDR'] == $userrow['regip'])
		$accountsonip++;
	
}

$isnametaken=0;
if(!empty($_POST['username']))
{
	$_POST['username']=substr($_POST['username'], 0, 25);


	$stmt = mysqli_prepare($sql,"SELECT `id` FROM `users` WHERE `name` = ?");
	mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
	mysqli_stmt_execute($stmt);
	$query=mysqli_stmt_get_result($stmt);

	if(mysqli_num_rows($query) >= 1)
		$isnametaken = 1;
}


if(!empty($user))
{
	printmessage("you cannot register while logged in");
	printfooter();
	die();
	
}

else if($isnametaken)
{
	printmessage("that username is in use!");

}

else if($accountsonip >=2)
{
	printmessage("you can't make more than 2 accounts per ip!");
	printfooter();
	die();
	
}

else if(!empty($_POST['username']) && !empty($_POST['password']) )
{
	
	
	//sanitize user input so it doesn't render <script>'s and junk and also truncate it to 25 characters
	$_POST['username']=htmlspecialchars(substr($_POST['username'], 0, 25));
	
	//time to hash the users password
	$hashedpassword=password_hash(substr($_POST['password'], 0, 70), PASSWORD_DEFAULT);

	//prepared query time; insert dat data!
	$stmt1 = mysqli_prepare($sql,"INSERT INTO `users` ( `name`, `password`, `regip`, `regdate`) VALUES (?, ?, ?, ?)");
	$time=time();	
	mysqli_stmt_bind_param($stmt1,"sssi", $_POST['username'], $hashedpassword, $_SERVER['REMOTE_ADDR'], $time);
	mysqli_stmt_execute($stmt1);


	printmessage("Your account has been successfully added.<br><br>
			Click <a href=login.php>here</a> to login to the site.<br>");
			
	$showinputboxes=false;

}

else if(!empty($_POST['username']) && empty($_POST['password']))
{
	printmessage("you left your password blank");
	$whoopsuser=htmlspecialchars($_POST['username']);

	
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
	print  "<form method=\"post\" action=register.php>
		<table $themesettings[tableAttributes] class=\"table\">
		<tr><th colspan=2 $themesettings[thRegularAttributes] class=\"thRegular\">register</th></tr>
		<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=200>human name:</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=text length=25 width=25 name=username value=\"$whoopsuser\"></td></tr>
		<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\">password:</td><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=password length=70 width=25 name=password></td></tr>
		<tr><td colspan=2 $themesettings[tdStyle1Attributes] class=\"tdStyle1\"><input type=submit value=Submit></tr>
		</table>
		</form>";
}	
printfooter();
?>
