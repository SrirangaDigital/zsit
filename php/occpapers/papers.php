<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India</title>
<link href="../style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="page">
	<div class="header">
		<div class="zsi_logo"><img src="../images/logo.png" alt="ZSI Logo" /></div>
		<div class="gov_logo"><img src="../images/gov_logo.png" alt="Government of India Logo" /></div>
		<div class="title">
			<p class="eng">
				<span class="big">भारत सरकार</span><br />
				पर्यावरण एवं वन मंत्रालय<br />
				<span class="big">Government of India</span><br />
				Ministry of Environment and Forests
			</p>
			<div class="full">
				<p class="small">भारतीय प्राणी सर्वेक्षण</p>
				<p class="vbig">Zoological Survey of India</p>
			</div>
		</div>
<?php include("include_nav.php");?>
	</div>
	<div class="mainpage">
		<div class="nav">
			<div class="archive_title">Occasional Papers</div>
			<ul class="menu">
				<li><a class="active" href="papers.php">Papers</a></li>
				<li><a href="authors.php">Authors</a></li>
				<li class="gap_below"><a href="../search.php">Search</a></li>
				<li><a title="Click to download DjVu plugin" href="http://www.caminova.net/en/downloads/download.aspx?id=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder">
			<form method="post" action="occ_goto.php">
				<div class="goto">Go to paper no. <input type="text" class="goto_num" name="goto_num" maxlength="3"/><input class="goto_search" name="goto_submit" type="submit" value="&gt;&gt;"/></div>
			</form>
			<div class="page_title"><span class="motif occ_motif"></span>Occasional Papers</div>
				<ul class="decimal">
<?php

include("connect.php");

$db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
$rs = mysql_select_db($database,$db) or die("No Database");

$query = "select * from article_occpapers order by volume";
$result = mysql_query($query);

$num_rows = mysql_num_rows($result);

if($num_rows)
{
	for($i=1;$i<=$num_rows;$i++)
	{
		$row=mysql_fetch_assoc($result);

		$titleid=$row['titleid'];
		$title=$row['title'];
		$featid=$row['featid'];
		$page=$row['page'];
		$authid=$row['authid'];
		$paper=$row['volume'];
		$year=$row['year'];
		$month=$row['month'];
		
		$title1=addslashes($title);
		
		echo "<li id=\"p$paper\">";
		echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\">$title</a></span>";
		echo "<span class=\"titlespan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"yearspan\">$year</span>";
		
		if($authid != 0)
		{

			echo "<br />&mdash;";
			$aut = preg_split('/;/',$authid);

			$fl = 0;
			foreach ($aut as $aid)
			{
				$query2 = "select * from author where authid=$aid";
				$result2 = mysql_query($query2);

				$num_rows2 = mysql_num_rows($result2);

				if($num_rows2)
				{
					$row2=mysql_fetch_assoc($result2);

					$authorname=$row2['authorname'];
					

					if($fl == 0)
					{
						echo "<span class=\"authorspan\"><a href=\"../auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
						$fl = 1;
					}
					else
					{
						echo "<span class=\"titlespan\">;&nbsp;</span><span class=\"authorspan\"><a href=\"../auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
					}
				}

			}
		}
		echo "<br /><span class=\"downloadspan\"><a href=\"../../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\" target=\"_blank\">View article</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (DjVu)</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (PDF)</a></span>";

		echo "</li>\n";
	}
}

?>
				</ul>
			</div>
	</div>
<?php include("include_footer.php");?>
	<div class="clearfix"></div>
</div>
<?php include("include_footer_out.php");?>
</body>

</html>

