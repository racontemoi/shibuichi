<?php
class ColorBox extends ImageGalleryUI
{
	static $link_to_demo = "http://colorpowered.com/colorbox/core/example1/index.html";
	static $label = "Color Box";
	public $item_template = "ColorBox_item";

	public function initialize()
	{
		Requirements::javascript('jsparty/jquery/jquery.js'); 
		Requirements::javascript('image_gallery/gallery_ui/colorbox/javascript/jquery.colorbox.js');
		Requirements::javascript('image_gallery/gallery_ui/colorbox/javascript/colorbox_init.js');
		Requirements::css('image_gallery/gallery_ui/colorbox/css/colorbox.css');
	}	
}