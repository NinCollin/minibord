<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

//print our header
printheader();

if(empty($user))
{
	printmessage("you cannot edit your profile while logged out");
	printfooter();
	die();
	
}

if(empty($_POST['submit']))
{
print  "<form action=editprofile.php method=POST>

	<table $themesettings[tableAttributes] class=\"table\">
	<tr><th $themesettings[thRegularAttributes] class=\"thRegular\">&nbsp;</th>
	<th $themesettings[thRegularAttributes] class=\"thRegular\" width=400>&nbsp;</th></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Username:</b><br><div class=\"smalltext\" align=left>To request a username change, please contact an admin.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">$user[name]</td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Password:</b><br><div class=\"smalltext\" align=left>You can change your password here if desired.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=password length=70 width=25 name=password></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Namecolor:</b><br><div class=\"smalltext\" align=left>Your name color.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">";

	$checked0 = "";
	$checked1 = ""; 
	$checked2 = "";
	if($user['namecolor'] == 0)
		$checked0 = "checked";
	elseif($user['namecolor'] == 1)
		$checked1 = "checked";
	else
		$checked2 = "checked";

	print "
      
	<input type=radio id=colorchoice name=\"namecolor\" value=0 $checked0> Color 1

	<input type=radio id=colorchoice name=\"namecolor\" value=1 $checked1> Color 2

	<input type=radio id=colorchoice name=\"namecolor\" value=2 $checked2> Color 3

	</td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Avatar URL:</b><br><div class=\"smalltext\" align=left>This is picture that shows up next to all your posts.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=text name=avatarurl length=100 size=40 value=\"$user[avatarurl]\"></td></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Minipic URL:</b><br><div class=\"smalltext\" align=left>A 16x16 picture that shows up next to your username.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=text name=minipicurl length=100 size=40 value=\"$user[minipicurl]\"></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Homepage URL:</b><br><div class=\"smalltext\" align=left>The URL of your homepage.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=text name=homepageurl length=100 size=40 value=\"$user[homepageurl]\"></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Homepage Name:</b><br><div class=\"smalltext\" align=left>The name of your homepage.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=text name=homepagename length=100 size=40 value=\"$user[homepagename]\"></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Email Address:</b><br><div class=\"smalltext\" align=left>Your email address; not required.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=text name=email length=100 size=40 value=\"$user[email]\"></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Birthday:</b><br><div class=\"smalltext\" align=left>Your birthday in YYYY-MM-DD format; not required.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">";

	if(!empty($user['birthday']))
	{
		$birthday=getdate($user['birthday']);
		$year = "value=$birthday[year]";
		$month = "value=$birthday[mon]";
		$day = "value=$birthday[mday]";


	}
	else
	{
		$year = "";
		$month = "";
		$day = "";
	}
	print "

	<input type=number name=year size=8 min=1900 max=2099 $year> - <input type=number name=month size=6 min=1 max=12 $month> - <input type=number name=day size=6 min=1 max=31 $day></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Bio:</b><br><div class=\"smalltext\" align=left>Here you can write information about yourself to be displayed on your profile.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><textarea name=bio>$user[bio]</textarea></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Post Header:</b><br><div class=\"smalltext\" align=left>Stuff in this box is displayed at the top of your posts.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><textarea name=postheader>$user[postheader]</textarea></td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Post Footer:</b><br><div class=\"smalltext\" align=left>Stuff in this box is displayed at the bottom of your posts.</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><textarea name=postfooter>$user[postfooter]</textarea></td></tr>";



	//lets get the list of themes
	$themes=mysqli_query($sql,"SELECT `id`, `name` FROM `themes` ORDER BY `displayorder`");


	//lets generate a select menu
	$selectMenu="<select name=\"theme\" autocomplete=off>";

	if(!empty($user['theme']))
		$selectedThemeID = intval($user['theme']);
	else
		$selectedThemeID = $defaultThemeID;


	while($theme=mysqli_fetch_array($themes))
	{
		$selected="";
		if($selectedThemeID == $theme['id'])
			$selected = "selected"; 
		$selectMenu .= "  <option value=$theme[id] $selected>$theme[name]</option>";
	}

	$selectMenu .= "</select>";

	print "
	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center><b>Theme:</b><br><div class=\"smalltext\" align=left>The theme you want to use (overrides cookie settings.)</div></td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\">$selectMenu</td></tr>

	<tr><td $themesettings[tdStyle1Attributes] class=\"tdStyle1\" align=center>&nbsp;</td>
	<td $themesettings[tdStyle2Attributes] class=\"tdStyle2\"><input type=submit name=submit value=Submit></td></tr>



	</table>

	</form>";
}

