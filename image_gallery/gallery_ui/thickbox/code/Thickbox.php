<?php

class Thickbox extends ImageGalleryUI
{
	static $link_to_demo = "http://jquery.com/demo/thickbox/";
	static $label = "Thickbox";
	public $item_template = "Thickbox_item";
	
	public function initialize()
	{
		Requirements::javascript('jsparty/jquery/jquery.js'); 	
		Requirements::javascript('image_gallery/gallery_ui/thickbox/javascript/thickbox.js');
		Requirements::css('image_gallery/gallery_ui/thickbox/css/thickbox.css');
		
	}

}