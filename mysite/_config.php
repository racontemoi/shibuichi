<?php

global $project;
$project = 'mysite';

require_once(str_replace('//','/',dirname(__FILE__).'/') .'../.shi/shi.php');

if (_shi('admin_set') > 0)
	Security::setDefaultAdmin(_shi('admin_username'), _shi('admin_password'));

global $databaseConfig;
$database = _shi('db_name_prefix') . _shi('db_name') . _shi('db_name_suffix');
$databaseConfig = 
	array('type' => 'MySQLDatabase',
	      'server' => _shi('db_server'),
	      'username' => _shi('db_username'),
	      'password' => _shi('db_password'),
	      'database' => $database);

if (_shi('mode') == 'dev')
	Director::set_environment_type('dev');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.com/themes/
SSViewer::set_theme('blackcandy');

?>