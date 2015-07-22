<?php
	// If nothing is GETed, redirect to search page
	if(empty($_GET['author']) && empty($_GET['title']) && empty($_GET['text'])) {
		header('Location: search.php');
		exit(1);
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India | Digital archives of their Publications</title>
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
				<li><a title="Click to download DjVu plugin" href="https://www.cuminas.jp/en/downloads/download_en/?pid=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder">
<?php

include("cas/connect.php");
require_once("common.php");

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

if(isset($_GET['check']))
{
	$check=$_GET['check'];
	if(!(isValidCheck($check)))
	{
		echo "Invalid URL";
		
		echo "</div></div>";
		include("include_footer.php");
		echo "<div class=\"clearfix\"></div></div>";
		include("include_footer_out.php");
		echo "</body></html>";
		exit(1);
	}

	if(isset($_GET['author'])){$author = $_GET['author'];}else{$author = '';}
	if(isset($_GET['text'])){$text = $_GET['text'];}else{$text = '';}
	if(isset($_GET['title'])){$title = $_GET['title'];}else{$title = '';}
	if(isset($_GET['searchform'])){$searchform = $_GET['searchform'];}else{$searchform = '';}
	if(isset($_GET['resetform'])){$resetform = $_GET['resetform'];}else{$resetform = '';}
	
	$text = entityReferenceReplace($text);
	$author = entityReferenceReplace($author);
	$title = entityReferenceReplace($title);
	$searchform = entityReferenceReplace($searchform);
	$resetform = entityReferenceReplace($resetform);

	$author = preg_replace("/[\t]+/", " ", $author);
	$author = preg_replace("/[ ]+/", " ", $author);
	$author = preg_replace("/^ /", "", $author);

	$title = preg_replace("/[\t]+/", " ", $title);
	$title = preg_replace("/[ ]+/", " ", $title);
	$title = preg_replace("/^ /", "", $title);

	$text = preg_replace("/[\t]+/", " ", $text);
	$text = preg_replace("/[ ]+/", " ", $text);
	$text = preg_replace("/^ /", "", $text);

	$text2 = $text;
	$text2d = $text;
	$text2d = preg_replace("/ /", "|", $text2d);

	if($title=='')
	{
		$title='[a-z]*';
	}
	if($author=='')
	{
		$author='[a-z]*';
	}

	$cfl = 0;
	
	$author = addslashes($author);
	$title = addslashes($title);
	
	if($text=='')
	{
		$iquery{"rec"}="(SELECT titleid, title, authid, authorname, page, 'type', featid from article_records WHERE authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"mem"}="(SELECT titleid, title, authid, authorname, page, 'type', featid from article_memoirs WHERE authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"occ"}="(SELECT titleid, title, authid, authorname, page, 'type', featid from article_occpapers WHERE authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"fbi"}="(SELECT book_id, title, authid, authorname, page, type, slno from fbi_books_list WHERE type='fbi' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"fi"}="(SELECT book_id, title, authid, authorname, page, type, slno from fbi_books_list WHERE type='fi' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"sfs"}="(SELECT book_id, title, authid, authorname, page, type, slno from sfs_book_toc WHERE type='sfs' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"cas"}="(SELECT book_id, title, authid, authorname, page, type, slno from cas_book_toc WHERE type='cas' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"ess"}="(SELECT book_id, title, authid, authorname, page, type, slno from ess_book_toc WHERE type='ess' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"hpg"}="(SELECT book_id, title, authid, authorname, page, type, slno from hpg_books_list WHERE type='hpg' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"spb"}="(SELECT book_id, title, authid, authorname, page, type, slno from spb_book_toc WHERE type='spb' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"sse"}="(SELECT book_id, title, authid, authorname, page, type, slno from sse_books_list WHERE type='sse' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"tcm"}="(SELECT book_id, title, authid, authorname, page, type, slno from tcm_books_list WHERE type='tcm' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"zlg"}="(SELECT book_id, title, authid, authorname, page, type, slno from zlg_book_toc WHERE type='zlg' and authorname REGEXP '$author' and title REGEXP '$title')";
		$iquery{"bul"}="(SELECT titleid, title, authid, authorname, page, 'type', featid from article_bulletin WHERE authorname REGEXP '$author' and title REGEXP '$title')";
	
		$query = '';
		$mtf = '';
		
		for($ic=0;$ic<sizeof($check);$ic++)
		{
			if($check[$ic] != '')
			{
				$mtf = $mtf . "<span class=\"motif ".$check[$ic]."_motif nrmargin\"></span>\n";
				$query = $query . " UNION ALL " . $iquery{$check[$ic]};
			}
		}
		$query = preg_replace("/^ UNION ALL /", "", $query);
	}
	elseif($text!='')
	{
		$text = trim($text);
		if(preg_match("/^\"/", $text))
		{
			$stext = preg_replace("/\"/", "", $text);
			$dtext = $stext;
			$stext = '"' . $stext . '"';
		}
		elseif(preg_match("/\+/", $text))
		{
			$stext = preg_replace("/\+/", " +", $text);
			$dtext = preg_replace("/\+/", "|", $text);
			$stext = '+' . $stext;
		}
		elseif(preg_match("/\|/", $text))
		{
			$stext = preg_replace("/\|/", " ", $text);
			$dtext = $text;
		}
		else
		{
			$stext = $text;
			$dtext = $stext = preg_replace("/ /", "|", $text);
		}
		
		$stext = addslashes($stext);
		
		$iquery{"rec"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT titleid, title, authid, authorname, page, 'type', featid, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_records WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"mem"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT titleid, title, authid, authorname, page, 'type', featid, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_memoirs WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"occ"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT titleid, title, authid, authorname, page, 'type', featid, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_occpapers WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"fbi"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_fbi WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"fi"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_fi WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"sfs"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_sfs WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"cas"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_cas WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";

		$iquery{"ess"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_ess WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
		
		$iquery{"hpg"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_hpg WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
		
		$iquery{"spb"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_spb WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
					
		$iquery{"sse"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_sse WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
					
		$iquery{"tcm"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_tcm WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
					
		$iquery{"zlg"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT book_id, title, authid, authorname, page, type, slno, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_zlg WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
						
		$iquery{"bul"}="(SELECT * FROM
							(SELECT * FROM
								(SELECT * FROM
									(SELECT titleid, title, authid, authorname, page, 'type', featid, cur_page, MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) AS relevance FROM searchtable_bulletin WHERE MATCH (text) AGAINST ('$stext' IN BOOLEAN MODE) ORDER BY relevance DESC)
								AS tb10 WHERE authorname REGEXP '$author')
							AS tb20 WHERE title REGEXP '$title')
						AS tb30 WHERE cur_page NOT REGEXP '[a-z]')";
				
		$query = '';
		$mtf = '';
		
		for($ic=0;$ic<sizeof($check);$ic++)
		{
			if($check[$ic] != '')
			{
				$mtf = $mtf . "<span class=\"motif ".$check[$ic]."_motif nrmargin\"></span>\n";
				$query = $query . " UNION ALL " . $iquery{$check[$ic]};
			}
		}
		$query = preg_replace("/^ UNION ALL /", "", $query);
	}
	
	//~ $result = mysql_query($query);
	//~ $num_results = mysql_num_rows($result);

	$result = $db->query($query); 
	$num_results = $result ? $result->num_rows : 0;

	if ($num_results > 0)
	{
		echo "<div class=\"count authorspan\">$num_results result(s)</div>";
	}
	echo "<div class=\"page_title\"><p style=\"float: right;\">$mtf</p><span>Search Results</span></div>";
	$titleid[0]=0;
	$count = 1;
	$id = "0";
	if($num_results > 0)
	{
		echo "<ul>";
		for($i=1;$i<=$num_results;$i++)
		{
			//~ $row1 = mysql_fetch_assoc($result);
			$row1 = $result->fetch_assoc();

			if(isset($row1['titleid']))
			{
				$book_id = $row1['titleid'];
			}
			else
			{
				$book_id = $row1['book_id'];
			}
			
			$title = $row1['title'];
			$authid = $row1['authid'];
			$authorname = $row1['authorname'];
			$page = $row1['page'];
			$type = $row1['type'];
			
			if(isset($row1['featid']))
			{
				$slno = $row1['featid'];
			}
			else
			{
				$slno = $row1['slno'];
			}			
			
			if($type == "type")
			{
				$slno = $book_id;
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
			}
			
			$title = preg_replace('/!!(.*)!!/', "<i>$1</i>", $title);
			$title = preg_replace('/!/', "", $title);
		
			if($text != '')
			{
				$cur_page = $row1['cur_page'];
			}
			
			$title1=addslashes($title);
			
			if ($id != $slno)
			{
				if($id == 0)
				{
					echo "<li><span class=\"motif ".$type."_motif\"></span>";
				}
				else
				{
					echo "</li>\n<li><span class=\"motif ".$type."_motif\"></span>";
				}
				
				if(($type == "fbi") || ($type == "fi") || ($type == "hpg") || ($type == "sse") || ($type == "tcm"))
				{
					$book_info = '';
					
					if($type == "fi")
					{
						$query_aux = "select * from fbi_books_list where book_id='$book_id' and type='$type'";
					}
					else
					{
						$query_aux = "select * from ".$type."_books_list where book_id='$book_id' and type='$type'";
					}
					
					//~ $result_aux = mysql_query($query_aux);
					//~ $num_rows_aux = mysql_num_rows($result_aux);
					
					$result_aux = $db->query($query_aux); 
					$num_rows_aux = $result_aux ? $result_aux->num_rows : 0;
					
					//~ $row_aux=mysql_fetch_assoc($result_aux);
					$row_aux = $result_aux->fetch_assoc();

					$page_end = $row_aux['page_end'];
					$edition = $row_aux['edition'];
					$volume = $row_aux['volume'];
					$part = $row_aux['part'];
					$year = $row_aux['year'];
					$month = $row_aux['month'];
					
					if($result_aux){$result_aux->free();}
					
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
					
					echo "<span class=\"titlespan\"><a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($title) . "\">$title</a></span>";
					echo "<br /><span class=\"bookspan\">$book_info</span>";
					print_author($authid,$db);
					
					echo "<br /><span class=\"downloadspan\">";
						echo "<a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($title) . "\">View TOC</a>&nbsp;|&nbsp;";

						$PDFUrl = '../PDFVolumes/' . $type . '/' . $book_id . '/index.pdf';
						if (file_exists($PDFUrl)) echo '<a target="_blank" href="' . $PDFUrl . '">Download Book (PDF)</a>&nbsp;|&nbsp;';

						echo "<a target=\"_blank\" href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=1&amp;zoom=page\">Read Book (DjVu)</a>";
					echo "</span>";

					$id = $slno;
					
					if($text != '')
					{
						echo "<br /><span class=\"authorspan\">result(s) found at page no(s). </span>";
						echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."</a> &nbsp;</span>";
						$id = $slno;
					}
				}
				elseif(($type == "sfs") || ($type == "cas") || ($type == "ess") || ($type == "spb") || ($type == "zlg"))
				{
					$book_info = "";
			
					$query_aux = "select * from ".$type."_books_list where book_id=$book_id and type='".$type."'";

					//~ $result_aux = mysql_query($query_aux);
					//~ $num_rows_aux = mysql_num_rows($result_aux);

					$result_aux = $db->query($query_aux); 
					$num_rows_aux = $result_aux ? $result_aux->num_rows : 0;

					//~ $row_aux=mysql_fetch_assoc($result_aux);
					$row_aux = $result_aux->fetch_assoc();

					$btitle = $row_aux['title'];
					$slno = $row_aux['slno'];
					$edition = $row_aux['edition'];
					$volume = $row_aux['volume'];
					$part = $row_aux['part'];
					$dpage = $row_aux['page'];
					$dpage_end = $row_aux['page_end'];
					$month = $row_aux['month'];
					$year = $row_aux['year'];
					
					if($result_aux){$result_aux->free();}
							
					$btitle = preg_replace('/!!(.*)!!/', "<i>$1</i>", $btitle);
					$btitle = preg_replace('/!/', "", $btitle);
		
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

					if($page == '')
					{
						echo "<span class=\"titlespan\"><a href=\"#\">$title</a></span>";
					}
					else
					{
						echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
					}
					echo "<span class=\"titlespan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"yearspan\">$stitle</span><br /><span class=\"bookspan\">$book_info</span>";
					print_author($authid,$db);
					
					echo "<br /><span class=\"downloadspan\">";
						echo "<a href=\"".$type."/".$type."_books_toc.php?book_id=$book_id&amp;type=$type&amp;book_title=" . urlencode($btitle) . "\">View TOC</a>&nbsp;|&nbsp;";

						$PDFUrl = '../PDFVolumes/' . $type . '/' . $book_id . '/index.pdf';
						if (file_exists($PDFUrl)) echo '<a target="_blank" href="' . $PDFUrl . '">Download Book (PDF)</a>&nbsp;|&nbsp;';

						echo "<a href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">View Article (DjVu)</a>";
					echo "</span>";

					$id = $slno;
					
					if($text != '')
					{
						echo "<br /><span class=\"authorspan\">result(s) found at page no(s). </span>";
						echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."</a> &nbsp;</span>";
						$id = $slno;
					}
				}
				elseif(($type == "records") || ($type == "memoirs") || ($type == "occpapers") || ($type == "bulletin"))
				{
					$titleid = $book_id;
					
					$query_aux = "select * from article_".$type." where titleid='$titleid'";

					//~ $result_aux = mysql_query($query_aux);
					//~ $row_aux=mysql_fetch_assoc($result_aux);
					
					$result_aux = $db->query($query_aux); 
					$row_aux = $result_aux->fetch_assoc();

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
					
					$title = preg_replace('/!!(.*)!!/', "<i>$1</i>", $title);
					$title = preg_replace('/!/', "", $title);
					$title1=addslashes($title);

					if($result_aux){$result_aux->free();}

					$query3 = "select feat_name from feature_".$type." where featid='$featid'";
					
					//~ $result3 = mysql_query($query3);		
					//~ $row3=mysql_fetch_assoc($result3);

					$result3 = $db->query($query3); 
					$row3 = $result3->fetch_assoc();
					
					$feature=$row3['feat_name'];
					
					if($result3){$result3->free();}
					
					if(($type == "records") || ($type == "memoirs") || ($type == "bulletin"))
					{
					
						echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
						echo "<br /><span class=\"featurespan\">$dtype&nbsp;&nbsp;|&nbsp;&nbsp;
							<a href=\"$type/toc.php?vol=$volume&amp;part=$part\">Vol.&nbsp;".intval($volume)."&nbsp;(p. ".$part.")&nbsp;;&nbsp;" . $month_name{intval($month)}."&nbsp;".$year."</a>
						</span>";
						if($feature != "")
						{
							echo "<span class=\"yearspan\">&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class=\"featurespan\"><a href=\"$type/feat.php?feature=" . urlencode($feature) . "&amp;featid=$featid\">$feature</a></span>";
						}
					}
					elseif($type == "occpapers")
					{
						echo "<span class=\"titlespan\"><a target=\"_blank\" href=\"../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\">$title</a></span>";
						echo "<span class=\"featurespan\"><br />Occ. paper no.&nbsp;".intval($paper)."&nbsp;($year)</span>";
					}
					
					if($authid != 0)
					{

						echo "<br />&mdash;";
						$aut = preg_split('/;/',$authid);

						$fl = 0;
						foreach ($aut as $aid)
						{
							$query2 = "select * from author where authid=$aid";

							//~ $result2 = mysql_query($query2);
							//~ $num_rows2 = mysql_num_rows($result2);
							
							$result2 = $db->query($query2); 
							$num_rows2 = $result2 ? $result2->num_rows : 0;

							if($num_rows2 > 0)
							{
								//~ $row2=mysql_fetch_assoc($result2);
								$row2 = $result2->fetch_assoc();

								$authorname=$row2['authorname'];								

								if($fl == 0)
								{
									echo "<span class=\"authorspan\"><a href=\"auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
									$fl = 1;
								}
								else
								{
									echo "<span class=\"titlespan\">;&nbsp;</span><span class=\"authorspan\"><a href=\"auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
								}
							}
							if($result2){$result2->free();}

						}
					}
					
					if(($type == "records") || ($type == "memoirs") || ($type == "bulletin"))
					{
						echo "<br /><span class=\"downloadspan\">";
						$ArticlePDFUrl = '../PDFVolumes/' . $type . '/' . $volume . '/' . $part . '/' . $page . '-' . $row_aux['page_end'] . '.pdf';
						if (file_exists($ArticlePDFUrl)) echo '<a target="_blank" href="' . $ArticlePDFUrl . '">Download article (PDF)</a> &nbsp;|&nbsp; ';
						echo "<a href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">View article (DjVu)</a></span>";

					}
					elseif($type == "occpapers")
					{
						echo "<br /><span class=\"downloadspan\">";
						$ArticlePDFUrl = '../PDFVolumes/occpapers/' . $paper . '/index.pdf';
						if (file_exists($ArticlePDFUrl)) echo '<a target="_blank" href="' . $ArticlePDFUrl . '">Download article (PDF)</a> &nbsp;|&nbsp; ';
						echo "<a href=\"../Volumes/occpapers/$paper/index.djvu?djvuopts&amp;page=1&amp;zoom=page\" target=\"_blank\">View article (DjVu)</a></span>";
					}

					if($text != '')
					{
						echo "<br /><span class=\"authorspan\">result(s) found at page no(s). </span>";
						if(($type == "records") || ($type == "memoirs") || ($type == "bulletin"))
						{
							echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."&nbsp;</a></span>";
						}
						elseif($type == "occpapers")
						{
							echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$paper/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."&nbsp;</a></span>";
						}
						$id = $titleid;
					}
				}
			}
			else
			{
				if($text != '')
				{
					if(($type == "records") || ($type == "memoirs") || ($type == "bulletin"))
					{
						echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$volume/$part/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."</a> &nbsp;</span>";
						$id = $titleid;
					}
					elseif($type == "occpapers")
					{
						echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$paper/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."</a> &nbsp;</span>";
						$id = $titleid;
					}
					else
					{
						echo "<span class=\"titlespan\"><a href=\"../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$cur_page.djvu&amp;zoom=page&amp;find=$dtext/r\" target=\"_blank\">".intval($cur_page)."</a> &nbsp;</span>";
						$id = $slno;
					}
				}
			}
		}
		echo "</li></ul>";
	}
	else
	{
		echo"<span class=\"titlespan\">No results</span><br />";
		echo"<span class=\"authorspan\"><a href=\"search.php\">Go back and Search again</a></span>";
	}	
	if($result){$result->free();}
}
else
{
	echo"<span class=\"titlespan\">Please slect at least one publication</span><br />";
	echo"<span class=\"authorspan\"><a href=\"search.php\">Go back and Search again</a></span>";
}
$db->close();
?>
		
		</div>
	</div>
<?php include("include_footer.php");?>
	<div class="clearfix"></div>
</div>
<?php include("include_footer_out.php");?>
</body>

</html>
<?php

function print_author($authid,$db)
{
	if($authid != 0)
	{

		echo "<br />&mdash;";
		$aut = preg_split('/;/',$authid);

		$fl = 0;
		foreach ($aut as $aid)
		{
			$query2 = "select * from author where authid=$aid";

			//~ $result2 = mysql_query($query2);
			//~ $num_rows2 = mysql_num_rows($result2);
			
			$result2 = $db->query($query2); 
			$num_rows2 = $result2 ? $result2->num_rows : 0;

			if($num_rows2 > 0)
			{
				//~ $row2=mysql_fetch_assoc($result2);
				$row2 = $result2->fetch_assoc();

				$authorname=$row2['authorname'];

				if($fl == 0)
				{
					echo "<span class=\"authorspan\"><a href=\"auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
					$fl = 1;
				}
				else
				{
					echo "<span class=\"titlespan\">;&nbsp;</span><span class=\"authorspan\"><a href=\"auth.php?authid=$aid&amp;author=" . urlencode($authorname) . "\">$authorname</a></span>";
				}
			}
			if($result2){$result2->free();}
		}
	}
}

?>
