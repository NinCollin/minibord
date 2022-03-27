<?php

//Require the core file for logins and functions and stuff
require ('core.php');

//print our header
printheader();


if(!empty($_POST['submitSettings']))
{
	setcookie("theme", intval($_POST['themeID']));
	
	printmessage("Your settings have been successfully updated.<br><br>
			Click <a href=index.php>here</a> to return to the site.<br>
			Click <a href=settings.php>here</a> to edit settings again.");

}

else
{
	//lets get the list of themes
	$themes=mysqli_query($sql,"SELECT `id`, `name` FROM `themes`");


	//lets generate a select menu
	$selectMenu="<select name=\"themeID\">";

	if(!empty($_COOKIE['theme']))
		$selectedThemeID = intval($_COOKIE['theme']);
	else
		$selectedThemeID = 0;


	while($theme=mysqli_fetch_array($themes))
	{
		$selected="";
		if($selectedThemeID == $theme['id'])
			$selected = "selected";
		$selectMenu .= "  <option value=$theme[id] $selected>$theme[name]</option>";
	}

	//lets create our input table
	print  "<form method=\"post\" action=settings.php>
		<table border=1 width=345 $themesettings[tableAttributes]>
		<tr><th colspan=2>settings</th></tr>
		<tr><td colspan=2>note: saved to cookies</td></tr>
		<tr><td>theme: </td>  <td>&nbsp$selectMenu</td></tr>
		<tr><td colspan=2><input type=submit value=Submit><input type=hidden name=submitSettings value=true</td></tr>
		</table>
		</form>";
}	
printfooter();
?>