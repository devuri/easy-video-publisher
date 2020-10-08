<?php
namespace VideoPublisherlite\Text;

if ( ! defined('ABSPATH') ) exit;

/**
 * WordProcessor
 */
class WordProcessor
{

  	/**
  	 * text_search() find item in the array, array_search will not work for index 0
  	 *
  	 * @param  string|null $find
  	 * @param  array  $text
  	 * @return string $result
  	 * @link https://stackoverflow.com/questions/16750311/php-in-array-or-array-search-not-working
  	 */
	private static function text_search( string $find = null , array $text  = array() ){
		$search = array_search( $find , $text ) !== false;
		if ( $search ) {
			$search = array_search( $find , $text );
			$result = $text[$search];
		}
		return $result;
	}

	/**
	 * text_to_array() convert the text to array
	 *
	 * @param  string $text [description]
	 * @return array       [description]
	 */
	public static function text_to_array( $text = null ){

		// clean up and remove unwanted elements.
		$text = strtolower( $text );
		$text = sanitize_title($text);
		$text = str_replace( "-", " ", $text );

		// convert string to array.
		// @link https://www.php.net/manual/en/function.str-word-count.php
		$text = str_word_count( $text , 1, '1..9ü' );
		return $text;
	}

 	/**
 	 * find $find by name, search the string for text
 	 *
 	 * @param  string $text
 	 * @param  string $find
 	 * @return array
 	 */
	public static function find_word( string $text = null , $find = null ){

		// lowercase
		$find = strtolower( $find );

		// convert the text to array
		$text = self::text_to_array($text);

		// find string in the array
		$result = self::text_search( $find , $text );

		// add the result to empty $match array
		$match = array();
		$match[] = $result;

		// output
		return $match;
	}

}
