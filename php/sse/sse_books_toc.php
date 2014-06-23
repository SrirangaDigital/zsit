<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India</title>
<link href="../style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-2.0.0.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/treeview.js"></script>
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
			<div class="archive_title">Status Survey of<br />Endangered Species</div>
			<ul class="menu">
				<li><a class="active" href="../sse_books_list.php">Books</a></li>
				<li><a href="authors.php">Authors</a></li>
				<li class="gap_below"><a href="../search.php">Search</a></li>
				<li><a title="Click to download DjVu plugin" href="http://www.caminova.net/en/downloads/download.aspx?id=1" target="_blank">Get DjVu</a></li>
			</ul>
		</div>
		<div class="archive_holder">

<?php
include("connect.php");
require_once("../common.php");

$book_id = $_GET['book_id'];
$type = $_GET['type'];
$book_title = $_GET['book_title'];

$book_title = entityReferenceReplace($book_title);

if(!(isValidId($book_id) && isValidType($type) && isValidTitle($book_title)))
{
	echo "Invalid URL";
	
	echo "</div></div>";
	include("include_footer.php");
	echo "<div class=\"clearfix\"></div></div>";
	include("include_footer_out.php");
	echo "</body></html>";
	exit(1);
}

$db = new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0){
    die('Not connected to database [' . $db->connect_error . ']');
}

//~ $db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
//~ $rs = mysql_select_db($database,$db) or die("No Database");

$query = "select * from sse_book_toc where book_id=$book_id and type='$type' order by slno";

$result = $db->query($query); 
$num_rows = $result->num_rows;

//~ 
//~ $result = mysql_query($query);
//~ $num_rows = mysql_num_rows($result);

$stack = array();
$p_stack = array();
$first = 1;

$li_id = 0;
$ul_id = 0;

$plus_link = "<img class=\"bpointer\" title=\"Expand\" src=\"../images/plus.gif\" alt=\"Expand or Collapse\" onclick=\"display_block_inside(this)\" />";
//$plus_link = "<a href=\"#\" onclick=\"display_block(this)\"><img src=\"plus.gif\" alt=\"\"></a>";
$bullet = "<img class=\"bpointer\" src=\"../images/bullet_1.gif\" alt=\"Point\" />";

