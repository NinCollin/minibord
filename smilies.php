<?php

$run=1;
$activefile=__FILE__;

//Require the core file for logins and functions and stuff
require ('corefiles/core.php');


//print our header
printheader();

print "<head>$themesettings[styletag]</head><body $themesettings[bodyAttributes] class=\"body\">";


$smilies=mysqli_query($sql, "SELECT * FROM `smilies` ORDER BY `displayorder`");

$numsmilies=mysqli_num_rows($smilies);


$found=false;
$numrowscols=1;

for($i = 1; $found == false; $i++)
{	

	if(pow($i, 2) == $numsmilies)
	{	
		$found=true;
		$numrowscols=$i;

	}

	if(($numsmilies % pow($i, 2)) == $numsmilies)
	{
		$found=true;
		$numrowscols=$i;
	}
}

print "<!--<table width=100% height=100%><tr><td align=center valign=middle> --><center>Smilies<table $themesettings[tableAttributes] class=\"table\" style=\"width: 500px;\">";

$col=1;
$row=1;
$startrow=true;
while($smilie=mysqli_fetch_array($smilies))
{
	if($col==1)
	{
		print "<tr>";

	}
	print"<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=50><img src=\"$smilie[image]\"></td><td  $themesettings[tdStyle2Attributes] class=\"tdStyle2\" width=50>$smilie[code]</td>";
	
	if($col == $numrowscols)
	{

		print "</tr>";
		$col=1;
		$row++;
		$startrow=true;
	}
	else
	{
		$col++;
		$startrow=false;
	}
}

/*
for($r = $row; $r<=$numrowscols; $r++)
{
	if($startrow==true)
	{
		print"<tr>";

	}
*/
if($startrow==false)
{
	for($c = $col; $c<=$numrowscols; $c++)
	{
		print"<td  $themesettings[tdStyle1Attributes] class=\"tdStyle1\" width=50>&nbsp;</td><td  $themesettings[tdStyle2Attributes] class=\"tdStyle2\" width=50>&nbsp;</td>";

	}
}
/*
	$col=1;

	print "<tr>";
}
*/
print "</table><!--</td></tr></table> --></center><br>";




printfooter();


?>