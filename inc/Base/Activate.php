<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Base;

class Activate
{
	public static function run()
	{
		flush_rewrite_rules();

		/**
		 * if the option is already exist in the databse then just return the function
		 */
		if (get_option('myplugin')) {
			return;
		}

		$default = array(
			'cpt_manager' => true
		);

		update_option('myplugin', $default);
	}
}
