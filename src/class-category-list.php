<?php
namespace EasyVideoPublisher;

/**
 *
 */
class Category_List
{
	/**
	 * list of categories with checkbox
	 * @return [type] [description]
	 */
	public static function checkbox(){
		$terms = array(
			'taxonomy' => 'category',
			'parent'   => 0
		);
		$categories = get_terms($terms);
		foreach ($categories as $key => $category) {
			$checkbox  = '<div id="category_checkbox" style="padding-bottom: 1em; color:'.self::font_color().';font-weight:600">';
			$checkbox .= '<input type="checkbox" id="category['.$category->term_id.']" name="category['.$category->term_id.']">';
			$checkbox .= '<label for="'.sanitize_file_name(strtolower($category->name)).'">'.$category->name.' ('.$category->count.')</label>';
			$checkbox .= '</div>';
			echo $checkbox;
		}
	}

	/**
	 * font_color()
	 * @return [type] [description]
	 */
	public static function font_color( bool $restrict = false ){
		if ($restrict) {
			$color = '#b9b9b9';
			return $color;
		}
		$color = '#424242';
		return $color;
	}

}
