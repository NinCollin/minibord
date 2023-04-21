<?php

$themesettings = array(

	//You can overwrite the default banner with a special one if you choose
	"useCustomImageBanner" => false,
	"banner" => NULL,
	
	//Most styling should be done through CSS
	"styletag" => "
		<style>
		html * {font-family: veranda;}
		.body { background-image: url(\"images/bgack.png\"); }
		
		.header { width: 100%;}
		.tableHeader {border: solid 1px #C7E1FF; border-collapse: collapse; width: 100%;}
		.tdHeaderBanner {border: solid 1px #C7E1FF; padding:3px; background: #3a506b; text-align: center;}
		.tdHeaderLinks1 {background: #33465E;border: solid 1px #C7E1FF; text-align: center;}
		.tdHeaderLinks2 {background: #33465E;border: solid 1px #C7E1FF; text-align: center;}
		.tdHeaderViewCounter {background: #33465E;border: solid 1px #C7E1FF; text-align: center;}
		.tdHeaderTime {background: #33465E;border: solid 1px #C7E1FF; text-align: center;}
		.tdHeaderSpacer {background: #33465E;border: solid 1px #C7E1FF; text-align: center; height: 4px}

		
		.content {width: 100%;}
		
		.table {border: solid 1px #C7E1FF; border-collapse: collapse; width: 100%;}
		.thRegular {background: #4E6D91; border: solid 1px #C7E1FF;}
		.thCategory {background: #4E6D91; border: solid 1px #C7E1FF;}
		.tdStyle1 {background: #3a506b;border: solid 1px #C7E1FF;}
		.tdStyle2 {background: #33465E;border: solid 1px #C7E1FF;}
		
		.smallText {font-size: 12px}
		.footer {text-align: center; font-size: 12px}
		
		</style>
		",

	//Use only if necessary; CSS for styling is preferred

	//Attributes to inject into the <body> tag
	"bodyAttributes" => "text=white alink=white vlink=white link=white",
	
	//Attributes to inject into <table> <td> and <th> tags
	"tableAttributes" => "",
	"thRegularAttributes" => "",
	"thCategoryAttributes" => "",
	"tdStyle1Attributes" => "",
	"tdStyle2Attributes" => "",

	//Atributes to inject into the header table tags
	"tableHeaderAttributes" => "",
	"tdHeaderBannerAttributes" => "",
	"tdHeaderLinks1Attributes" => "",
	"tdHeaderLinks2Attributes" => "",
	"tdHeaderViewCounterAttributes" => "",
	"tdHeaderTimeAttributes" => "",
	"tdHeaderSpacerAttributes" => "",

	//scheme path
	"schemePath" => "schemes/default.php"

	
);
	

?>