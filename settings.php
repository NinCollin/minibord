<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');

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
	$themes=mysqli_query($sql,"SELECT `id`, `name` FROM `themes` ORDER BY `displayorder`");


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
		<table $themesettings[tableAttributes] class=\"table\">
		<tr><th colspan=2  $themesettings[thRegularAttributes] class=\"thRegular\">settings</th></tr>
		<tr><td colspan=2  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">note: saved to cookies</td></tr>
		<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=100>theme: </td>  <td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp$selectMenu</td></tr>
		<tr><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;</td><td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\">&nbsp;<input type=submit value=Submit><input type=hidden name=submitSettings value=true</td></tr>
		</table>
		</form>";
}	
printfooter();
?>