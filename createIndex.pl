#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

use DBI();

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_records ON searchtable_records (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_memoirs ON searchtable_memoirs (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_occpapers ON searchtable_occpapers (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_fbi ON searchtable_fbi (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_fi ON searchtable_fi (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_sfs ON searchtable_sfs (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_cas ON searchtable_cas (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_ess ON searchtable_ess (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_hpg ON searchtable_hpg (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_spb ON searchtable_spb (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_sse ON searchtable_sse (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_tcm ON searchtable_tcm (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_zlg ON searchtable_zlg (text)");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE FULLTEXT index text_index_bulletin ON searchtable_bulletin (text)");
$sth->execute();
$sth->finish();

$dbh->disconnect();
