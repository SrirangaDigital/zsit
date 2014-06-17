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
			<div class="archive_title">Bulletin</div>
			<ul class="menu">
				<li><a href="volumes.php" class="active">Volumes</a></li>
				<li><a href="articles.php">Articles</a></li>
				<li><a href="authors.php">Authors</a></li>
				<li class="gap_below"><a href="../search.php">Search</a></li>
				<li><a title="Click to download DjVu plugin" href="http://www.caminova.net/en/downloads/download.aspx?id=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder_volume">
<?php

include("connect.php");
require_once("../common.php");

$volume=$_GET['vol'];
$year=$_GET['year'];

if(!(isValidVolume($volume) && isValidYear($year)))
{
	echo "Invalid URL";
	
	echo "</div></div>";
	include("include_footer.php");
	echo "<div class=\"clearfix\"></div></div>";
	include("include_footer_out.php");
	echo "</body></html>";
	exit(1);
}

$db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
$rs = mysql_select_db($database,$db) or die("No Database");

echo "<div class=\"page_title\"><span class=\"motif bul_motif\"></span>Volume&nbsp;".intval($volume)."&nbsp;(".$year.") <span class=\"it\">(Bulletin)</span></div>";
?>

			<div class="col1">
				<ul>

<?php

$row_count = 4;
$month_name = array("0"=>"","1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

$query = "select distinct part from article_bulletin where volume='$volume' order by part";
$result = mysql_query($query);

$num_rows = mysql_num_rows($result);

$count = 0;
$col = 1;

if($num_rows)
{
	for($i=1;$i<=$num_rows;$i++)
	{
		$row=mysql_fetch_assoc($result);
		$part=$row['part'];
		
		$query11 = "select min(page) as minpage from article_bulletin where volume='$volume' and part='$part'";
		$result11 = mysql_query($query11);
		$num_rows11 = mysql_num_rows($result11);
		if($num_rows11)
		{
			$row11=mysql_fetch_assoc($result11);
			$page_start = $row11['minpage'];
			$page_start = intval($page_start);
		}

		$query12 = "select max(page_end) as maxpage from article_bulletin where volume='$volume' and part='$part'";
		$result12 = mysql_query($query12);
		$num_rows12 = mysql_num_rows($result12);
		if($num_rows12)
		{
			$row12=mysql_fetch_assoc($result12);
			$page_end = $row12['maxpage'];
			$page_end = intval($page_end);
		}



		$query1 = "select distinct month from article_bulletin where volume='$volume' and part='$part' order by month";
		$result1 = mysql_query($query1);
		$num_rows1 = mysql_num_rows($result1);
		if($num_rows1)
		{
			$row1=mysql_fetch_assoc($result1);
			$month = $row1['month'];

			$count++;
			if($count > $row_count)
			{
				$col++;
				echo "</div>\n
				<div class=\"col$col\">";
				$count = 1;
			}
			echo "<li class=\"li_below\"><span class=\"yearspan\"><a href=\"toc.php?vol=$volume&amp;part=$part\">part&nbsp;".$part;
			if(intval($month) != 0)
			{
				echo "&nbsp;(".$month_name{intval($month)}.")";
			}
			echo "<br />pp. $page_start-$page_end</a></span></li>";
		}
	}
}

?>
				</ul>
			</div>
		</div>
	</div>
<?php include("include_footer.php");?>
	<div class="clearfix"></div>
</div>
<?php include("include_footer_out.php");?>
</body>

</html>

