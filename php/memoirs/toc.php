<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India | Digital archives of their Publications</title>
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
				पर्यावरण, वन और जलवायु परिवर्तन मंत्रालय<br />
				<span class="big">Government of India</span><br />
				Ministry of Environment, Forest and<br />Climate Change
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
			<div class="archive_title">Memoirs</div>
			<ul class="menu">
				<li><a href="volumes.php" class="active">Volumes</a></li>
				<li><a href="articles.php">Articles</a></li>
				<li><a href="authors.php">Authors</a></li>
				<!-- <li><a href="features.php">Categories</a></li> -->
				<li class="gap_below"><a href="../search.php">Search</a></li>
				<li><a title="Click to download DjVu plugin" href="https://www.cuminas.jp/en/downloads/download_en/?pid=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder">
<?php

include("connect.php");
require_once("../common.php");

if(isset($_GET['vol'])){$volume = $_GET['vol'];}else{$volume = '';}
if(isset($_GET['part'])){$part = $_GET['part'];}else{$part = '';}

if(!(isValidVolume($volume) && isValidPart($part)))
{
	echo "Invalid URL";
	
	echo "</div></div>";
	include("include_footer.php");
	echo "<div class=\"clearfix\"></div></div>";
	include("include_footer_out.php");
	echo "</body></html>";
	exit(1);
}

$db = @new mysqli("$host", "$user", "$password", "$database", "$port");
if($db->connect_errno > 0)
{
	echo 'Not connected to the database [' . $db->connect_errno . ']';
	echo "</div></div>";
	include("include_footer.php");
	echo "<div class=\"clearfix\"></div></div>";
	include("include_footer_out.php");
	echo "</body></html>";
	exit(1);
}

//~ $db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
//~ $rs = mysql_select_db($database,$db) or die("No Database");

$month_name = array("0"=>"","1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

$query = "select distinct year,month from article_memoirs where volume='$volume' and part='$part'";

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

//~ $result = mysql_query($query);
//~ $num_rows = mysql_num_rows($result);

if($num_rows > 0)
{
	//~ $row=mysql_fetch_assoc($result);
	$row = $result->fetch_assoc();
	
	$month=$row['month'];
	$year=$row['year'];
	
	$dpart = preg_replace("/^0/", "", $part);
	$dpart = preg_replace("/\-0/", "-", $dpart);
	
	echo "<div class=\"page_title\"><span class=\"motif mem_motif\"></span>Volume&nbsp;".intval($volume)."&nbsp;- Part&nbsp;".$dpart."&nbsp;&nbsp;:&nbsp;&nbsp;".$month_name{intval($month)}."&nbsp;".$year." <span class=\"it\">(Memoirs)</span></div>";
	
	$PDFUrl = '../../PDFVolumes/memoirs/' . $volume . '/' . $part . '/index.pdf';
	if (file_exists($PDFUrl)) echo '<div class="page_other"><span class="downloadBook"><a target="_blank" href="' . $PDFUrl . '">Download entire issue (PDF)</a></span></div>';

	echo "<ul class=\"dot\">";
}
if($result){$result->free();}

$query1 = "select * from article_memoirs where volume='$volume' and part='$part' order by page";

$result1 = $db->query($query1); 
$num_rows1 = $result1 ? $result1->num_rows : 0;

//~ $result1 = mysql_query($query1);
//~ $num_rows1 = mysql_num_rows($result1);

if($num_rows1 > 0)
{
	for($i=1;$i<=$num_rows1;$i++)
	{
		//~ $row1=mysql_fetch_assoc($result1);
		$row1 = $result1->fetch_assoc();

		$titleid=$row1['titleid'];
		$title=$row1['title'];
		$featid=$row1['featid'];
		$page=$row1['page'];
		$authid=$row1['authid'];
		$volume=$row1['volume'];
		$part=$row1['part'];
		$year=$row1['year'];
		$month=$row1['month'];
		$plates=$row1['plates'];
		
		$title1=addslashes($title);
		
		$query3 = "select feat_name from feature_memoirs where featid='$featid'";
		
		//~ $result3 = mysql_query($query3);		
		$result3 = $db->query($query3); 
		
		//~ $row3=mysql_fetch_assoc($result3);
		$row3 = $result3->fetch_assoc();
		
		$feature=$row3['feat_name'];
		if($result3){$result3->free();}
		
		
		echo "<li>";
		echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../../Volumes/memoirs/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
		if($feature != "")
		{
			echo "<span class=\"titlespan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"featurespan\"><a href=\"feat.php?feature=" . urlencode($feature) . "&amp;featid=$featid\">$feature</a></span>";
		}
		
		if($authid != 0)
		{

			echo "<br />&mdash;";
			$aut = preg_split('/;/',$authid);

			$fl = 0;
			foreach ($aut as $aid)
			{
				$query2 = "select * from author where authid=$aid";

				$result2 = $db->query($query2); 
				$num_rows2 = $result2 ? $result2->num_rows : 0;

				//~ $result2 = mysql_query($query2);
				//~ $num_rows2 = mysql_num_rows($result2);

				if($num_rows2 > 0)
				{
					//~ $row2=mysql_fetch_assoc($result2);
					$row2 = $result2->fetch_assoc();

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
				if($result2){$result2->free();}
			}
		}
		echo "<br /><span class=\"downloadspan\">";
		$ArticlePDFUrl = '../../PDFVolumes/memoirs/' . $volume . '/' . $part . '/' . $page . '-' . $row1['page_end'] . '.pdf';
		if (file_exists($ArticlePDFUrl)) echo '<a target="_blank" href="' . $ArticlePDFUrl . '">Download article (PDF)</a> &nbsp;|&nbsp; ';
		echo "<a href=\"../../Volumes/memoirs/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">View article (DjVu)</a></span>";
		echo "</li>\n";
	}
}
else
{
	echo "No data in the database";
}

if($result1){$result1->free();}
$db->close();

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
