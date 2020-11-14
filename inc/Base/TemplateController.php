<?php

namespace Inc\Base;

class TemplateController extends BaseController
{
    public $templates;

    public function register()
    {
        if (!$this->activated('template_manager')) {
            return;
        }

        $this->templates = [
            'page-templates/two-column-tpl.php' => 'Two Columns Layout'
        ];

        add_filter('theme_page_templates', [$this, 'custom_templates']);
        add_filter('template_include', [$this, 'load_template']);
    }

    public function custom_templates($templates)
    {
        $templates = array_merge($templates, $this->templates);

        return $templates;
    }

    public function load_template($template)
    {
        global $post;

        if (!$post) {
            return $template;
        }

        // if is the front page load the custom template
        if (is_front_page()) {
            $file = $this->plugin_path . 'page-templates/front-page.php';
            if (file_exists($file)) {
                return $file;
            }
        }

        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        if (!isset($this->templates[$template_name])) {
            return $template;
        }

        $file = $this->plugin_path . $template_name;

        if (file_exists($file)) {
            return $file;
        }

        return $template;
    }
}
