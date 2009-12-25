<?php

require_once(str_replace('//','/',dirname(__FILE__).'/../shi.php'));

$database = _shi('db_name_prefix') . _shi('db_name') . _shi('db_name_suffix');
$server = _shi('db_server');
$user = _shi('db_username');
$password = _shi('db_password');
$mysql = _shi('tool_mysql');
$dump_file = _shi('web_root') . '/shi_dump/current.sql';

$result = `(echo use $database\\; && cat $dump_file) | $mysql --user=$user --password=$password --host=$server`;
echo "<h1>Result</h1><pre>$result</pre>";
?>