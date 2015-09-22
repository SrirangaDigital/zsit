#!/bin/sh

host="localhost"
db="zsi"
usr="root"
pwd='mysql'

echo "drop database if exists zsi; create database zsi;" | /usr/bin/mysql -u$usr -p$pwd

perl author_records.pl $host $db $usr $pwd
perl author_memoirs.pl $host $db $usr $pwd
perl author_occpapers.pl $host $db $usr $pwd
perl author_fbi_fi.pl $host $db $usr $pwd
perl author_sfs.pl $host $db $usr $pwd
perl author_cas.pl $host $db $usr $pwd
perl author_ess.pl $host $db $usr $pwd
perl author_hpg.pl $host $db $usr $pwd
perl author_spb.pl $host $db $usr $pwd
perl author_sse.pl $host $db $usr $pwd
perl author_tcm.pl $host $db $usr $pwd
perl author_zlg.pl $host $db $usr $pwd
perl author_bulletin.pl $host $db $usr $pwd

perl feat_records.pl $host $db $usr $pwd
perl feat_memoirs.pl $host $db $usr $pwd
perl feat_occpapers.pl $host $db $usr $pwd
perl feat_bulletin.pl $host $db $usr $pwd

perl articles_records.pl $host $db $usr $pwd
perl articles_memoirs.pl $host $db $usr $pwd
perl articles_occpapers.pl $host $db $usr $pwd
perl articles_bulletin.pl $host $db $usr $pwd

perl books_fbi_fi.pl $host $db $usr $pwd
perl toc_fbi.pl $host $db $usr $pwd
perl si_fbi.pl $host $db $usr $pwd

perl toc_fi.pl $host $db $usr $pwd
perl si_fi.pl $host $db $usr $pwd

perl books_sfs.pl $host $db $usr $pwd
perl toc_sfs.pl $host $db $usr $pwd

perl books_cas.pl $host $db $usr $pwd
perl toc_cas.pl $host $db $usr $pwd

perl books_ess.pl $host $db $usr $pwd
perl toc_ess.pl $host $db $usr $pwd

perl books_hpg.pl $host $db $usr $pwd
perl toc_hpg.pl $host $db $usr $pwd

perl books_spb.pl $host $db $usr $pwd
perl toc_spb.pl $host $db $usr $pwd

perl books_sse.pl $host $db $usr $pwd
perl toc_sse.pl $host $db $usr $pwd

perl books_tcm.pl $host $db $usr $pwd
perl toc_tcm.pl $host $db $usr $pwd

perl books_zlg.pl $host $db $usr $pwd
perl toc_zlg.pl $host $db $usr $pwd

perl ocr_records.pl $host $db $usr $pwd
perl ocr_memoirs.pl $host $db $usr $pwd
perl ocr_occpapers.pl $host $db $usr $pwd
perl ocr_fbi.pl $host $db $usr $pwd
perl ocr_fi.pl $host $db $usr $pwd
perl ocr_sfs.pl $host $db $usr $pwd
perl ocr_cas.pl $host $db $usr $pwd
perl ocr_ess.pl $host $db $usr $pwd
perl ocr_hpg.pl $host $db $usr $pwd
perl ocr_spb.pl $host $db $usr $pwd
perl ocr_sse.pl $host $db $usr $pwd
perl ocr_tcm.pl $host $db $usr $pwd
perl ocr_zlg.pl $host $db $usr $pwd
perl ocr_bulletin.pl $host $db $usr $pwd

perl searchtable_records.pl $host $db $usr $pwd
perl searchtable_memoirs.pl $host $db $usr $pwd
perl searchtable_occpapers.pl $host $db $usr $pwd
perl searchtable_fbi.pl $host $db $usr $pwd
perl searchtable_fi.pl $host $db $usr $pwd
perl searchtable_sfs.pl $host $db $usr $pwd
perl searchtable_cas.pl $host $db $usr $pwd
perl searchtable_ess.pl $host $db $usr $pwd
perl searchtable_hpg.pl $host $db $usr $pwd
perl searchtable_spb.pl $host $db $usr $pwd
perl searchtable_sse.pl $host $db $usr $pwd
perl searchtable_tcm.pl $host $db $usr $pwd
perl searchtable_zlg.pl $host $db $usr $pwd
perl searchtable_bulletin.pl $host $db $usr $pwd

echo "create fulltext index text_index_records on searchtable_records (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_memoirs on searchtable_memoirs (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_occpapers on searchtable_occpapers (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_fbi on searchtable_fbi (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_fi on searchtable_fi (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_sfs on searchtable_sfs (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_cas on searchtable_cas (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_ess on searchtable_ess (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_hpg on searchtable_hpg (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_spb on searchtable_spb (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_sse on searchtable_sse (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_tcm on searchtable_tcm (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_zlg on searchtable_zlg (text);" | /usr/bin/mysql -u$usr -p$pwd $db
echo "create fulltext index text_index_bulletin on searchtable_bulletin (text);" | /usr/bin/mysql -u$usr -p$pwd $db
