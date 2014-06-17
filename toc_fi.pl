#!/usr/bin/perl
$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

use DBI();

open(IN,"zsi_fi_toc.xml") or die "can't open zsi_fi_toc.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth1=$dbh->prepare("CREATE TABLE fi_book_toc(book_id varchar(4), 
level int(2),
title varchar(1000),
page varchar(10),
type varchar(4),
slno int(6) auto_increment, primary key(slno)) auto_increment=10001 ENGINE=MyISAM");
$sth1->execute();
$sth1->finish();

$line = <IN>;
$scount = 0;

while($line)
{
	chop($line);
	if($line =~ /<fi>/)
	{
		$type = "fi";
	}	
	elsif($line =~ /<book bid="(.*)">/)
	{
		$book_id = $1;
	}
	elsif($line =~ /<s([0-9]+) page="(.*)" title="(.*)">/)
	{
		$level = $1;
		$page = $2;
		$title = $3;
		insert_to_db($book_id,$level,$title,$page,$type);
		$scount++;
	}
	elsif($line =~ /<s([0-9]+) page="(.*)" title="(.*)"><\/s([0-9]+)>/)
	{
		$level = $1;
		$page = $2;
		$title = $3;
		insert_to_db($book_id,$level,$title,$page,$type);		
		$scount++;
	}
	elsif($line =~ /<\/s([0-9]+)>/)
	{
	}
	else
	{
		#~ print $line . "\n";
	}
$line = <IN>;	
}

close(IN);

#~ print "Total S count:" . $scount . "\n";

sub insert_to_db()
{
	my($book_id,$level,$title,$page) = @_;
	my($sth2);

	$title =~ s/'/\\'/g;
	$title =~ s/<i>/!!/g;
	$title =~ s/<\/i>/!!/g;

	$sth2=$dbh->prepare("insert into fi_book_toc values('$book_id','$level','$title','$page','$type','')");
	$sth2->execute();
	$sth2->finish();
}
