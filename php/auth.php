<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India</title>
<link href="style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="page">
	<div class="header">
		<div class="zsi_logo"><img src="images/logo.png" alt="ZSI Logo" /></div>
		<div class="gov_logo"><img src="images/gov_logo.png" alt="Government of India Logo" /></div>
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
			<ul class="menu">
				<li class="gap_below"><a href="search.php">Advanced Search</a></li>
				<li><a href="records/volumes.php">Records</a></li>
				<li><a href="memoirs/volumes.php">Memoirs</a></li>
				<li><a href="occpapers/papers.php">Occasional Papers</a></li>
				<li><a href="fbi_books_list.php">Fauna of<br />British India</a></li>
				<li><a href="fi_books_list.php">Fauna of<br />India</a></li>
				<li><a href="sfs_books_list.php">State Fauna Series</a></li>
				<li><a href="cas_books_list.php">Conservation Area Series</a></li>
				<li><a href="ess_books_list.php">Ecosystem Series</a></li>
				<li><a href="hpg_books_list.php">Handbook and<br />Pictorial Guides</a></li>
				<li><a href="spb_books_list.php">Special<br />Publications</a></li>
				<li><a href="sse_books_list.php">Status Survey of Endangered Species</a></li>
				<li><a href="tcm_books_list.php">Technical<br />Monographs</a></li>
				<li><a href="zlg_books_list.php">Zoologiana</a></li>
				<li class="gap_below"><a href="bulletin/volumes.php">Bulletin</a></li>
				<li><a title="Click to download DjVu plugin" href="http://www.caminova.net/en/downloads/download.aspx?id=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder">
<?php

include("bulletin/connect.php");
require_once("common.php");

$authid=$_GET['authid'];
$authorname=$_GET['author'];

$authorname = entityReferenceReplace($authorname);

if(!(isValidAuthid($authid) && isValidAuthor($authorname)))
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

