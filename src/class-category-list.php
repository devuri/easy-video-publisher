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
			if ( in_array( $category->term_id , get_option('evp_restricted_categories') ) ) {
				$color = '#b9b9b9';
				$checkbox  = '<div id="category_checkbox" style="padding-bottom: 1em; color:'.$color.';font-weight:600">';
				$checkbox .= '<input type="checkbox" id="category['.$category->term_id.']" name="category['.$category->term_id.']" checked>';
				$checkbox .= '<label for="'.sanitize_file_name(strtolower($category->name)).'">'.$category->name.' ('.$category->count.')</label>';
				$checkbox .= '</div>';
				echo $checkbox;
			} else {
				$color = '#424242';
				$checkbox  = '<div id="category_checkbox" style="padding-bottom: 1em; color:'.$color.';font-weight:600">';
				$checkbox .= '<input type="checkbox" id="category['.$category->term_id.']" name="category['.$category->term_id.']">';
				$checkbox .= '<label for="'.sanitize_file_name(strtolower($category->name)).'">'.$category->name.' ('.$category->count.')</label>';
				$checkbox .= '</div>';
				echo $checkbox;
			}
		}
	}

}
