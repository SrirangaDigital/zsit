<?php

function isValidId($book_id)
{
	return preg_match("/^[0-9][0-9][0-9]$/", $book_id) ? true : false;
}

function isValidType($type)
{
	return preg_match("/^(rec|mem|occ|fbi|fi|sfs|cas|ess|hpg|spb|sse|tcm|zlg|bul)$/", $type) ? true : false;
}

function isValidTitle($title)
{
/*
	return preg_match("/^[a-zA-Z0-9,\(\):;\.\&\-\'\_ ]+$/", $title) ? true : false;
*/
	return(true);
}

function isValidLetter($letter)
{
	return preg_match("/^([A-Z]|Special)$/", $letter) ? true : false;
}

function isValidVolume($vol)
{
	return preg_match("/^[0-9][0-9][0-9]$/", $vol) ? true : false;
}

function isValidPart($part)
{
	return preg_match("/^([0-9][0-9]|[0-9][0-9]\-[0-9][0-9])$/", $part) ? true : false;
}

function isValidYear($year)
{
	return preg_match("/^([0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9]\-[0-9][0-9])$/", $year) ? true : false;
}

function isValidFeature($feature)
{
/*
	return preg_match("/^[a-zA-Z0-9,\(\):;\.\&\-\'\_ ]+$/", $feature) ? true : false;
*/
	return(true);
}

function isValidFeatid($featid)
{
	return preg_match("/^[0-9][0-9][0-9][0-9][0-9]$/", $featid) ? true : false;
}

function isValidAuthid($authid)
{
	return preg_match("/^[0-9][0-9][0-9][0-9][0-9]$/", $authid) ? true : false;
}

function isValidAuthor($author)
{
/*
	return preg_match("/^[a-zA-Z0-9,\(\):;\.\&\- ]+$/", $author) ? true : false;
*/
	return(true);
}

function isValidText($text)
{
/*
	return preg_match("/^[a-zA-Z0-9,\(\):;\.\&\- ]+$/", $author) ? true : false;
*/
	return(true);
}

function entityReferenceReplace($term)
{
	$term = preg_replace("/<i>/", "", $term);
	$term = preg_replace("/<\/i>/", "", $term);
	$term = preg_replace("/\;/", "&#59;", $term);
	$term = preg_replace("/</", "&#60;", $term);
	$term = preg_replace("/=/", "&#61;", $term);
	$term = preg_replace("/>/", "&#62;", $term);
	$term = preg_replace("/\(/", "&#40;", $term);
	$term = preg_replace("/\)/", "&#41;", $term);
	$term = preg_replace("/\:/", "&#58;", $term);
	$term = preg_replace("/Drop table|Create table|Alter table|Delete from|Desc table|Show databases/i", "", $term);
	
	return($term);
}


/*
isValidTitle, isValidFeature, isValidAuthor, isValidText
*/
?>
