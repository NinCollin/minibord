<?php

$starttime=hrtime(true);

//Disables all javascript using Content Security Policy; requires a CSP-compatible browser however
header('Content-Security-Policy: script-src \'self\';');

if(empty($run))
	die('This file may not be run directly');


//BEGIN CONFIG

//Connect to mysql server
$sql=mysqli_connect( 'host', 'username', 'password', 'database') or die('Couldn\'t connect to database!');
mysqli_query($sql,"SET profiling = 1;");

//used for rss lol
$siteroot="http://mybord.example.fish/";

//Max number of posts to grab via RSS
$options['rssLimit'] = 20;

//Name of board
$boardname="mybord";

//Default theme id
$defaultThemeID=7;

//default scheme path
$defaultSchemePath="schemes/default.php";

//default banner
$defaultbanner="images/logoo.png";

//If true, please enable htmLawed, otherwise you will be vulnerable to XSS!!!!
$options['enableHTML'] = false; //If true, please enable htmLawed, otherwise you will be vulnerable to XSS!!!!

//If true, resanitizes existing HTML already in database when displayed (makes no changes to the database) 
//Useful if HTML was enabled at one point, but later disabled (to prevent already stored HTML from rendering)
//Only takes effect if 'enableHTML' is false
//Note that this *will* be messy (especially if users had post layouts) so it's mostly useful for emergency purposes
$options['sanitizeExistingIfHTMLDisabled'] = true;

//Clean up and prevent HTML abuse; requires an external library
//Please read through the default htmLawed config below before enabling this
//If you follow the minibord installation instructions, the htmLawedPath should not need to be changed
$options['enablehtmLawed'] = true; 
$options['htmLawedPath'] = 'lib/htmLawed.php';

//htmLawed config
//
//htmLawed has a variety of different options that it supports.
//Below are the default options for minibord. Please read through the explanations of each one.
//
//'safe'=>1 is what mitigates XSS in HTML. It is highly recommended to keep this
//
//'balance'=>0 disables balancing HTML. Without it, <divs> stretched between postheader and postfooter wouldn't work (since those are processed independently)
//
//'make_tag_strict'=>0 allows deprecated HTML elements. You can remove this if you want
//
//'style_pass'=>1 disables sanitizing inline styles. While I cannot find any information about true XSS through inline styles on modern browsers, 
//		there are some odd (and possibly no longer relevant [citation needed]) browser-specific extensions that may make this possible. 
//		Leave this in at your own risk, but removing it breaks URLs in inline styles. There are, however, CSS exploits that allow a CSRF token to 
//		be leaked, but minibord does not use them (it should and might in the future, however)
//
//'css_expression'=>1 disables some odd old IE-specific thing. Included just incase 'style_pass'=>1 disables this
//
$options['htmLawedconfig'] = array('safe'=>1, 'balance'=>0, 'make_tag_strict'=>0, 'style_pass'=>1, 'css_expression'=>1);

//Lets include our functions
require('corefiles/functions.php');

//Verify the user is logged in
require('corefiles/auth.php');

//Now lets include our script that generates the theme
require('corefiles/layoutgen.php');

?>
