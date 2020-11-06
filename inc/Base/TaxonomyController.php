<?php

namespace Inc\Base;

use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\TaxonomyCallbacks;
use Inc\Api\SettingsApi;

class TaxonomyController extends BaseController
{
    public $subpages = array();
    public $settings;
    public $callbacks;
    public $taxonomies = array();

    public $taxonomy_callbacks;

    public function register()
    {

        if (!$this->activated('taxonomy_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->taxonomy_callbacks = new TaxonomyCallbacks();

        $this->setSubpages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addSubPages($this->subpages)->register();

        $this->store();

        if( ! empty($this->taxonomies)) {
            add_action('init', array($this, 'registerCustomTaxonomy'));
        }
    }

    public function setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'myplugin',
                'page_title' => 'Custom taxonomy',
                'menu_title' => 'Taxonomy',
                'capability' => 'manage_options',
                'menu_slug' => 'myplugin_taxonomy',
                'callback' => array($this->callbacks, 'adminTaxonomy')
            ),
        );
    }

    public function setSettings()
    {
        $args = array(
            array(
                'option_group' => 'myplugin_taxonomy',
                'option_name' => 'myplugin_taxonomy',
                'callback' => array($this->taxonomy_callbacks, 'taxonomySanitize')
            )
        );

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = array(
            array(
                'id' => 'myplugin_taxonomy_index',
                'title' => 'Custom Taxonomy Manager',
                'page' => 'myplugin_taxonomy'
            )
        );

        $this->settings->setSections($args);
    }

    public function setFields()
    {
        $args = array(
            array(
                'id' => 'taxonomy',
                'title' => 'Taxonomy',
                'callback' => array($this->taxonomy_callbacks, 'textField'),
                'page' => 'myplugin_taxonomy',
                'section' => 'myplugin_taxonomy_index',
                'args' => array(
                    'option_name' => 'myplugin_taxonomy',
                    'label' => 'taxonomy',
        		),
            ),
            array(
                'id' => 'singular_name',
                'title' => 'Singlar Name',
                'callback' => array($this->taxonomy_callbacks, 'textField'),
                'page' => 'myplugin_taxonomy',
                'section' => 'myplugin_taxonomy_index',
                'args' => array(
                    'option_name' => 'myplugin_taxonomy',
                    'label' => 'singular_name',
        		),
            ),
            array(
                'id' => 'hierarchical',
                'title' => 'Hierarchical',
                'callback' => array($this->taxonomy_callbacks, 'cheakboxField'),
                'page' => 'myplugin_taxonomy',
                'section' => 'myplugin_taxonomy_index',
                'args' => array(
                    'option_name' => 'myplugin_taxonomy',
                    'label' => 'hierarchical',
                    'classes' => 'ui-toggle'
        		),
            ),
            array(
                'id' => 'objects',
                'title' => 'Post types',
                'callback' => array($this->taxonomy_callbacks, 'cheakboxPostTypeField'),
                'page' => 'myplugin_taxonomy',
                'section' => 'myplugin_taxonomy_index',
                'args' => array(
                    'option_name' => 'myplugin_taxonomy',
                    'label' => 'objects',
                    'classes' => 'ui-toggle'
        		),
            ),
        );

        $this->settings->setFields($args);
    }

    public function store()
    {
        $options = get_option('myplugin_taxonomy') ?: array();

        foreach($options as $option) {
            $labels = array(
                'name'              => _x( $option['taxonomy'], 'taxonomy general name' ),
                'singular_name'     => _x( $option['singular_name'], 'taxonomy singular name' ),
                'search_items'      => __( "Search {$option['taxonomy']}" ),
                "all_items"         => __( "All {$option['taxonomy']}" ),
                "parent_item"       => __( "Parent {$option['singular_name']}" ),
                "parent_item_colon" => __( "Parent {$option['singular_name']}:" ),
                "edit_item"         => __( "Edit {$option['singular_name']}" ),
                "update_item"       => __( "Update {$option['singular_name']}" ),
                "add_new_item"      => __( "Add New {$option['singular_name']}" ),
                "new_item_name"     => __( "New {$option['singular_name']} Name" ),
                "menu_name"         => __( "{$option['singular_name']}" ),
            );

            $this->taxonomies[]   = array(
                'hierarchical'      => isset($option['hierarchical']) ?: false, // make it hierarchical (like categories)
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => $option['taxonomy'] ],
                'objects'           => isset($option['objects']) ?  $option['objects']: null
            );
        }
    }

    public function registerCustomTaxonomy()
    {
        foreach($this->taxonomies as $taxonomy) {
            $objexts = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;
            register_taxonomy(
                $taxonomy['rewrite']['slug'],
                $objexts,
                $taxonomy
            );
        }
    }
}
