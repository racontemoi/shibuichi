<?php

require_once(str_replace('//','/',dirname(__FILE__).'/../shi.php'));

$database = _shi('db_name_prefix') . _shi('db_name') . _shi('db_name_suffix');
$server = _shi('db_server');
$user = _shi('db_username');
$password = _shi('db_password');
$mysqldump = _shi('tool_mysqldump');

$dump = `$mysqldump --user=$user --password=$password --host=$server --opt --skip-extended-insert $database`;
$dump_file = _shi('web_root') . '/shi_dump/current.sql';
file_put_contents($dump_file, $dump);
echo "OK.";
?>
