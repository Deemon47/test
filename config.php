<?php
//MYSQL Settings
//host name
$CFG['mysql_host']='loaclhost';
//mysql user name
$CFG['mysql_user']='root';
//mysql user password
$CFG['mysql_pass']='root';
//mysql base name
$CFG['mysql_base']='demo';
//show debug block
$CFG['show_debug']=false;
//show debug messages of type
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