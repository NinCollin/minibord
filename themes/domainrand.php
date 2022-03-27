<?php

$NCDindex=random_int(0,4);

//Array of colors
$NCDcolorarray = array(
	0 => "#FFCCC5", 
	1 => "#F7D8A5", 
	2 => "#DBD1DC",
	3 => "#007D8C",
	4 => "#000000"
);

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

//Now lets populate our theme array
$themesettings = array(
	"useImageBanner" => true,
	"banner" => $NCDbanner, //Use banner based on color scheme

	"backgroundColor" => "$NCDcolorarray[$NCDindex]", //Use color from array based on time of day
	"backgroundImage" => NULL,
	"useBackgroundImage" => false,

	"textColor" => $NCDtextcolor,

	"tableBorderColor" => "#000000",
	"tableBackgroundColor" => "$NCDcolorarray[$NCDindex]", //Use color from array based on time of day
	"tableBackgroundImage" => NULL,
	"useTableBackgroundImage" => false,
	
	"bodyAttributes" => "",
	"useAutoBodyAttributes" => true,
	"tableAttributes" => "",
	"useAutoTableAttributes" => true


);
	
?>