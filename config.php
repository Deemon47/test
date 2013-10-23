<?php
// $CFG['mysql_host']='loaclhost';
$CFG['mysql_user']='root';
$CFG['mysql_pass']='root';
$CFG['mysql_base']='demo';
$CFG['watch_actions']=
[
	'test',
	'error',
	'message',
	'notice',
	//'_GET',
	//'_POST',
	'_SESSION',
	'_INCLUDED'
];