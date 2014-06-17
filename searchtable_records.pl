#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];


use DBI();

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth11=$dbh->prepare("CREATE TABLE searchtable_records(title varchar(500),
authid varchar(200),
authorname varchar(1000),
featid varchar(10),
text varchar(5000),
page varchar(5),
page_end varchar(5),
cur_page varchar(5),
plates varchar(1000), 
volume varchar(10),
part varchar(10),
year int(4),
month varchar(2),
titleid varchar(30)) ENGINE=MyISAM");
$sth11->execute();
$sth11->finish();

$sth1=$dbh->prepare("select * from article_records order by titleid");
$sth1->execute();

while($ref=$sth1->fetchrow_hashref())
{
	$titleid = $ref->{'titleid'};
	$page = $ref->{'page'};
	$page_end = $ref->{'page_end'};
	$volume = $ref->{'volume'};
	$part = $ref->{'part'};
	$title = $ref->{'title'};
	$authid = $ref->{'authid'};
	$authorname = $ref->{'authorname'};
	$featid = $ref->{'featid'};
	$plates = $ref->{'plates'};
	$year = $ref->{'year'};
	$month = $ref->{'month'};
	
	$title =~ s/'/\\'/g;
	$authorname =~ s/'/\\'/g;
		
	print $volume."\n";
	
	$sth2=$dbh->prepare("select * from ocr_records where volume='$volume' and part='$part' and cur_page between '$page' and '$page_end'");
	$sth2->execute();
	
	while($ref2=$sth2->fetchrow_hashref())
	{
		$text = $ref2->{'text'};
		$cur_page = $ref2->{'cur_page'};
		
		$sth4=$dbh->prepare("insert into searchtable_records values('$title','$authid','$authorname','$featid','$text','$page','$page_end','$cur_page','$plates',
			'$volume','$part','$year','$month','$titleid')");
		$text = '';
		$sth4->execute();
		$sth4->finish();
	}
	$sth2->finish();
}

$sth1->finish();
$dbh->disconnect();
