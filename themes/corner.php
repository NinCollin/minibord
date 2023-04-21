<?php

	
$themesettings = array(

	//You can overwrite the default banner with a special one if you choose
	"useCustomImageBanner" => false,
	"banner" => NULL,
	
	//Most styling should be done through CSS
	"styletag" => "
	<style>html * {font-family: monospace;}</style>",

	//Use only if necessary; CSS for styling is preferred

	//Attributes to inject into the <body> tag
	"bodyAttributes" => " background=images/cornerbg.png bgcolor=#C0C0C0 link=#3022b1",
	
	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "border=1 bgcolor=#C0C0C0",
	"thRegularAttributes" => "bgcolor=#C0C0C0",
	"thCategoryAttributes" => "bgcolor=#C0C0C0",
	"tdStyle1Attributes" => "bgcolor=#C0C0C0",
	"tdStyle2Attributes" => "bgcolor=#C0C0C0",

	//Atributes to inject into the header table tags
	"tableHeaderAttributes" => "cellspacing=0 cellpadding=0",
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