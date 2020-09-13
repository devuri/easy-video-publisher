<?php
namespace VideoPublisherPro\Text;

/**
 * WordProcessor
 */
class WordProcessor
{

  /**
   * text_search() find item in the array
   * array_search will not work for index 0
   * @param string|null $find [description]
   * @param array $text [description]
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
   * find $find by name
   * search the string for artist
   * @param string|null $text text to search
   * @param string $find [description]
   * @return array [type]         [description]
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
