<?php

namespace Inc\Base;

class AuthController extends BaseController
{
    public function register()
    {
        if (!$this->activated('ajax_login')) {
            return;
        }
        
        add_action('wp_head', [$this, 'wp_auth_template']);
    }

    public function wp_auth_template()
    {
        $file = $this->plugin_path . 'templates/auth.php';

        if (file_exists($file)) {
            load_template($file);
        }
    }
}