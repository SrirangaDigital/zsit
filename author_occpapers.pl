#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

$type_code = '03';

use DBI();

open(IN,"zsi_occpapers.xml") or die "can't open zsi_occpapers.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$line = <IN>;

while($line)
{
	if($line =~ /<author>(.*)<\/author>/)
	{
		$authorname = $1;
		insert_authors($authorname);
	}
	$line = <IN>;
}

close(IN);
$dbh->disconnect();


sub insert_authors()
{
	my($authorname) = @_;

	$authorname =~ s/'/\\'/g;
	
	my($sth,$ref,$sth1);
	$sth = $dbh->prepare("select * from author where authorname='$authorname'");
	$sth->execute();
	$ref=$sth->fetchrow_hashref();
	if($sth->rows()==0)
	{
		$sth1=$dbh->prepare("insert into author values('$authorname','$type_code',null)");
		$sth1->execute();
		$sth1->finish();
	}
	else
	{
		$type = $ref->{'type'};
		$authid = $ref->{'authid'};
		
		if(!($type=~/$type_code/))
		{
			$type = $type . ";" . $type_code;
			$sth1=$dbh->prepare("update author set type='$type' where authid='$authid' and authorname='$authorname'");
			$sth1->execute();
			$sth1->finish();
		}
		
	}
	$sth->finish();	
}
