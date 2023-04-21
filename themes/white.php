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
	"bodyAttributes" => "bgcolor=#FFFFFF",
	
	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "border=1 bordercolor=#000000",
	"thRegularAttributes" => "bgcolor=#FFFFFF",
	"thCategoryAttributes" => "bgcolor=#FFFFFF",
	"tdStyle1Attributes" => "bgcolor=#FFFFFF",
	"tdStyle2Attributes" => "bgcolor=#FFFFFF",

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