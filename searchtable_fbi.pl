#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];


use DBI();

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth11=$dbh->prepare("CREATE TABLE searchtable_fbi(book_id varchar(4), 
level int(2),
title varchar(1000),
authid varchar(200),
authorname varchar(1000),
page varchar(4),
page_end varchar(4),
edition varchar(2),
volume varchar(3),
part varchar(2),
type varchar(4),
year int(4),
month varchar(2),
slno int(6),
cur_page varchar(10),
text varchar(5000)) ENGINE=MyISAM");
$sth11->execute();
$sth11->finish();

$sth1=$dbh->prepare("select * from fbi_books_list where type='fbi' and book_id not like '000' and book_id not like '' order by book_id");
$sth1->execute();

while($ref=$sth1->fetchrow_hashref())
{
	$book_id = $ref->{'book_id'};
	$level = $ref->{'level'};
	$title = $ref->{'title'};
	$authid = $ref->{'authid'};
	$authorname = $ref->{'authorname'};
	$page = $ref->{'page'};
	$page_end = $ref->{'page_end'};
	$edition = $ref->{'edition'};
	$volume = $ref->{'volume'};
	$part = $ref->{'part'};
	$type = $ref->{'type'};
	$year = $ref->{'year'};
	$month = $ref->{'month'};
	$slno = $ref->{'slno'};
	
	$title =~ s/'/\\'/g;
	$authorname =~ s/'/\\'/g;
		
	print $book_id."\n";
	
	$sth2=$dbh->prepare("select * from ocr_fbi where volume='$book_id'");
	$sth2->execute();
	
	while($ref2=$sth2->fetchrow_hashref())
	{
		$text = $ref2->{'text'};
		$cur_page = $ref2->{'cur_page'};
		
		$sth4=$dbh->prepare("insert into searchtable_fbi values('$book_id', '$level', '$title', '$authid', '$authorname', '$page', '$page_end', '$edition', '$volume', '$part', '$type', '$year', '$month', '$slno', '$cur_page', '$text')");
		$text = '';
		$sth4->execute();
		$sth4->finish();
	}
	$sth2->finish();
}

$sth1->finish();
$dbh->disconnect();