$month_name = array("0"=>"","1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

echo "<div class=\"page_title\">Bibliography of $authorname</div>";
echo "<ul>";

$query = "(select 'type', titleid, title, page from article_records where authid like '%$authid%') 
UNION ALL (select 'type', titleid, title, page from article_memoirs where authid like '%$authid%') 
UNION ALL (select 'type', titleid, title, page from article_occpapers where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from fbi_books_list where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from sfs_book_toc where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from cas_book_toc where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from ess_book_toc where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from hpg_books_list where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from spb_book_toc where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from sse_books_list where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from tcm_books_list where authid like '%$authid%') 
UNION ALL (select type, book_id, title, page from zlg_book_toc where authid like '%$authid%')
UNION ALL (select 'type', titleid, title, page from article_bulletin where authid like '%$authid%')";

// echo $query;

$result = mysql_query($query);

$num_rows = mysql_num_rows($result);

if($num_rows)
{
for($i=1;$i<=$num_rows;$i++)
	{
		$row=mysql_fetch_assoc($result);

		$type=$row['type'];
		$book_id=$row['titleid'];
		$title=$row['title'];
		$page=$row['page'];
		
		$title = preg_replace('/!!(.*)!!/', "<i>$1</i>", $title);
		$title = preg_replace('/!/', "", $title);
		
		if(($type == "fbi") || ($type == "fi") || ($type == "hpg") || ($type == "sse") || ($type == "tcm"))
		{
			if($type == "fi")
			{
				$query_aux = "select * from fbi_books_list where book_id=$book_id and type='".$type."'";
			}
			else
			{
				$query_aux = "select * from ".$type."_books_list where book_id=$book_id and type='".$type."'";
			}
			
			$result_aux = mysql_query($query_aux);
			$num_rows_aux = mysql_num_rows($result_aux);
			$row_aux=mysql_fetch_assoc($result_aux);

			$authid=$row_aux['authid'];
			$authorname=$row_aux['authorname'];
			$type=$row_aux['type'];
			$page=$row_aux['page'];
			
			$page_end=$row_aux['page_end'];
			$edition=$row_aux['edition'];
			$volume=$row_aux['volume'];
			$part=$row_aux['part'];
			$year=$row_aux['year'];
			$month=$row_aux['month'];
			$book_id=$row_aux['book_id'];
			
			$book_info = '';

			if($type == 'fbi')
			{
				$book_info = $book_info . "Fauna of British India ";	
			}
			elseif($type == 'fi')
			{
				$book_info = $book_info . "Fauna of India ";	
			}
			elseif($type == 'hpg')
			{
				$book_info = $book_info . "Handbook and Pictorial Guides ";	
			}
			elseif($type == 'sse')
			{
				$book_info = $book_info . "Status Survey of Endangered Species ";	
			}
			elseif($type == 'tcm')
			{
				$book_info = $book_info . "Technical Monographs ";	
			}

			if($edition != '00')
			{
				if (intval($edition) == 1)
				{
					$book_info = $book_info . " | First Edition";
				}
				if (intval($edition) == 2)
				{
					$book_info = $book_info . " | Second Edition";
				}
			}
			if($volume != '00')
			{
				$book_info = $book_info . " | Volume " . intval($volume);
			}
			if($part != '00')
			{
				$book_info = $book_info . " | Part " . intval($part);
			}
			if(intval($page) != 0)
			{
				$book_info = $book_info . " | pp " . intval($page) . " - " . intval($page_end);	
			}
			
			echo "<li><span class=\"motif ".$type."_motif\"></span>";
			echo "<span class=\"titlespan\"><a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($title) . "\">$title</a></span>";
			echo "<br /><span class=\"bookspan\">$book_info</span>";
			echo "<br /><span class=\"downloadspan\"><a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($title) . "\">View TOC</a>&nbsp;|&nbsp;<a target=\"_blank\" href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=1&amp;zoom=page\">Read Book</a>&nbsp;|&nbsp;<a href=\"\" target=\"_blank\">Download Book (DjVu)</a>&nbsp;|&nbsp;<a href=\"\" target=\"_blank\">Download Book (PDF)</a></span>";
			echo "</li>\n";
		}
		elseif(($type == "sfs") || ($type == "cas") || ($type == "ess") || ($type == "spb") || ($type == "zlg"))
		{
			$book_info = "";
			
			$query_aux = "select * from ".$type."_books_list where book_id=$book_id and type='".$type."'";
			$result_aux = mysql_query($query_aux);
			$num_rows_aux = mysql_num_rows($result_aux);
			$row_aux=mysql_fetch_assoc($result_aux);

			
			$authid=$row_aux['authid'];
			$authorname=$row_aux['authorname'];
			
			$btitle = $row_aux['title'];
			$slno = $row_aux['slno'];
			$edition = $row_aux['edition'];
			$volume = $row_aux['volume'];
			$part = $row_aux['part'];
			$dpage = $row_aux['page'];
			$dpage_end = $row_aux['page_end'];
			$month = $row_aux['month'];
			$year = $row_aux['year'];

			if($type == 'sfs')
			{
				$stitle = "State Fauna Series ";	
			}
			elseif($type == 'cas')
			{
				$stitle = "Conservation Area Series ";	
			}
			elseif($type == 'ess')
			{
				$stitle = "Ecosystem Series ";	
			}
			elseif($type == 'spb')
			{
				$stitle = "Special Publications ";	
			}
			elseif($type == 'zlg')
			{
				$stitle = "Zoologiana ";	
			}
			
			if($btitle != '')
			{
				$book_info = $book_info . " | " . $btitle;
			}
			if($edition != '00')
			{
				$book_info = $book_info . " | Edition " . intval($edition);
			}
			if($volume != '00')
			{
				$book_info = $book_info . " | Volume " . intval($volume);
			}
			if($part != '00')
			{
				$book_info = $book_info . " | Part " . intval($part);
			}
			if(intval($dpage) != 0)
			{
				$book_info = $book_info . " | pp " . intval($dpage) . " - " . intval($dpage_end);	
			}
			if(intval($month) != 0)
			{
				$book_info = $book_info . " | " . $month_name{intval($month)} . " " . intval($year);	
			}

			$book_info = preg_replace("/^ /", "", $book_info);
			$book_info = preg_replace("/^\|/", "", $book_info);
			$book_info = preg_replace("/^ /", "", $book_info);

			echo "<li><span class=\"motif ".$type."_motif\"></span>";
			echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
			echo "<span class=\"titlespan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"yearspan\">$stitle</span><br /><span class=\"bookspan\">$book_info</span>";
			echo "<br /><span class=\"downloadspan\"><a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($btitle) . "\">View TOC</a>&nbsp;|&nbsp;<a href=\"\" target=\"_blank\">Download Article (DjVu)</a>&nbsp;|&nbsp;<a href=\"\" target=\"_blank\">Download Article (PDF)</a></span>";
			echo "</li>\n";
		}
		elseif(($type == "type"))
		{
			$titleid = $book_id;

			if(preg_match("/^rec/", $book_id))
			{
				$type = "records";
				$dtype = "Records";
			}
			elseif(preg_match("/^mem/", $book_id))
			{
				$type = "memoirs";
				$dtype = "Memoirs";
			}
			elseif(preg_match("/^occ/", $book_id))
			{
				$type = "occpapers";
				$dtype = "Occasional Papers";
			}
			elseif(preg_match("/^bul/", $book_id))
			{
				$type = "bulletin";
				$dtype = "Bulletin";
			}
						
			$query_aux = "select * from article_".$type." where titleid='$titleid'";
			$result_aux = mysql_query($query_aux);
			$row_aux=mysql_fetch_assoc($result_aux);

			$titleid=$row_aux['titleid'];
			$title=$row_aux['title'];
			$featid=$row_aux['featid'];
			$page=$row_aux['page'];
			$authid=$row_aux['authid'];
			$volume=$row_aux['volume'];
			$part=$row_aux['part'];
			$year=$row_aux['year'];
			$month=$row_aux['month'];
			
			$paper = $volume;	
			$title1=addslashes($title);
					
			$query3 = "select feat_name from feature_".$type." where featid='$featid'";
			$result3 = mysql_query($query3);		
			$row3=mysql_fetch_assoc($result3);
			$feature=$row3['feat_name'];
					
			if(($type == "records") || ($type == "memoirs") || ($type == "bulletin"))
			{
				echo "<li><span class=\"motif ".$type."_motif\"></span>";
				echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
				echo "<br /><span class=\"featurespan\">$dtype&nbsp;&nbsp;|&nbsp;&nbsp;
					<a href=\"$type/toc.php?vol=$volume&amp;part=$part\">Vol.&nbsp;".intval($volume)."&nbsp;(p. ".$part.")&nbsp;;&nbsp;" . $month_name{intval($month)}."&nbsp;".$year."</a>
				</span>";
				if($feature != "")
				{
					echo "<span class=\"titlespan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"featurespan\"><a href=\"$type/feat.php?feature=$feature&amp;featid=$featid\">$feature</a></span>";
				}
				
				echo "<br /><span class=\"downloadspan\"><a href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">View article</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (DjVu)</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (PDF)</a></span>";
				echo "</li>\n";
			}
			elseif($type == "occpapers")
			{
				echo "<li><span class=\"motif ".$type."_motif\"></span>";
				echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\">$title</a></span>";
				echo "<span class=\"featurespan\"><br />Occ. paper no.&nbsp;".intval($paper)."&nbsp;($year)</span>";
				echo "<br /><span class=\"downloadspan\"><a href=\"../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\" target=\"_blank\">View article</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (DjVu)</a>&nbsp;|&nbsp;<a href=\"#\" target=\"_blank\">Download article (PDF)</a></span>";
				echo "</li>\n";
			}
		}
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

