<?php

if(empty($run))
	die('This file may not be run directly');

//Let's populate the theme array with the default settings
$themesettings = array(

	//You can overwrite the default banner with a special one if you choose
	"useCustomImageBanner" => false,
	"banner" => NULL,
	
	//Most styling should be done through CSS
	"styletag" => "<style>
		.body;
	
		.header;
		.tableHeader;
		.tdBanner;
		.tdHeaderLinks1;
		.tdHeaderLinks2;
		.tdHeaderViewCounter;
		.tdHeaderTime;
		.tdHeaderSpacer;
		
		.content;
		
		.table;
		.thRegular;
		.thCategory;
		.tdStyle1;
		.tdStyle2;
		
		.smallText;
		.footer;

		</style>",

	//Use only if necessary; CSS for styling is preferred

	//Attributes to inject into the <body> tag
	"bodyAttributes" => "",

	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "",
	"thRegularAttributes" => "",
	"thCategoryAttributes" => "",
	"tdStyle1Attributes" => "",
	"tdStyle2Attributes" => "",

	//Atributes to inject into the header table tags
	"tableHeaderAttributes" => "",
	"tdBannerAttributes" => "",
	"tdHeaderLinks1Attributes" => "",
	"tdHeaderLinks2Attributes" => "",
	"tdHeaderViewCounterAttributes" => "",
	"tdHeaderTimeAttributes" => "",
	"tdHeaderSpacerAttributes" => ""



);

$isCookieThemeIDValid=0;
$cookieThemeID=0;
if(!empty($_COOKIE['theme']) && empty($user['theme']))
{
	$cookieThemeID=intval($_COOKIE['theme']);
	$isCookieThemeIDValid=mysqli_num_rows(mysqli_query($sql,"SELECT `id` FROM `themes` WHERE `id` = $cookieThemeID"));
}
if(!empty($user['theme']))
{
	$themeArray=mysqli_fetch_array(mysqli_query($sql,"SELECT `path` FROM `themes` WHERE `id` = $user[theme]"));
	include($themeArray['path']);
}
elseif(!empty($isCookieThemeIDValid))
{
	$themeArray=mysqli_fetch_array(mysqli_query($sql,"SELECT `path` FROM `themes` WHERE `id` = $cookieThemeID"));
	include($themeArray['path']);
}
else
{
	$themeArray=mysqli_fetch_array(mysqli_query($sql,"SELECT `path` FROM `themes` WHERE `id` = $defaultThemeID"));
	include($themeArray['path']);
}

if(!empty($themesettings['schemePath']))
{

	include($themesettings['schemePath']);
}
else
{
	include($defaultSchemePath);
}

?>