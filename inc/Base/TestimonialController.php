<?php

namespace Inc\Base;

class TestimonialController extends BaseController
{
    public function register()
    {
        if (!$this->activated('testimonial_manager')) {
            return;
        }

        add_action('init', [$this, 'testimonial_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_box']);
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
        wp_nonce_field('myplugin_testimonial_author', 'myplugin_testimonila_author_nonce');

        $value = get_post_meta($post->ID, '_myplugin_testimonial_author_key', true); ?>

            <label for="myplugin_testimonial_author">Testimnial Author</label>
            <input
                type="text"
                name="myplugin_testimonial_author"
                id="myplugin_testimonial_author"
                class="widefat"
                value="<?= esc_attr($value); ?>"
            >

        <?php
    }

    public function save_meta_box($post_id)
    {
        if ( ! isset($_POST['myplugin_testimonila_author_nonce'])) {
            return $post_id;
        }
        $nonce = $_POST['myplugin_testimonila_author_nonce'];

        if(! wp_verify_nonce($nonce, 'myplugin_testimonial_author')) {
            return $post_id;
        }

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if( ! current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = sanitize_text_field($_POST['myplugin_testimonial_author']);

        update_post_meta($post_id, '_myplugin_testimonial_author_key', $data);
    }
}
