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

		if (!get_option('myplugin')) {
			update_option('myplugin', array(
				'cpt_manager' => true
			));
		}

		if (!get_option('myplugin_taxonomy')) {
			update_option('myplugin_taxonomy', array());
		}

		if (!get_option('myplugin_cpt')) {
			update_option('myplugin_cpt', array());
		}
	}
}
