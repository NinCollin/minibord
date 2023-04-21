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
	"bodyAttributes" => "bgcolor=#1d3358, background=images/bgR.png text=#c6d5ec vlink=#ffffff link=#ffffff",
	
	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "border=1",
	"thRegularAttributes" => "bgcolor=#968cd6 background=\"images/bgR2.png\"",
	"thCategoryAttributes" => "bgcolor=#968cd6 background=\"images/bgR2.png\"",
	"tdStyle1Attributes" => "bgcolor=#968cd6 background=\"images/bgR2.png\"",
	"tdStyle2Attributes" => "bgcolor=#968cd6 background=\"images/bgR2.png\"",

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