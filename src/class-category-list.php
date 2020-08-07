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
			$checkbox  = '<div>';
			$checkbox .= '<input type="checkbox" id="category[]" name="category[]">';
			$checkbox .= '<label for="scales">'.$category->name.' ('.$category->count.')</label>';
			$checkbox .= '</div>';
			echo $checkbox;
		}
	}

}
