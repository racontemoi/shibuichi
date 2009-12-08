<?php

require_once(str_replace('//','/',dirname(__FILE__).'/../shi.php'));

$database = _shi('db_name');
$server = _shi('db_server');
$user = _shi('db_username');
$password = _shi('db_password');
$mysqldump = _shi('tool_mysqldump');

$dump = `$mysqldump --user $user --password $password --server $server $database`;
$dump_file = _shi('web_root') . '/shi_dump/current.sql';
put_file_contents($dump_file, $dump);
echo "OK.";
?>