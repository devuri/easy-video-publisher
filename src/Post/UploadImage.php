<?php
namespace VideoPublisherlite\Post;

/**
 *
 */
class UploadImage extends ImageUploadFromUrl
{

  /**
   * new uploader
   * @param null $image_url
   * @param string $upload_dir
   * @param string $filename
   * @return UploadImage
   * @throws \Exception
   */
	public static function upload( $image_url = null , $upload_dir = '', $filename = ''  ){
		$file = new UploadImage( $image_url , $upload_dir , $filename , false );
		return $file;
	}

}
