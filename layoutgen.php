<?php

//Let's populate the theme array with the default settings
$themesettings = array(
	"useImageBanner" => false,
	"banner" => NULL,

	"backgroundColor" => "#FFFFFF",
	"backgroundImage" => NULL,
	"useBackgroundImage" => false,

	"textColor" => "black",

	"tableBorderColor" => "#000000",
	"tableBackgroundColor" => "#FFFFFF",
	"tableBackgroundImage" => NULL,
	"useTableBackgroundImage" => false,

	"bodyAttributes" => "",
	"useAutoBodyAttributes" => true,
	"tableAttributes" => "",
	"useAutoTableAttributes" => true

);

$isCookieThemeIDValid=0;
$cookieThemeID=0;
if(!empty($_COOKIE['theme']))
{
	$cookieThemeID=intval($_COOKIE['theme']);
	$isCookieThemeIDValid=mysqli_num_rows(mysqli_query($sql,"SELECT `id` FROM `themes` WHERE `id` = $cookieThemeID"));
}

if(!empty($isCookieThemeIDValid))
{
	$themeArray=mysqli_fetch_array(mysqli_query($sql,"SELECT `path` FROM `themes` WHERE `id` = $cookieThemeID"));
	include($themeArray['path']);
}
else
{
	$themeArray=mysqli_fetch_array(mysqli_query($sql,"SELECT `path` FROM `themes` WHERE `id` = $defaultThemeID"));
	include($themeArray['path']);
}

//Time to create some variables to give various attributes to our board

//If the theme uses a background image and provides a nonempty link, lets use that image
if($themesettings['useAutoBodyAttributes'] && $themesettings['useBackgroundImage'] && !empty($themesettings['backgroundImage']))
{ 
	
	$themesettings['bodyAttributes'] .= " background=\"$themesettings[backgroundImage]\" bgcolor=\"$themesettings[backgroundColor]\" text=\"$themesettings[textColor]\"";
}
//Otherwise use the background color
else if($themesettings['useAutoBodyAttributes'])
{

	$themesettings['bodyAttributes'] .= " bgcolor=\"$themesettings[backgroundColor]\" text=\"$themesettings[textColor]\"";
}


//If the theme uses a background image for tables and provides a nonempty link, lets use that image
if($themesettings['useAutoTableAttributes'] && $themesettings['useTableBackgroundImage'] && !empty($themesettings['tableBackgroundImage']))
{
	$themesettings['tableAttributes'] .= " background\"$themesettings[tableBackgroundImage]\" bgcolor=\"$themesettings[tableBackgroundColor]\" bordercolor=\"$themesettings[tableBorderColor]\"";
}
//Otherwise use the background color
else if($themesettings['useAutoTableAttributes'])
{
	$themesettings['tableAttributes'] .= " bgcolor=\"$themesettings[tableBackgroundColor]\" bordercolor=\"$themesettings[tableBorderColor]\"";
}


?>