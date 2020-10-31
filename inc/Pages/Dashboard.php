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
class Dashboard extends BaseController
{
	public $settings;

	public $callbacks;
	public $managerCallback;

	public $pages = array();

	// public $subpages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
		$this->managerCallback = new ManagerCallback();

		$this->setPages();

		// $this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();
		$this->settings
			->addPages($this->pages)
			->withSubPage('Dashboard')
			->register();
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

	public function setSettings()
	{

		$args = array(
			array(
				'option_group' => 'myplugin_settings',
				'option_name' => 'myplugin',
				'callback' => array($this->managerCallback, 'cheakboxSanitize')
			)
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

		$args = array();

		foreach ($this->managers as $key => $value) {
			$args[] = array(
				'id' => $key,
				'title' => $value,
				'callback' => array($this->managerCallback, 'cheakboxField'),
				'page' => 'myplugin',
				'section' => 'myplugin_admin_index',
				'args' => array(
					'option_name' => 'myplugin',
					'label' => $key,
					'classes' => 'ui-toggle'
				)
			);
		}

		$this->settings->setFields($args);
	}
}
