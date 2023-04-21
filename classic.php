<?php
//print site header
function printheader()
{
	global $user;
	global $boardname;
	global $themesettings;
	global $defaultbanner;
	global $sql;
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
	

	//Generate the headlinks
	$headlinkcategories=mysqli_query($sql, "SELECT * FROM `headlinks_categories` ORDER BY `displayorder`");
	$links="";
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
					if($firstlink==true)
						$links.="<tr><td $themesettings[tdHeaderLinks1Attributes] class=\"tdHeaderLinks1\">";
					
					if($firstlink==false)
						$links.=" ";
					else if(!empty($user) && basename($activefile)=="index.php")
						$links.="<a href=index.php?action=markallforumsread>mark all forums read</a> ";
					else if(!empty($user) && basename($activefile)=="forum.php")
					{
						$forumid=intval($_GET['id']);
						$doesforumexist=mysqli_fetch_array(mysqli_query($sql, "SELECT COUNT(*) FROM `forums` WHERE `id` = $forumid"));
									
						if($doesforumexist[0])
							$links.="<a href=\"index.php?action=markforumread&id=$forumid\">mark forum read</a> ";
			
					}
					$links.="<a href=$headlink[url]>".strtolower($headlink['name'])."</a>";
				}
				else if($currentcat>=2)
				{

					
					if($firstlink==false)
						$links.=" ";
					else
						$links.="<tr><td $themesettings[tdHeaderLinks1Attributes] class=\"tdHeaderLinks2\">";
					$links.="<a href=$headlink[url]>".strtolower($headlink['name'])."</a>";


				}				
				$firstlink=false;
			}
			
		}
		if($currentcat==1 && !empty($user))
			$links.=" - logged in as ".getusername($user['id']);	

		$links.="</td></tr>";
		$currentcat++;
		
	}

	//Print the header
	
	print  "<head>$themesettings[styletag]</head><body $themesettings[bodyAttributes] class=\"body\">

		<table border=0 width=800 align=left height=100% valign=top><tr><td width=100% height=100% align=left valign=top>
		<div class=\"header\">
		<table $themesettings[tableHeaderAttributes] class=\"tableHeader\">
		<tr><td colspan=3 $themesettings[tdHeaderBannerAttributes] class=\"tdHeaderBanner\">$bannerimage</td></tr>
		
		$links
				
		</table>
		</div><br>";

}

?>