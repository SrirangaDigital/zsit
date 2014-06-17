#!/usr/bin/perl
$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

use DBI();

open(IN,"zsi_zlg_toc.xml") or die "can't open zsi_zlg_toc.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth1=$dbh->prepare("CREATE TABLE zlg_book_toc(book_id varchar(4), 
level int(2),
title varchar(1000),
authid varchar(200),
authorname varchar(1000),
page varchar(10),
type varchar(4),
slno int(6) auto_increment, primary key(slno)) auto_increment=10001 ENGINE=MyISAM");
$sth1->execute();
$sth1->finish();

$line = <IN>;
$scount = 0;

$authid = '';
$authors = '';
while($line)
{
	chop($line);
	if($line =~ /<zlg>/)
	{
		$type = "zlg";
	}	
	elsif($line =~ /<book bid="(.*)">/)
	{
		$book_id = $1;
	}
	elsif($line =~ /<s([0-9]+) page="(.*)" title="(.*)" author="(.*)">/)
	{
		$level = $1;
		$page = $2;
		$title = $3;
		$authors = $4;
		if($authors ne "")
		{
			@list = split(/;/,$authors);
			for($i=0;$i<@list;$i++)
			{
				$authid = $authid . ";" . get_authid($list[$i]);
			}
			$authid =~ s/^;//;
		}
		else
		{
			$authid = "";
		}
		insert_to_db($book_id,$level,$title,$authid,$authors,$page,$type);
		$scount++;
		$authid = "";
		$authors = "";
	}
	elsif($line =~ /<s([0-9]+) page="(.*)" title="(.*)" author="(.*)"><\/s([0-9]+)>/)
	{
		$level = $1;
		$page = $2;
		$title = $3;
		$authors = $4;
		if($authors ne "")
		{
			@list = split(/;/,$authors);
			for($i=0;$i<@list;$i++)
			{
				print $list[$i] . "\n";
				$authid = $authid . ";" . get_authid($list[$i]);
			}
			$authid =~ s/^;//;
		}
		else
		{
			$authid = "";
		}
		insert_to_db($book_id,$level,$title,$authid,$authors,$page,$type);		
		$scount++;
		$authid = "";
		$authors = "";
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
	my($book_id,$level,$title,$authid,$authors,$page) = @_;
	my($sth2);

	$title =~ s/'/\\'/g;
	$title =~ s/<i>/!!/g;
	$title =~ s/<\/i>/!!/g;

	$sth2=$dbh->prepare("insert into zlg_book_toc values('$book_id','$level','$title','$authid','$authors','$page','$type','')");
	$sth2->execute();
	$sth2->finish();
}

sub get_authid()
{
	my($authorname) = @_;
	my($sth,$ref,$authid);

	$authorname =~ s/'/\\'/g;
	
	$sth=$dbh->prepare("select authid from author where authorname='$authorname'");
	$sth->execute();
			
	my $ref = $sth->fetchrow_hashref();
	$authid = $ref->{'authid'};
	$sth->finish();
	return($authid);
}
