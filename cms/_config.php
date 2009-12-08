<?php

/**
 * Extended URL rules for the CMS module
 * 
 * @package cms
 */
Director::addRules(50, array(
	'processes//$Action/$ID/$Batch' => 'BatchProcess_Controller',
	'admin/help//$Action/$ID' => 'CMSHelp',
	'admin/ReportField//$Action/$ID/$Type/$OtherID' => 'ReportField_Controller',
	'admin/bulkload//$Action/$ID/$OtherID' => 'BulkLoaderAdmin',
	'admin//ImageEditor/$Action' => 'ImageEditor',
	'admin/cms//$Action/$ID/$OtherID' => 'CMSMain', 
	'PageComment//$Action/$ID' => 'PageComment_Controller',
	'dev/buildcache' => 'RebuildStaticCacheTask',
));

CMSMenu::populate_menu();

// Default CMS HTMLEditorConfig
HtmlEditorConfig::get('cms')->setOptions(array(
	'friendly_name' => 'Default CMS',
	'priority' => '50',
	'mode' => 'none',
	'language' => i18n::get_tinymce_lang(),
	'content_css' => 'cms/css/editor.css, '.(SSViewer::current_theme() ? THEMES_DIR . "/" . SSViewer::current_theme() : project()) . "/css/editor.css",

	'body_class' => 'typography',
	'document_base_url' => Director::absoluteBaseURL(),

	'urlconverter_callback' => "nullConverter",
	'setupcontent_callback' => "sapphiremce_setupcontent",
	'cleanup_callback' => "sapphiremce_cleanup",

	'template_templates' => array(
    	array( 'title' => "Three column", 'src' => "assets/snippet.html", 'description' => "A simple 3 column layout" )
	),

	'use_native_selects' => true, // fancy selects are bug as of SS 2.3.0
	'valid_elements' => "+a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align],-sub[class],-sup[class],-blockquote[dir|class],-table[border=0|cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],-td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir]",
	'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|usemap],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]"
));


HtmlEditorConfig::get('cms')->disablePlugins('blockquote');
HtmlEditorConfig::get('cms')->enablePlugins('media', '../../tinymce_ssbuttons', 'fullscreen');
			
HtmlEditorConfig::get('cms')->insertButtonsBefore('formatselect', 'styleselect');
HtmlEditorConfig::get('cms')->insertButtonsBefore('advcode', 'ssimage', 'ssflash', 'sslink', 'unlink', 'anchor', 'separator' );
HtmlEditorConfig::get('cms')->insertButtonsAfter ('advcode', 'fullscreen', 'separator');
			
HtmlEditorConfig::get('cms')->removeButtons('tablecontrols');
HtmlEditorConfig::get('cms')->addButtonsToLine(3, 'tablecontrols');


?>
