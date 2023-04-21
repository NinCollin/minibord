<?php

if(empty($run))
	die('This file may not be run directly');

//Declare the variable for our user array
$user="";


//Lets make sure the user is authenticated correctly
if(!empty($_COOKIE['token']))
{

	//First, lets check that their login token is in the system
	$stmt = mysqli_prepare($sql,"SELECT `token`, `ipverification`, `ip`, `expires`, `userid` FROM `tokens` WHERE `token` = ?");
	mysqli_stmt_bind_param($stmt, "s", $_COOKIE['token']);
	mysqli_stmt_execute($stmt);

	//and fetch the results
	$token_query=mysqli_stmt_get_result($stmt);

	//Unset our stmt variable to free memory
	unset($stmt);

	//If the token isn't in the system, clear the user's cookie
	if(mysqli_num_rows($token_query) == 0)
	{
		setcookie("token", "");
	}

	//Otherwise, lets make sure its valid
	else
	{

		//Lets fetch an array from our querried token data
		$token_data=mysqli_fetch_array($token_query);	

		//Verify that the token hasn't expired and that the IP matched the user's IP (if IP verification is turned on)
		$time=time();
		if($token_data['expires'] > $time && ($_SERVER['REMOTE_ADDR'] == $token_data['ip'] || $token_data['ipverification'] == 0))
		{
			$user=mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM `users` WHERE `id` = $token_data[userid]"));

			$time=time();
			mysqli_query($sql, "UPDATE `users` SET `lastview` = $time WHERE `id` = $user[id]");

		}

		//check if the IP is different and IP verification is enabled; if so, log as a failed verification
		else if($_SERVER['REMOTE_ADDR'] != $token_data['ip'] && $token_data['ipverification'] == 1)
		{
			$stmt = mysqli_prepare($sql,"INSERT INTO `ipverificationfailures` (`originalip`, `failedip`, `userid`) VALUES (?, ?, ?)");
			mysqli_stmt_bind_param($stmt, "ssi", $token_data['ip'], $_SERVER['REMOTE_ADDR'], $token_data['userid']);
			mysqli_stmt_execute($stmt);


			
			$stmt = mysqli_prepare($sql,"DELETE FROM `tokens` WHERE `token` = ?");
			mysqli_stmt_bind_param($stmt, "s", $_COOKIE['token']);
			mysqli_stmt_execute($stmt);
			
			unset($stmt);

			setcookie("token", "");

		}
		//If it doesn't, remove the token from the database and unset their cookie
		else
		{
			
			$stmt = mysqli_prepare($sql,"DELETE FROM `tokens` WHERE `token` = ?");
			mysqli_stmt_bind_param($stmt, "s", $_COOKIE['token']);
			mysqli_stmt_execute($stmt);
			
			unset($stmt);

			setcookie("token", "");


		}
	}

	unset($token_query);
	unset($token_data);
}

//Now let's do some ip logging for security purposes
if(!empty($user))
{

	//Let's log the IP the user is connecting from
	$numberONE=1;
	$stmt = mysqli_prepare($sql,"INSERT INTO `iphistory_user` (`userid`, `ip`, `views`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `views` = `views` + 1, `lastuseragent` = ?");
	mysqli_stmt_bind_param($stmt, "isis", $user['id'], $_SERVER['REMOTE_ADDR'], $numberONE, $_SERVER['HTTP_USER_AGENT']);
	mysqli_stmt_execute($stmt);

}
else
{
	//lets do some guest loggin
	

	//add a guest entry
	$date=time();
	$ip=mysqli_real_escape_string($sql, $_SERVER['REMOTE_ADDR']);
	$numberONE=1;
	$stmt = mysqli_prepare($sql,"INSERT INTO `guests` (`ip`, `firstview`, `lastview`, `views`, `lastuseragent`) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `lastview` = ?, `views` = `views` + 1, `lastuseragent` = ?");
	mysqli_stmt_bind_param($stmt, "siiisis", $_SERVER['REMOTE_ADDR'], $date, $date, $numberONE, $_SERVER['HTTP_USER_AGENT'], $date, $_SERVER['HTTP_USER_AGENT']);
	mysqli_stmt_execute($stmt);



}



//Now let's do some more IP logging
if(empty($_COOKIE['sessiontoken']))
{
	$sessiontoken=bin2hex(random_bytes(30));

	//create a new persistant session cookie to track IP addresses
	setcookie("sessiontoken", $sessiontoken, 0, "", "", false, true);



	
}
else
{
	
	//Let's log the IP the user is connecting from
	//we didnt want to do this up above because if a user has cookies disabled, it'll had a whole new row to the database on every page load
	$numberONE=1;
	$stmt = mysqli_prepare($sql,"INSERT INTO `iphistory_global` (`token`, `ip`, `views`, `lastuseragent`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `views` = `views` + 1, `lastuseragent` = ?");
	mysqli_stmt_bind_param($stmt, "ssiss", $_COOKIE['sessiontoken'], $_SERVER['REMOTE_ADDR'], $numberONE, $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_USER_AGENT']);
	mysqli_stmt_execute($stmt);


}

//Lastly, lets increment the view counter
mysqli_query($sql, "INSERT INTO `config` (`name`, `value`) VALUES ('views', 1) ON DUPLICATE KEY UPDATE `value` = `value` + 1");
?>