<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallback;

/**
 *
 */
class Admin extends BaseController
{
	public $settings;

	public $callbacks;
	public $managerCallback;

	public $pages = array();

	public $subpages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
		$this->managerCallback = new ManagerCallback();

		$this->setPages();

		$this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
	}

	public function setPages()
	{
		$this->pages = array(
			array(
				'page_title' => 'My plugin',
				'menu_title' => 'hello',
				'capability' => 'manage_options',
				'menu_slug' => 'myplugin',
				'callback' => array($this->callbacks, 'adminDashboard'),
				'icon_url' => 'dashicons-store',
				'position' => 110
			)
		);
	}

	public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'myplugin',
				'page_title' => 'Custom Post Types',
				'menu_title' => 'CPT',
				'capability' => 'manage_options',
				'menu_slug' => 'myplugin_cpt',
				'callback' => array($this->callbacks, 'adminCpt')
			),
			array(
				'parent_slug' => 'myplugin',
				'page_title' => 'Custom Taxonomies',
				'menu_title' => 'Taxonomies',
				'capability' => 'manage_options',
				'menu_slug' => 'myplugin_taxonomy',
				'callback' => array($this->callbacks, 'adminTaxonomy')
			),
			array(
				'parent_slug' => 'myplugin',
				'page_title' => 'Custom Widgets',
				'menu_title' => 'Widgets',
				'capability' => 'manage_options',
				'menu_slug' => 'myplugin_widgets',
				'callback' => array($this->callbacks, 'adminWidget')
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'myplugin_settings',
				'option_name' => 'cpt_manager',
				'callback' => array($this->managerCallback, 'cheakboxSanitize')
			),
			array(
				'option_group' => 'myplugin_settings',
				'option_name' => 'taxonomy_manager',
				'callback' => array($this->managerCallback, 'cheakboxSanitize')
			),
		);

		$this->settings->setSettings($args);
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'myplugin_admin_index',
				'title' => 'Settings',
				'callback' => array($this->managerCallback, 'adminSectionManager'),
				'page' => 'myplugin'
			)
		);

		$this->settings->setSections($args);
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'cpt_manager',
				'title' => 'Activate the custom post typy',
				'callback' => array($this->managerCallback, 'cheakboxField'),
				'page' => 'myplugin',
				'section' => 'myplugin_admin_index',
				'args' => array(
					'label' => 'cpt_manager',
					'classes' => 'ui-toggle'
				)
			),
			array(
				'id' => 'taxonomy_manager',
				'title' => 'Activate the custom Taxonomys',
				'callback' => array($this->managerCallback, 'cheakboxField'),
				'page' => 'myplugin',
				'section' => 'myplugin_admin_index',
				'args' => array(
					'label' => 'taxonomy_manager',
					'classes' => 'ui-toggle'
				)
			),
		);

		$this->settings->setFields($args);
	}
}
