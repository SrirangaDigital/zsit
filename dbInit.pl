#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

use DBI();

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth=$dbh->prepare("DROP DATABASE IF EXISTS $db");
$sth->execute();
$sth->finish();

$sth=$dbh->prepare("CREATE DATABASE $db");
$sth->execute();
$sth->finish();

$dbh->disconnect();
