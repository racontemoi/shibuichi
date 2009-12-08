<?php

class SWFUploadControls extends Controller
{
	public function handleswfupload()
	{
		if (isset($_FILES["swfupload_file"]) && is_uploaded_file($_FILES["swfupload_file"]["tmp_name"])) {
			$file = new File();
			$u = new Upload();
			$u->loadIntoFile($_FILES['swfupload_file'], $file, "Resumes");
			$file->write();			
			echo $file->ID;
		} 
		else {
			echo ' '; // return something or SWFUpload won't fire uploadSuccess
		}
		
	}
}