else
{
	//Intval our number thingies
	$_POST['namecolor']=intval($_POST['namecolor']);
	$_POST['year']=intval($_POST['year']);
	$_POST['month']=intval($_POST['month']);
	$_POST['day']=intval($_POST['day']);
	$_POST['theme']=intval($_POST['theme']);
	$_POST['homepageurl']=htmlspecialchars($_POST['homepageurl']);
	$_POST['homepagename']=htmlspecialchars($_POST['homepagename']);
	$_POST['avatarurl']=htmlspecialchars($_POST['avatarurl']);
	$_POST['minipicurl']=htmlspecialchars($_POST['minipicurl']);
	$_POST['email']=htmlspecialchars($_POST['email']);


	//we intval'd our POST data, so this query is safe
	$isThemeIDValid=mysqli_num_rows(mysqli_query($sql,"SELECT `id` FROM `themes` WHERE `id` = $_POST[theme]"));

	//if the theme id isn't valid, fix it!
	if(!$isThemeIDValid)
	{
		$_POST['theme'] = $defaultThemeID;
	}
	
	//if the namecolor isnt a valid choice, fix it!
	if($_POST['namecolor'] < 0 || $_POST['namecolor'] > 2)
	{
		$_POST['namecolor'] = 0;
	}

	if($_POST['year'] == 0 || $_POST['month'] == 0 || $_POST['day'] == 0)
		$birthday="";
	else
		$birthday=strtotime("$_POST[year]/$_POST[month]/$_POST[day]");

	//Check if html is allowed
	if($options['enableHTML'] == true)
	{
		//If so, check if we're cleaning it up
		if($options['enablehtmLawed'] == true)
		{

			require_once($options['htmLawedPath']);
			$_POST['postheader'] = htmLawed($_POST['postheader'], $options['htmLawedconfig']);
			$_POST['postfooter'] = htmLawed($_POST['postfooter'], $options['htmLawedconfig']);
			$_POST['bio'] = htmLawed($_POST['bio'], $options['htmLawedconfig']);



		
		}
	}
	else
	{
		//If its not enabled, let's just strip everything out
		$_POST['bio']=htmlspecialchars($_POST['bio']);
		$_POST['postheader']=htmlspecialchars($_POST['postheader']);
		$_POST['postfooter']=htmlspecialchars($_POST['postfooter']);

	}

	//prepared query time; update dat data!
	$stmt1 = mysqli_prepare($sql,"UPDATE `users` SET `namecolor` = ?, `postheader` = ?, `postfooter` = ?, `avatarurl` = ?, `minipicurl` = ?, `email` = ?, `homepageurl` = ?, `homepagename` = ?, `birthday` = ?, `bio` = ?, `theme` = ? WHERE `id` = $user[id]");
	mysqli_stmt_bind_param($stmt1,"isssssssisi", $_POST['namecolor'], $_POST['postheader'], $_POST['postfooter'], $_POST['avatarurl'], $_POST['minipicurl'], $_POST['email'], $_POST['homepageurl'], $_POST['homepagename'], $birthday, $_POST['bio'], $_POST['theme'],);
	mysqli_stmt_execute($stmt1);

	
	//if the user changed their password, lets update it seperately
	if(!empty($_POST['password']))
	{
		$hashedpassword=password_hash(substr($_POST['password'], 0, 70), PASSWORD_DEFAULT);

		//prepared query time; update dat data!
		$stmt2 = mysqli_prepare($sql,"UPDATE `users` SET `password` = ? WHERE `id` = $user[id]");	
		mysqli_stmt_bind_param($stmt2,"s", $hashedpassword);
		mysqli_stmt_execute($stmt2);


	}

	printmessage("Your profile has been successfully updated.<br><br>
			Click <a href=profile.php?id=$user[id]>here</a> to view your profile.<br>");


}

printfooter();
?>
