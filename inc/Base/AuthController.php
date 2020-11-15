<?php

namespace Inc\Base;

class AuthController extends BaseController
{
    public function register()
    {
        if (!$this->activated('ajax_login')) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        // add_action('wp_head', [$this, 'wp_auth_template']);
        add_shortcode('myplugin-login-form', [$this, 'login_shortcode']);
        add_action('wp_ajax_nopriv_myplugin_login', [$this, 'login']);
    }

    public function login_shortcode()
    {
        if (is_user_logged_in()) {
            return;
        }
        require_once $this->plugin_path . 'templates/auth.php';
    }

    public function login()
    {
        check_ajax_referer('ajax-login-nonce', 'myplugin_auth');

        $info = [];

        $info['user_login']    = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember']      = true;

        $user_signon = wp_signon($info, false);

        if (is_wp_error($user_signon)) {
            echo json_encode(
                [
                    'status'  => false,
                    'message' => 'Wrong username and password'
                ]
            );

            die();
        }

        echo json_encode(
            [
                'status'  => true,
                'message' => 'Login Successful, redirecting..'
            ]
        );

        die();

    }

    public function wp_auth_template()
    {
        $file = $this->plugin_path . 'templates/auth.php';

        if (file_exists($file)) {
            load_template($file);
        }
    }

    public function enqueue()
    {
        wp_enqueue_style('authstyle', $this->plugin_url . 'assets/auth.css');
        wp_enqueue_script('authjs', $this->plugin_url . 'assets/auth.js');
    }
}
