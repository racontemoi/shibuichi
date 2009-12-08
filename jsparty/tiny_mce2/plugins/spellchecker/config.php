<?php
	// General settings

	// Use the PSpell engine if it exists: it supports locales and is quicker than GoogleSpell
	if(function_exists('pspell_new')) {
		$config['general.engine'] = 'PSpell';
	} else {
		$config['general.engine'] = 'GoogleSpell';
	}

	//$config['general.engine'] = 'PSpellShell';
	//$config['general.remote_rpc_url'] = 'http://some.other.site/some/url/rpc.php';

	// PSpell settings
	$config['PSpell.mode'] = PSPELL_FAST;
	$config['PSpell.spelling'] = "";
	$config['PSpell.jargon'] = "";
	$config['PSpell.encoding'] = "";

	// PSpellShell settings
	$config['PSpellShell.mode'] = PSPELL_FAST;
	$config['PSpellShell.aspell'] = '/usr/bin/aspell';
	$config['PSpellShell.tmp'] = '/tmp';

	// Windows PSpellShell settings
	//$config['PSpellShell.aspell'] = '"c:\Program Files\Aspell\bin\aspell.exe"';
	//$config['PSpellShell.tmp'] = 'c:/temp';
?>
