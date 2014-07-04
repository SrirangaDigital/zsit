<?php

require_once("../common.php");
$goto_num = $_POST['goto_num'];

if(isValidVolume($goto_num))
{
	$goto_num = intval($goto_num);
	$goto_num = str_pad($goto_num, 3, "0", STR_PAD_LEFT);
	@header("Location:papers.php#p$goto_num");
}
else
{
	@header("Location:papers.php");
}

?>
