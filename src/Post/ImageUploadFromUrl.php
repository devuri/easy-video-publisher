<?php

namespace VideoPublisherlite\Post;

/**
 * Class ImageUploadFromUrl
 * forked copy of the:
 * @link https://github.com/aleksnagornyi/image_upload_from_url
 * @package ImageUploadFromUrl
 */
class ImageUploadFromUrl
{
    /**
     * @var string|string
     */
    private $remote_url, $filename, $folder, $filePath, $imageType, $rewrite;

    /**
     * Upload constructor.
     *
     * @param string $remote_url
     * @param string $folder
     * @param bool $filename
     * @param bool $rewrite
     * @throws \Exception
     */
    public function __construct( $remote_url, $folder, $filename = false, $rewrite = false)
    {
        if(!filter_var($remote_url, FILTER_VALIDATE_URL)){
            throw new \Exception('Not a valid URL');
        }
        $this->remote_url = $remote_url;

        if( !is_dir( $folder ) ){
            throw new \Exception('Folder Not Exist');
        }
        if( !is_writable ( $folder ) ){
            throw new \Exception('Directory Is Not Writable');
         }

        $this->folder = $folder;
        if($filename == false){
            $this->filename = uniqid();
        }else{
            $this->filename = $filename;
        }
        $this->rewrite = $rewrite;
    }

    /**
     * save image method
     *
     * @return bool|string
     * @throws \Exception
     */
    public function save()
    {
        $dataImage = $this->getImageFromUrl();
        $this->filePath = $this->folder.DIRECTORY_SEPARATOR.$this->filename.'.'.$this->imageType;

        if($this->rewrite == false && file_exists($this->filePath)) throw new \Exception('File Exists');

        switch ($this->imageType){
            case 'gif':
                imagegif($dataImage, $this->filePath);
                break;
            case 'jpg':
                imagejpeg($dataImage, $this->filePath, 100);
                break;
            case 'png':
                imagepng($dataImage, $this->filePath );
                break;
            default:
                throw new \Exception('Not Suported Mime Type');
        }
        imagedestroy($dataImage);

        return pathinfo($this->filePath);
    }

    /**
     * Full file info
     *
     * @return array
     */
    public function getFileInfo()
    {
        return [
            'basename' 	=> basename($this->filePath),
            'dirname' 	=> dirname($this->filePath),
            'realpath' 	=> realpath($this->filePath),
            'pathinfo' 	=> pathinfo($this->filePath),
            'filesize' 	=> filesize($this->filePath),
        ];
    }

	/**
	 * Validate the url
	 *
	 * @return bool
	 */
	public function validateUrl(){
		if ( wp_http_validate_url( $this->remote_url ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get URL Response
	 *
	 * @return array
	 */
	public function getResponse()
	{
		if( ! $this->validateUrl() ){
			throw new \Exception('URL Not Suported or Invalid');
		}
		return wp_remote_get( $this->remote_url );
	}

    /**
     * get image from url and return image recource
     *
     * @return resource
     * @throws \Exception
     * @link https://developer.wordpress.org/plugins/http-api/
     */
    private function getImageFromUrl()
    {
		if( ! wp_remote_retrieve_response_code( $this->getResponse() ) === 200 ) {
			throw new \Exception('Repsonse Not Valid');
		}

        $type	= wp_remote_retrieve_headers( $this->getResponse() )['content-type'];
		$result	= wp_remote_retrieve_body( $this->getResponse() );

        if( $this->checkFileExtensions($type) == false ) {
            throw new \Exception('Not Suported Mime Type');
        }

        $imageResource = @imagecreatefromstring($result);

        if( !$imageResource ){
            throw new \Exception('Not Suported Mime Type');
        }
        return $imageResource;
    }

    /**
     * check type exist image
     *
     * @param string $type
     * @return bool
     */
    private function checkFileExtensions($type)
    {
        $ext = ["image/gif" => 'gif', "image/jpeg" => 'jpg', "image/png" => 'png'];
        if (array_key_exists($type, $ext)) {
            $this->imageType = $ext[$type];
            return true;
        }
        return false;
    }

}
