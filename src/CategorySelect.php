<?php
namespace EasyVideoPublisher;

/**
 *
 */
class CategorySelect
{

	/**
	 * Custom version of the WP Dropdown Category list
	 *
	 * @param  string $fieldname   field name
	 * @param  array $args define custom arguments
	 * @return
	 * @link https://developer.wordpress.org/reference/functions/wp_dropdown_categories/
	 */
	public static function categories( $fieldname = null,$args = array()){

		if (current_user_can( 'manage_options' )) {
			$restricted = '';
		} else {
			$restricted = get_option('evp_restricted_categories');
		}


		$catlist_args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 1,
			'child_of'           => 0,
			'exclude'            => $restricted,
			'echo'               => 0,
			'selected'           => 0,
			'hierarchical'       => 0,
			'name'               => strtolower(str_replace(" ", "_", $fieldname)).'set_category',
			'id'                 => '',
			'class'              => 'uk-select',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'category',
			'hide_if_empty'      => false,
			'value_field'	     => 'term_id',
		);
		// ref https://developer.wordpress.org/reference/functions/wp_dropdown_categories/
		$categories = '<tr class="input-select">';
		$categories .= '<th><label for="select_dropdown">Select a Category</label></th>';
		$categories .= '<td>';
		$categories .= wp_dropdown_categories($catlist_args);
		$categories .= '</td>';
		$categories .= '</tr>';
		return $categories;
	}
}
