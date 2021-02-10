<?php
namespace VideoPublisherlite\Text;

/**
 * WordProcessor
 */
class WordProcessor
{

  	/**
  	 * Text search() find item in the array,
  	 * array_search will not work for index 0
  	 *
  	 * @param  string|null $find .
  	 * @param  array       $text .
  	 * @return string      $result .
  	 * @link https://stackoverflow.com/questions/16750311/php-in-array-or-array-search-not-working
  	 */
	private static function text_search( $find = null, $text = array() ) : string {
		$search = array_search( $find, $text, true ) !== false;
		if ( $search ) {
			$search = array_search( $find, $text, true );
			$result = $text[ $search ];
		}
		return $result;
	}

	/**
	 * Convert text to array()
	 *
	 * @param  string $text .
	 * @return array  $text .
	 */
	public static function text_to_array( $text = null ) : array {

		// clean up and remove unwanted elements.
		$text = strtolower( $text );
		$text = sanitize_title( $text );
		$text = str_replace( '-', ' ', $text );

		/**
		 * Convert string to array
		 *
		 * @link https://www.php.net/manual/en/function.str-word-count.php
		 */
		$text = str_word_count( $text, 1, '1..9ü' );
		return $text;
	}

 	/**
 	 * Find $find by name, search the string for text
 	 *
 	 * @param string $text .
 	 * @param string $find .
 	 * @return array
 	 */
	public static function find_word( $text = null, $find = null ) : array {

		$find = strtolower( $find );

		$text = self::text_to_array( $text );

		$result = self::text_search( $find, $text );

		$match = array();
		$match[] = $result;

		return $match;
	}

}
