<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 * Undocumented class
 */
class Enqueue extends BaseController
{
	public function register()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	function enqueue()
	{
		// enqueue all our scripts
		wp_enqueue_script('media_upload');
		wp_enqueue_script('jquery');

		wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/main.css');
		wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/main.js');
	}
}
