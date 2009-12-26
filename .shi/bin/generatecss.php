<?php

require_once(str_replace('//','/',dirname(__FILE__).'/../shi.php'));

$root = _shi('web_root');
$clevercss = _shi('tool_clevercss');

$dir_iterator = new RecursiveDirectoryIterator($root);
$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
echo "<h1>Result</h1>";
foreach ($iterator as $file) {
	if ($file->isFile() && substr($file->getPathname(), -5) == '.ccss') {
		$ccss = $file->getPathname();
		$css = substr($ccss, 0, strlen($ccss)-5) . '.css';
		unlink($css);
		$result = `cd $root; $clevercss $ccss`;
		if (!file_exists($css))
			throw new Exception("Clevercss did not work on $ccss.");
		$content = file_get_contents($css);
		$converted = preg_replace(array("/\\.0+;/", "/\\.0+px/", "/\\.0+%/", "/\\.0+\\ /"),
		                          array(';', 'px', '%', ' '), $content);
		file_put_contents($css, $converted);
		echo "<p>File $ccss converted to $css.</p>\n";
	}
}
?>