$month_name = array("0"=>"","1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

//~ $plus_link = "+";
//~ $bullet = ".";


$query_aux = "select * from sse_books_list where book_id=$book_id and type='sse'";

$result_aux = $db->query($query_aux); 
$num_rows_aux = $result_aux->num_rows;

//~ $result_aux = mysql_query($query_aux);
//~ $num_rows_aux = mysql_num_rows($result_aux);

$row_aux = $result_aux->fetch_assoc();
//~ $row_aux=mysql_fetch_assoc($result_aux);

$edition = $row_aux['edition'];
$volume = $row_aux['volume'];
$part = $row_aux['part'];
$authorname = $row_aux['authorname'];
$page = $row_aux['page'];
$page_end = $row_aux['page_end'];
$type = $row_aux['type'];
$year = $row_aux['year'];
$month = $row_aux['month'];

$result_aux->free();

$anames = preg_replace("/;/", ",&nbsp;&nbsp;", $authorname);
$anames = preg_split("/;/", $authorname);

$daname = '';

if(sizeof($anames) > 1)
{
	for($i=0; $i<(sizeof($anames) - 1); $i++)
	{
		$daname = $daname . ",&nbsp;&nbsp;" . $anames[$i];
	}
	$daname = preg_replace("/^,&nbsp;&nbsp;/", "", $daname);
	$daname = $daname . "&nbsp;&nbsp;and&nbsp;&nbsp;" . $anames[sizeof($anames) - 1];
}
else
{
	$daname = $authorname;
}

/*
echo "<div class=\"book_cover\"><img src=\"../images/cover.png\" alt=\"Book Cover\" /></div>";
*/
echo "<div class=\"page_booktitle\"><span class=\"motif sse_motif\"></span><span class=\"itl\">$book_title</span></div>";
echo "<div class=\"page_subtitle\"><span class=\"itl\">$daname</span></div>";
echo "<div class=\"page_other\">";

$book_info = '';
		
if($edition != '00')
{
	$book_info = $book_info . "Edition " . intval($edition);
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
if(intval($month) != 0)
{
	$book_info = $book_info . " | " . $month_name{intval($month)} . " " . intval($year);
}

$book_info = preg_replace("/^ /", "", $book_info);
$book_info = preg_replace("/^\|/", "", $book_info);
$book_info = preg_replace("/^ /", "", $book_info);

echo "$book_info</div>";
if($num_rows)
{
	echo "<div class=\"treeview\">";
	for($i=1;$i<=$num_rows;$i++)
	{
		//~ $row=mysql_fetch_assoc($result);
		$row = $result->fetch_assoc();
		
		$level = $row['level'];
		$title = $row['title'];
		$page = $row['page'];
		$type = $row['type'];
		$slno = $row['slno'];
		
		$title = "<span class=\"titlespan\"><a target=\"_blank\" href=\"../../Volumes/$type/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\">$title</a></span>";
		$title = preg_replace('/!!(.*)!!/', "<i>$1</i>", $title);
		if($first)
		{
			array_push($stack,$level);
			$ul_id++;
			echo "<ul id=\"ul_id$ul_id\">\n";
			array_push($p_stack,$ul_id);
			$li_id++;
			//echo "<li>$title(" . $stack[sizeof($stack)-1] . ")\n";
			//echo "<li>$title\n";
			$deffer = display_tabs($level) . "<li id=\"li_id$li_id\">:rep:$title";
			$first = 0;
		}
		elseif($level > $stack[sizeof($stack)-1])
		{
			//$parent_id = "ul_id" . $p_stack[sizeof($p_stack)-1];
			//$alt_link = $plus_link;
			//$alt_link = preg_replace('/#/',"#$parent_id",$alt_link);
			$deffer = preg_replace('/:rep:/',"$plus_link",$deffer);
			echo $deffer;			

			$ul_id++;			
			$li_id++;			
			array_push($stack,$level);
			array_push($p_stack,$ul_id);
			//echo "<ul>\n\t<li>$title(" . display_stack($stack) . ")\n";
			//echo "<ul>\n\t<li>$title\n";
			$deffer = "\n" . display_tabs(($level-1)) . "<ul class=\"dnone\" id=\"ul_id$ul_id\">\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
		elseif($level < $stack[sizeof($stack)-1])
		{
			$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
			echo $deffer;
			
			for($k=sizeof($stack)-1;(($k>=0) && ($level != $stack[$k]));$k--)
			{
				echo "</li>\n". display_tabs($level) ."</ul>\n";
				$top = array_pop($stack);
				$top1 = array_pop($p_stack);
			}
			$li_id++;
			//echo "</li>\n<li>$title(" . display_stack($stack) . ")\n";
			$deffer = display_tabs($level) . "</li>\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
		elseif($level == $stack[sizeof($stack)-1])
		{
			$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
			echo $deffer;
			$li_id++;
			//echo "</li>\n<li>$title(" . display_stack($stack) . ")\n";
			//echo "</li>\n<li>$title\n";
			$deffer = "</li>\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
	}

	$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
	echo $deffer;

	for($i=0;$i<sizeof($stack);$i++)
	{
		echo "</li>\n". display_tabs($level) ."</ul>\n";
	}

	echo "</div>";
}
else
{
	echo "No data in the database";
}

$result->free();

function display_stack($stack)
{
	for($j=0;$j<sizeof($stack);$j++)
	{
		$disp_array = $disp_array . $stack[$j] . ",";
	}
	return $disp_array;
}

function display_tabs($num)
{
	$str_tabs = "";
	
	if($num != 0)
	{
		for($tab=1;$tab<=$num;$tab++)
		{
			$str_tabs = $str_tabs . "\t";
		}
	}
	
	return $str_tabs;
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

