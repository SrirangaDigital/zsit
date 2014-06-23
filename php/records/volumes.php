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
			<div class="archive_title">Records</div>
			<ul class="menu">
				<li><a href="volumes.php" class="active">Volumes</a></li>
				<li><a href="articles.php">Articles</a></li>
				<li><a href="authors.php">Authors</a></li>
				<li><a href="features.php">Categories</a></li>
				<li class="gap_below"><a href="../search.php">Search</a></li>
				<li><a title="Click to download DjVu plugin" href="http://www.caminova.net/en/downloads/download.aspx?id=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder_volume">
			<div class="page_title"><span class="motif rec_motif"></span>Volumes <span class="it">(Records)</span></div>
			<div class="col1"><ul>
<?php

include("connect.php");

$db = new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0){
    die('Not connected to database [' . $db->connect_error . ']');
}

//~ $db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
//~ $rs = mysql_select_db($database,$db) or die("No Database");

$row_count = 30;

$query = "select distinct volume from article_records order by volume";

$result = $db->query($query); 
$num_rows = $result->num_rows;

//~ $result = mysql_query($query);
//~ $num_rows = mysql_num_rows($result);

$count = 0;
$col = 1;

if($num_rows)
{
	for($i=1;$i<=$num_rows;$i++)
	{
		//~ $row=mysql_fetch_assoc($result);
		$row = $result->fetch_assoc();
		$volume=$row['volume'];

		$query1 = "select distinct year from article_records where volume='$volume'";
		
		//~ $result1 = mysql_query($query1);
		//~ $num_rows1 = mysql_num_rows($result1);
		$result1 = $db->query($query1); 
		$num_rows1 = $result1->num_rows;
		
		if($num_rows1)
		{
			for($i1=1;$i1<=$num_rows1;$i1++)
			{
				//~ $row1=mysql_fetch_assoc($result1);
				$row1 = $result1->fetch_assoc();
				
				if($i1==1)
				{
					$year=$row1['year'];
				}
				else if($i1==2)
				{
					$year2 = $row1['year'];
					$year21 = preg_split('//',$year2);
					$year=$year."-".$year21[3].$year21[4];
				}
			}
			$count++;
			$volume_int = intval($volume);
			if($count > $row_count)
			{
				$col++;
				echo "</ul></div>\n
				<div class=\"col$col\"><ul>";
				$count = 1;
			}
			echo "<li><span class=\"yearspan\"><a href=\"part.php?vol=$volume&amp;year=$year\">Volume $volume_int ($year)</a></span></li>";
		}
		$result1->free();
	}
}

$result->free();
$db->close();

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

