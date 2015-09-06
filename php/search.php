<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zoological Survey of India | Digital archives of their Publications</title>
<link href="style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="style/jquery-ui.css" />
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

</head>

<body>
<div class="page">
	<div class="header">
		<div class="zsi_logo"><img src="images/logo.png" alt="ZSI Logo" /></div>
		<div class="gov_logo"><img src="images/gov_logo.png" alt="Government of India Logo" /></div>
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
?>
			<div class="page_title">
				<p style="float: right;">
				<span class="motif rec_motif nrmargin"></span>
				<span class="motif mem_motif nrmargin"></span>
				<span class="motif occ_motif nrmargin"></span>
				<span class="motif fbi_motif nrmargin"></span>
				<span class="motif sfs_motif nrmargin"></span>
				<span class="motif cas_motif nrmargin"></span>
				<span class="motif ess_motif nrmargin"></span>
				<span class="motif hpg_motif nrmargin"></span>
				<span class="motif spb_motif nrmargin"></span>
				<span class="motif sse_motif nrmargin"></span>
				<span class="motif tcm_motif nrmargin"></span>
				<span class="motif zlg_motif nrmargin"></span>
				</p>
				Search
			</div>
			<div class="archive_search">
				<form method="get" action="search-result.php">
				<table>
					<tr>
						<td class="right" colspan="2">
							In<br />
							<input type="checkbox" name="check[]" value="rec" id="check_rec"/>&nbsp;<label for="check_rec">Records</label><br />
							<input type="checkbox" name="check[]" value="mem" id="check_mem"/>&nbsp;<label for="check_mem">Memoirs</label><br />
							<input type="checkbox" name="check[]" value="occ" id="check_occ"/>&nbsp;<label for="check_occ">Occasional Papers</label><br />
							<input type="checkbox" name="check[]" value="fbi" id="check_fbi"/>&nbsp;<label for="check_fbi">Fauna of British India</label><br />
							<input type="checkbox" name="check[]" value="fi" id="check_fi"/>&nbsp;<label for="check_fi">Fauna of India</label><br />
							<input type="checkbox" name="check[]" value="sfs" id="check_sfs"/>&nbsp;<label for="check_sfs">State Fauna Series</label><br />
							<input type="checkbox" name="check[]" value="cas" id="check_cas"/>&nbsp;<label for="check_cas">Conservation Area Series</label><br />
							<input type="checkbox" name="check[]" value="ess" id="check_ess"/>&nbsp;<label for="check_ess">Ecosystem Series</label><br />
							<input type="checkbox" name="check[]" value="hpg" id="check_hpg"/>&nbsp;<label for="check_hpg">Handbook and Pictorial Guides</label><br />
							<input type="checkbox" name="check[]" value="spb" id="check_spb"/>&nbsp;<label for="check_spb">Special Publications</label><br />
							<input type="checkbox" name="check[]" value="sse" id="check_sse"/>&nbsp;<label for="check_sse">Status Survey of Endangered Species</label><br />
							<input type="checkbox" name="check[]" value="tcm" id="check_tcm"/>&nbsp;<label for="check_tcm">Technical Monographs</label><br />
							<input type="checkbox" name="check[]" value="zlg" id="check_zlg"/>&nbsp;<label for="check_zlg">Zoologiana</label><br />
							<input type="checkbox" name="check[]" value="bul" id="check_bul"/>&nbsp;<label for="check_bul">Bulletin</label>
						</td>
					</tr>
<?php

//~ $db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
//~ $rs = mysql_select_db($database,$db) or die("No Database");

echo "<tr>
	<td class=\"left\"><label for=\"autocomplete\" class=\"titlespan\">Author</label></td>
	<td class=\"right\"><input name=\"author\" type=\"text\" class=\"titlespan wide\" id=\"autocomplete\" maxlength=\"150\" />";
	
$query_ac = "select * from author where type regexp '01|02|03|04|05|06|07|08|09|10|11|12|13' order by authorname";

$result_ac = $db->query($query_ac); 
$num_rows_ac = $result_ac ? $result_ac->num_rows : 0;

//~ $result_ac = mysql_query($query_ac);
//~ $num_rows_ac = mysql_num_rows($result_ac);

echo "<script type=\"text/javascript\">$( \"#autocomplete\" ).autocomplete({source: [ ";

$source_ac = '';

if($num_rows_ac > 0)
{
	for($i=1;$i<=$num_rows_ac;$i++)
	{
		//~ $row_ac=mysql_fetch_assoc($result_ac);
		$row_ac = $result_ac->fetch_assoc();

		$authorname=$row_ac['authorname'];

		$source_ac = $source_ac . ", ". "\"$authorname\"";
	}
	$source_ac = preg_replace("/^\, /", "", $source_ac);
}

echo "$source_ac ]});</script></td>";
echo "</tr>
<tr>
	<td class=\"left\"><label for=\"textfield2\" class=\"titlespan\">Title</label></td>
	<td class=\"right\"><input name=\"title\" type=\"text\" class=\"titlespan wide\" id=\"textfield2\" maxlength=\"150\" autocomplete=\"off\"/></td>
</tr>";

if($result_ac){$result_ac->free();}
$db->close();
?>
					<tr>
						<td class="left"><label for="textfield3" class="titlespan">Words</label></td>
						<td class="right"><input name="text" type="text" class="titlespan wide" id="textfield3" maxlength="150" autocomplete="off"/></td>
					</tr>
					<tr>
						<td class="left">&nbsp;</td>
						<td class="right">
							<input name="searchform" type="submit" class="titlespan med" id="button_search" value="Search"/>
							<input name="resetform" type="reset" class="titlespan med" id="button_reset" value="Reset"/>
						</td>
					</tr>
				</table>
				</form>
			</div>
		</div>
	</div>
<?php include("include_footer.php");?>
	<div class="clearfix"></div>
</div>
<?php include("include_footer_out.php");?>
</body>

</html>
