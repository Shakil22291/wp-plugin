<?php

namespace Inc\Api\Widgets;

use WP_Widget;

class MediaWidget extends WP_Widget
{
    public $widget_ID;
    public $widget_name;
    public $widget_options  = [];
    public $control_options = [];

    public function __construct()
    {
        $this->widget_ID      = 'myplugin_media_widget';
        $this->widget_name    = 'My Media widget';
        $this->widget_options = [
            'classname'                   => $this->widget_ID,
            'description'                 => 'This is widget description',
            'customize_selective_refresh' => true,
        ];

        $this->control_options = [
            'width'  => '400px',
            'height' => '300px',
        ];
    }

    public function register()
    {
        parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options);

        add_action('widgets_init', [$this, 'widgetInit']);
    }

    public function widgetInit()
    {
        register_widget($this);
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo 'hello';
        if (!empty($instance['title'])) {
            echo $args['before_title'];
            echo $instance['title'];
            echo $args['after_title'];
        }
        if (!empty($instance['image'])) {
            echo "
                <img style='max-width: 100%;'  src='{$instance['image']}'>
            ";
        }
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title   = !empty($instance['title']) ? $instance['title'] : 'This is title';
        $titleID = esc_attr($this->get_field_id('title'));
        $image   = !empty($instance['image']) ? $instance['image'] : '';
        $imageID = esc_attr($this->get_field_id('image')); ?>

            <p>
                <label for="<?= $titleID ?>">Title</label><br>
                <input
                    type="text"
                    class="widefat"
                    id="<?= $titleID; ?>"
                    name="<?= esc_attr($this->get_field_name('title')); ?>"
                    value="<?= esc_attr($title); ?>"
                >
            </p>
            <p>
                <label for="<?= $imageID ?>">Imae</label><br>
                <input
                    type="text"
                    class="widefat image-upload"
                    id="<?= $imageID; ?>"
                    name="<?= esc_attr($this->get_field_name('image')); ?>"
                    value="<?= esc_url($image); ?>"
                >
                <button type="button" class="button button-primary js-image-upload">Choose image</button>
            </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['image'] = ! empty($new_instance['image']) ? $new_instance['image'] : '';

        return $instance;
    }
}
