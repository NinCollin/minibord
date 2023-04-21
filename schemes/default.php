<?php
//print site header
function printheader()
{
	global $sql;
	global $user;
	global $boardname;
	global $themesettings;
	global $defaultbanner;
	global $activefile;


	//If our theme wants to use a custom image banner, and the banner url isn't empty, show image banner
	if($themesettings['useCustomImageBanner'] && !empty($themesettings['banner']))
	{
		$bannerimage = "<img src=$themesettings[banner]>";
	}
	//Otherwise, just use default banner
	else
	{
		$bannerimage = "<img src=$defaultbanner>";
	}
	
	$headlinkcategories=mysqli_query($sql, "SELECT * FROM `headlinks_categories` ORDER BY `displayorder`");
	
	$links1="";
	$links2="";
	$links3="";
	$currentcat=1;
	while($headlinkcategory=mysqli_fetch_array($headlinkcategories))
	{
		$headlinks=mysqli_query($sql, "SELECT * FROM `headlinks` WHERE `catid` = $headlinkcategory[id] ORDER BY `displayorder`");
		
		$firstlink=true;
		while($headlink=mysqli_fetch_array($headlinks))
		{
			if($headlink['restricted'] == false && (!empty($user) && $headlink['showwhenloggedin'] == true) || (empty($user) && $headlink['showwhenloggedout'] == true))
			{
				
				if($currentcat==1)
				{
				
					if($firstlink==false)
						$links1.=" | ";
					else if(!empty($user) && basename($activefile)=="index.php")
						$links1.="<a href=index.php?action=markallforumsread>Mark All Forums Read</a> | ";
					else if(!empty($user) && basename($activefile)=="forum.php")
					{
						$forumid=intval($_GET['id']);
						$doesforumexist=mysqli_fetch_array(mysqli_query($sql, "SELECT COUNT(*) FROM `forums` WHERE `id` = $forumid"));
									
						if($doesforumexist[0])
							$links1.="<a href=\"index.php?action=markforumread&id=$forumid\">Mark Forum Read</a> | ";
			
					}
					$links1.="<a href=$headlink[url]>$headlink[name]</a>";
				}
				else if($currentcat==2)
				{
					
					if($firstlink==false)
						$links2.=" | ";
					$links2.="<a href=$headlink[url]>$headlink[name]</a>";


				}
				else if($currentcat>=3)
				{
					
					if($firstlink==false)
						$links3.=" | ";
					$links3.="<a href=$headlink[url]>$headlink[name]</a>";


				}
				
				
				$firstlink=false;
			}
			
		}
		if($currentcat==1 && !empty($user))
			$links1.=" - logged in as ".getusername($user['id']);
		if($currentcat>=3)
			$links3.="<br>";	

		$currentcat++;
		
	}


	//get views
	$views=mysqli_fetch_row(mysqli_query($sql,"SELECT * FROM `config` WHERE `name` = 'views'"));

	//Print the header
	
	print  "<head>$themesettings[styletag]</head><body $themesettings[bodyAttributes] class=\"body\">


		<div class=\"header\">
		<table $themesettings[tableHeaderAttributes] class=\"tableHeader\">
		<tr><td colspan=3 $themesettings[tdHeaderBannerAttributes] class=\"tdHeaderBanner\">$bannerimage</td></tr>
		
		<tr><td rowspan=2 $themesettings[tdHeaderViewCounterAttributes] class=\"tdHeaderViewCounter\" width=150><div class=\"smalltext\">Views: $views[1]</div></td>
			<td $themesettings[tdHeaderLinks1Attributes] class=\"tdHeaderLinks1\">$links1</td>
			<td rowspan=2 $themesettings[tdHeaderTimeAttributes] class=\"tdHeaderTime\" width=150><div class=\"smalltext\">".date("Y-m-d H:i:s", time())."</div></td></tr>
		
		<tr><td $themesettings[tdHeaderLinks2Attributes] class=\"tdHeaderLinks2\">$links2</td></tr>";

		if(!empty($links3)) print "<tr><td colspan=3 $themesettings[tdHeaderSpacerAttributes] class=\"tdHeaderLinks2\">$links3</td></tr>";
		
		print "<tr><td colspan=3 $themesettings[tdHeaderSpacerAttributes] class=\"tdHeaderSpacer\"></td></tr>

		</table>
		</div><br>";

}
?>