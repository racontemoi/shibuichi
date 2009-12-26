<?php

require_once(str_replace('//','/',dirname(__FILE__).'/../shi.php'));

$root = _shi('web_root');
$clevercss = _shi('tool_clevercss');
$temp = $root . '/silverstripe-cache';
$dir = dir($temp);
$i = 0;
while (false !== ($file = $dir->read())) {
	if (strstr($file, '.cache') || strstr($file, 'manifest')) {
		unlink($temp.'/'.$file);
		$i++;
	}
}
echo "Deleted $i file(s).";
?>