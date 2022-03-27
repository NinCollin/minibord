<?php

//Connect to mysql server
$sql=mysqli_connect( 'server', 'user', 'password', 'database') or die('Couldn\'t connect to database!');

//used for rss lol
$siteroot="http://board.rainynight.city/";

//Name of board
$boardname="minibord";

//Default theme id
$defaultThemeID=5;

//Lets include our functions
require('functions.php');

//Now lets include our script that generates the theme
require('layoutgen.php');

?>
