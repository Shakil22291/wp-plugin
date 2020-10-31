<?php

namespace Inc\Base;

use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\CptCallbacks;
use Inc\Api\SettingsApi;

class CustomPostTypeController extends BaseController
{
    public $subpages = array();
    public $settings;
    public $callbacks;
    public $cptCallbacks;

    public $custom_post_types;

    public function register()
    {

        if (!$this->activated('cpt_manager')) return;

        $this->settings = new SettingsApi();
        $this->cptCallbacks = new CptCallbacks();
        $this->callbacks = new AdminCallbacks();

        $this->setSubpages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addSubPages($this->subpages)->register();

        add_action('init', array($this, 'registerCustomPostType'));
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
        );
    }


    public function registerCustomPostType()
    {
        $options = get_option('myplugin_cpt');

        foreach ($options as $option) {
            register_post_type(
                $option['post_type'],
                array(
                    'labels' => array(
                        'name' => $option['post_type_name'],
                        'singular_name' => $option['singular_name']
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'capability_type' => 'post',
                    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats'),
                    'taxonomies'  => array('category', 'post_tag'),
                )
            );
        }
    }

    public function setSettings()
    {

        $args = array(
            array(
                'option_group' => 'myplugin_cpt',
                'option_name' => 'myplugin_cpt',
                'callback' => array($this->cptCallbacks, 'cptSanitize')
            )
        );

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = array(
            array(
                'id' => 'myplugin_cpt_index',
                'title' => 'Settings',
                'page' => 'myplugin_cpt'
            )
        );

        $this->settings->setSections($args);
    }

    public function setFields()
    {

        $args = array(
            array(
                'id' => 'post_type',
                'title' => 'ID',
                'callback' => array($this->cptCallbacks, 'textField'),
                'page' => 'myplugin_cpt',
                'section' => 'myplugin_cpt_index',
                'args' => array(
                    'option_name' => 'myplugin_cpt',
                    'label' => 'post_type',
		),
            ),
            array(
                'id' => 'post_type_name',
                'title' => 'Post type name',
                'callback' => array($this->cptCallbacks, 'textField'),
                'page' => 'myplugin_cpt',
                'section' => 'myplugin_cpt_index',
                'args' => array(
                    'option_name' => 'myplugin_cpt',
                    'label' => 'post_type_name',
                )
            ),
            array(
                'id' => 'singular_name',
                'title' => 'Singular Name',
                'callback' => array($this->cptCallbacks, 'textField'),
                'page' => 'myplugin_cpt',
                'section' => 'myplugin_cpt_index',
                'args' => array(
                    'option_name' => 'myplugin_cpt',
                    'label' => 'singular_name',
                )
            ),
        );

        $this->settings->setFields($args);
    }
}
