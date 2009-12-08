<?php

class ImageGalleryAlbum extends DataObject
{
	static $db = array (
		'AlbumName' => 'Varchar(255)',
		'Description' => 'Text'
	);
	
	static $has_one = array (
		'CoverImage' => 'Image',
		'ImageGalleryPage' => 'ImageGalleryPage',
		'Folder' => 'Folder'
	);
	
	static $has_many = array (
		'GalleryItems' => 'ImageGalleryItem'
	);
	

	
	public function getCMSFields_forPopup()
	{
		return new FieldSet(
			new TextField('AlbumName', _t('ImageGalleryAlbum.ALBUMTITLE','Album Title')),
			new TextareaField('Description', _t('ImageGalleryAlbum.DESCRIPTION','Description')),
			new ImageField('CoverImage',_t('ImageGalleryAlbum.COVERIMAGE','Cover Image'))
		);
	}
	
	public function Link()
	{
		return $this->ImageGalleryPage()->Link('album/'.$this->Folder()->Name);
	}
	
	public function LinkingMode()
	{
		return Director::urlParam('ID') == $this->Folder()->Name ? "current" : "link";
	}
	
	public function ImageCount()
	{
		$images = DataObject::get("ImageGalleryItem","AlbumID = {$this->ID}"); 
		return $images ? $images->Count() : 0;
	}
	
	public function FormattedCoverImage()
	{
		return $this->CoverImage()->CroppedImage($this->ImageGalleryPage()->CoverImageWidth,$this->ImageGalleryPage()->CoverImageHeight);
	}
	
	function onBeforeWrite()
	{
		parent::onBeforeWrite();
		if(isset($_POST['AlbumName'])) {
			if($this->FolderID) {
				$this->Folder()->setName($_POST['AlbumName']);
				$this->Folder()->Title = $_POST['AlbumName'];

				$this->Folder()->write();
			}
			else {
				$folder = Folder::findOrMake('image-gallery/'.$this->ImageGalleryPage()->RootFolder()->Name.'/'.$_POST['AlbumName']);
				$this->FolderID = $folder->ID;
			}
		}
	}
	
	function onBeforeDelete()
	{
		parent::onBeforeDelete();
		$this->GalleryItems()->removeAll();
		$this->Folder()->delete();
	}
	
	
}


?>