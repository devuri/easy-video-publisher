<?php
namespace EasyVideoPublisher\Post;

/**
 *
 */
class UploadImage extends ImageUploadFromUrl
{

	/**
	 * new uploader
	 */
	public static function upload( $image_url = null , $upload_dir = '', $filename = ''  ){
		$file = new UploadImage( $image_url , $upload_dir , $filename , false );
		return $file;
	}

}
