<?php

class Shadowbox extends ImageGalleryUI
{
	static $label = "Shadowbox";
	static $link_to_demo = "http://www.shadowbox-js.com/";
	public $item_template = "Shadowbox_item";
	
	
	public function initialize()
	{
		Requirements::javascript('jsparty/jquery/jquery.js'); 	
		Requirements::javascript('image_gallery/gallery_ui/shadowbox/javascript/shadowbox-2.0.js');
		Requirements::javascript('image_gallery/gallery_ui/shadowbox/javascript/shadowbox_skin.js');
		Requirements::javascript('image_gallery/gallery_ui/shadowbox/javascript/shadowbox_init.js');
		Requirements::css('image_gallery/gallery_ui/shadowbox/css/shadowbox.css');
		
	}

}