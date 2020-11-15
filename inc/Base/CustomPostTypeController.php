<?php

namespace Inc\Base;

use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\CptCallbacks;
use Inc\Api\SettingsApi;

class CustomPostTypeController extends BaseController
{
    public $subpages = [];
    public $settings;
    public $callbacks;
    public $cptCallbacks;

    public $custom_post_types;

    public function register()
    {
        if (!$this->activated('cpt_manager')) {
            return;
        }

        $this->settings     = new SettingsApi();
        $this->cptCallbacks = new CptCallbacks();
        $this->callbacks    = new AdminCallbacks();

        $this->setSubpages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addSubPages($this->subpages)->register();

        add_action('init', [$this, 'registerCustomPostType']);
    }

    public function setSubpages()
    {
        $this->subpages = [
            [
                'parent_slug' => 'myplugin',
                'page_title'  => 'Custom Post Types',
                'menu_title'  => 'CPT',
                'capability'  => 'manage_options',
                'menu_slug'   => 'myplugin_cpt',
                'callback'    => [$this->callbacks, 'adminCpt']
            ],
        ];
    }

    public function registerCustomPostType()
    {
        $options = get_option('myplugin_cpt');

        foreach ($options as $option) {
            register_post_type(
                $option['post_type'],
                [
                    'labels' => [
                        'name'          => $option['post_type_name'],
                        'singular_name' => $option['singular_name']
                    ],
                    'public'          => true,
                    'has_archive'     => true,
                    'capability_type' => 'post',
                    'supports'        => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats'],
                    'show_in_rest'    => true,
                    'taxonomies'      => ['category', 'post_tag', 'newyaxonomy'],
                ]
            );
        }
    }

    public function setSettings()
    {
        $args = [
            [
                'option_group' => 'myplugin_cpt',
                'option_name'  => 'myplugin_cpt',
                'callback'     => [$this->cptCallbacks, 'cptSanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = [
            [
                'id'    => 'myplugin_cpt_index',
                'title' => 'Settings',
                'page'  => 'myplugin_cpt'
            ]
        ];

        $this->settings->setSections($args);
    }

    public function setFields()
    {
        $args = [
            [
                'id'       => 'post_type',
                'title'    => 'ID',
                'callback' => [$this->cptCallbacks, 'textField'],
                'page'     => 'myplugin_cpt',
                'section'  => 'myplugin_cpt_index',
                'args'     => [
                    'option_name' => 'myplugin_cpt',
                    'label'       => 'post_type',
                ],
            ],
            [
                'id'       => 'post_type_name',
                'title'    => 'Post type name',
                'callback' => [$this->cptCallbacks, 'textField'],
                'page'     => 'myplugin_cpt',
                'section'  => 'myplugin_cpt_index',
                'args'     => [
                    'option_name' => 'myplugin_cpt',
                    'label'       => 'post_type_name',
                ]
            ],
            [
                'id'       => 'singular_name',
                'title'    => 'Singular Name',
                'callback' => [$this->cptCallbacks, 'textField'],
                'page'     => 'myplugin_cpt',
                'section'  => 'myplugin_cpt_index',
                'args'     => [
                    'option_name' => 'myplugin_cpt',
                    'label'       => 'singular_name',
                ]
            ],
        ];

        $this->settings->setFields($args);
    }
}
