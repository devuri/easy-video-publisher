<?php
namespace VideoPublisherlite\Post;

class UploadImage extends ImageUploadFromUrl
{

  	/**
  	 * New uploader
  	 *
  	 * @param null   $image_url direct link the image.
  	 * @param string $upload_dir the upload dir.
  	 * @param string $filename The file name.
  	 * @return UploadImage
  	 * @throws \Exception Execption.
  	 */
	public static function upload( $image_url = null, $upload_dir = '', $filename = '' ) {
		$file = new UploadImage( $image_url, $upload_dir, $filename, false );
		return $file;
	}
}
