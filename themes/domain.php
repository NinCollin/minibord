<?php

//Array of colors
$NCDcolorarray = array(
	0 => "#FFCCC5", 
	1 => "#F7D8A5", 
	2 => "#DBD1DC",
	3 => "#007D8C",
	4 => "#000000"
);

//Get the current date
$NCDdate=getdate();

//Turn the current time into the number of seconds since midnight
$NCDnumseconds = $NCDdate['hours'] * 3600 + $NCDdate['minutes'] * 60 + $NCDdate['seconds'];

//Divide and Floor it! (turns # of seconds into a number between 0 and 4, 0 being early in the morning and 4 being late at night etc)
$NCDindex = floor($NCDnumseconds/17280);

//If we're using the last color, lets change text color and banner
if($NCDindex==4)
{
	$NCDtextcolor = "#B53120";
	$NCDbanner = "images/NCDbanner2.png";
}

//Otherwise just use the normal ones
else
{
	$NCDtextcolor = "black";
	$NCDbanner = "images/NCDbanner1.png";
}

$themesettings = array(

	//You can overwrite the default banner with a special one if you choose
	"useCustomImageBanner" => true,
	"banner" => $NCDbanner,
	
	//Most styling should be done through CSS
	"styletag" => "
	<style>html * {font-family: monospace;}</style>",

	//Use only if necessary; CSS for styling is preferred

	//Attributes to inject into the <body> tag
	"bodyAttributes" => "bgcolor=$NCDcolorarray[$NCDindex] text=$NCDtextcolor",
	
	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "border=1 bgcolor=$NCDcolorarray[$NCDindex]",
	"thRegularAttributes" => "bgcolor=$NCDcolorarray[$NCDindex]",
	"thCategoryAttributes" => "bgcolor=$NCDcolorarray[$NCDindex]",
	"tdStyle1Attributes" => "bgcolor=$NCDcolorarray[$NCDindex]",
	"tdStyle2Attributes" => "bgcolor=$NCDcolorarray[$NCDindex]",

	//Atributes to inject into the header table tags
	"tableHeaderAttributes" => "",
	"tdHeaderBannerAttributes" => "",
	"tdHeaderLinks1Attributes" => "",
	"tdHeaderLinks2Attributes" => "",
	"tdHeaderViewCounterAttributes" => "hidden=true",
	"tdHeaderTimeAttributes" => "hidden=true",
	"tdHeaderSpacerAttributes" => "",

	//scheme path
	"schemePath" => "schemes/classic.php"

	
);
	
?>