<?php

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\TestimonialCallbacks;

class TestimonialController extends BaseController
{
    public $settings;
    public $callbacks;

    public function register()
    {
        if (!$this->activated('testimonial_manager')) {
            return;
        }

        $this->settings  = new SettingsApi();
        $this->callbacks = new TestimonialCallbacks();

        add_action('init', [$this, 'testimonial_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_box']);
        add_action('manage_testimonial_posts_columns', [$this, 'set_custom_columns']);
        add_action('manage_testimonial_posts_custom_column', [$this, 'set_custom_columns_data'], 10, 2);
        add_action('manage_edit-testimonial_sortable_columns', [$this, 'set_custom_columns_sortable']);

        $this->setShortCodePage();

        add_shortcode('testimonial-slider', [$this, 'testimonial_slideshow']);
        add_shortcode('testimonial-form', [$this, 'testimonial_form']);
        add_action('wp_ajax_submit_testimonial', [$this, 'submit_testimonial']);
        add_action('wp_ajax_nopriv_submit_testimonial', [$this, 'submit_testimonial']);
    }

    public function submit_testimonial()
    {
        if ( ! DOING_AJAX || ! check_ajax_referer('testimonial_nonce', 'nonce')) {
            wp_send_json(['status' => 'error']); 
            wp_die();
        }

        $name    = sanitize_text_field($_POST['name']);
        $email   = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        $data = [
            'name'     => $name,
            'email'    => $email,
            'approved' => 0,
            'featured' => 0,
        ];

        $args = [
            'post_title'   => 'Testimonial from ' . $name,
            'post_content' => $message,
            'post_author'  => 1,
            'post_status'  => 'publish',
            'post_type'    => 'testimonial',
            'meta_input'   => [
                '_myplugin_testimonial_key' => $data,
            ],
        ];

        $postID = wp_insert_post($args);

        if ($postID) {
            $return = [
                'status' => 'success',
                'ID'     => $postID,
            ];
            wp_send_json($return);
            wp_die();
        }

        wp_send_json(['status' => 'error']);

        wp_die();
    }

    public function setShortCodePage()
    {
        $subpage = [
            [
                'parent_slug' => 'edit.php?post_type=testimonial',
                'page_title'  => 'Shortcodes',
                'menu_title'  => 'ShortCodes',
                'capability'  => 'manage_options',
                'menu_slug'   => 'myplugin_testimonila_shortcode',
                'callback'    => [$this->callbacks, 'shortCodePage'],
            ],
        ];

        $this->settings->addSubPages($subpage)->register();
    }

    public function testimonial_form()
    {
        ob_start();
        echo "<link rel='stylesheet' href='{$this->plugin_url}/assets/form.css' type='text/css' media='all' />";
        require_once "$this->plugin_path/templates/contact-form.php";
        echo "<script src='{$this->plugin_url}/assets/form.js'></script>";

        return ob_get_clean();
    }
    public function testimonial_slideshow()
    {
        ob_start();
        echo "<link rel='stylesheet' href='{$this->plugin_url}/assets/slider.css' type='text/css' media='all' />";
        require_once "$this->plugin_path/templates/slider.php";
        echo "<script src='{$this->plugin_url}/assets/slider.js'></script>";

        return ob_get_clean();
    }

    /**
     * register the post type
     *
     * @return void
     */
    public function testimonial_cpt()
    {
        $labels = [
            'name'          => 'Testimonials',
            'singular_name' => 'Testimonial',
        ];
        $args = [
            'labels'              => $labels,
            'menu_icon'           => 'dashicons-testimonial',
            'public'              => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'supports'            => ['title', 'editor'],
            'show_in_rest' => true
        ];
        register_post_type('testimonial', $args);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'tetimonila_author',
            'Author',
            [$this, 'render_author_box'],
            'testimonial',
            'side',
            'default'
        );
    }

    public function render_author_box($post)
    {
        wp_nonce_field('myplugin_testimonial', 'myplugin_testimonila_nonce');

        $data     = get_post_meta($post->ID, '_myplugin_testimonial_key', true);
        $name     = isset($data['name']) ? $data['name'] : '';
        $email    = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false; ?>

            <p>
                <label class="meta-label" for="myplugin_testimonial_author">Testimnial Author</label>
                <input
                    type="text"
                    name="myplugin_testimonial_author"
                    id="myplugin_testimonial_author"
                    class="widefat"
                    value="<?= esc_attr($name); ?>"
                >
            </p>

            <p>
                <label class="meta-label" for="myplugin_testimonial_email">Author Email</label>
                <input
                    type="email"
                    id="myplugin_testimonial_email"
                    name="myplugin_testimonial_email"
                    class="widefat"
                    value="<?php echo esc_attr($email); ?>"
                >
            </p>

            <div class="meta-container" style="margin-bottom: 5px;">
                <label class="meta-label w-50 text-left" for="myplugin_testimonial_approved">Approved</label>
                <div class="text-right w-50 inline" style="display: inline-block;">
                    <div class="ui-toggle inline">
                        <input type="checkbox" id="myplugin_testimonial_approved" name="myplugin_testimonial_approved" value="1" <?= $approved ? 'checked' : ''; ?>>
                        <label for="myplugin_testimonial_approved"><div></div></label>
                    </div>
                </div>
            </div>

            <div class="meta-container">
                <label class="meta-label w-50 text-left" for="myplugin_testimonial_featured">Featured</label>
                <div class="text-right w-50 inline" style="display: inline-block;">
                    <div class="ui-toggle inline">
                        <input type="checkbox" id="myplugin_testimonial_featured" name="myplugin_testimonial_featured" value="1" <?= $featured ? 'checked' : ''; ?>>
                        <label for="myplugin_testimonial_featured"><div></div></label>
                    </div>
                </div>
            </div>

        <?php
    }

    public function save_meta_box($post_id)
    {
        if (!isset($_POST['myplugin_testimonila_nonce'])) {
            return $post_id;
        }
        $nonce = $_POST['myplugin_testimonila_nonce'];

        if (!wp_verify_nonce($nonce, 'myplugin_testimonial')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = [
            'name'     => sanitize_text_field($_POST['myplugin_testimonial_author']),
            'email'    => sanitize_email($_POST['myplugin_testimonial_email']),
            'approved' => isset($_POST['myplugin_testimonial_approved']) ? 1 : 0,
            'featured' => isset($_POST['myplugin_testimonial_featured']) ? 1 : 0,
        ];

        update_post_meta($post_id, '_myplugin_testimonial_key', $data);
    }

    public function set_custom_columns($columns)
    {
        $title = $columns['title'];
        $date  = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['name']     = 'Autor Name';
        $columns['title']    = $title;
        $columns['approved'] = 'Approved';
        $columns['featured'] = 'Featured';

        return $columns;
    }

    public function set_custom_columns_data($column, $post_id)
    {
        $data     = get_post_meta($post_id, '_myplugin_testimonial_key', true);

        $name     = isset($data['name']) ? $data['name'] : '';
        $email    = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<stong>YES</stong>' : '<stong>NO</stong>';
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<stong>YES</stong>' : '<stong>NO</stong>';

        switch ($column) {
            case 'name':
                echo "
                    <strong>$name</strong><br/>
                    <a href='mailto:{$email}'>$email</a>
                ";
                break;

            case 'approved':
                echo $approved;
                break;

            case 'featured':
                echo $featured;
            break;
        }
    }

    public function set_custom_columns_sortable($columns)
    {
        $columns['name']     = 'name';
        $columns['approved'] = 'approved';
        $columns['featured'] = 'featured';

        return $columns;
    }
}
