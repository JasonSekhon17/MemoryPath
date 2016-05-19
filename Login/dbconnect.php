<?php
if (!mysql_connect("mysql1.000webhost.com", "a5542276_Jason", "jss575"))
	die('Cannot connect to the server. '.mysql_error());

if(!mysql_select_db('a5542276_MemPath'))
	die('cannot connect to database. '.mysql_error())

?>