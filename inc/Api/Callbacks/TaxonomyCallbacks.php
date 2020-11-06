<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class TaxonomyCallbacks extends BaseController
{
    public function taxonomySanitize( $input )
    {
        $output = get_option('myplugin_taxonomy');

        if(isset($_POST['remove'])) {
            unset($output[$_POST['remove']]);

            return $output;
        }

        if (count($output) == 0) {
            $output[$input['taxonomy']] = $input;

            return $output;
        }

        foreach ($output as $key => $value) {
            if ($input['taxonomy'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['taxonomy']] = $input;
            }
        }

        return $output;
    }

    public function textField($args)
    {
        $option_name = $args['option_name'];
        $option = get_option($option_name);
        $name = $option_name . "[" . $args['label'] . "]";
        $value = isset($_POST['edit_taxonomy']) ? $option[$_POST['edit_taxonomy']][$args['label']] : '';

        echo "
            <input
                type='text'
                id='{$args['label']}'
                class='regular-text'
                value='{$value}'
                name='{$name}'
                required
            >
        ";
    }

    public function cheakboxField($args)
    {
        $option_name = $args['option_name'];
        $option = get_option($option_name);
        $name = $option_name . "[" . $args['label'] . "]";
        $checked = '';

        if(isset($_POST['edit_taxonomy'])) {
            $checked = isset($option[$_POST['edit_taxonomy']][$args['label']]) ?: false;
        }
        $attrubutes = $checked ? 'checked' : '';

        echo "
            <div class='{$args['classes']}'>
                <input
                    type='checkbox'
                    name='{$name}'
                    id='{$name}'
                    value='1'
                    {$attrubutes}
                >
                <label for='{$name}'><div></div></label>
            </div>
        ";
    }

    public function cheakboxPostTypeField($args)
    {
        $option_name = $args['option_name'];
        $option = get_option($option_name);
        $checked = '';


        $post_types = get_post_types(array('show_ui' => true));

        foreach($post_types as $post) {

            if(isset($_POST['edit_taxonomy'])) {
                $checked = isset($option[$_POST['edit_taxonomy']][$args['label']][$post]) ?: false;
            }
            $attrubutes = $checked ? 'checked' : '';

            $name = $option_name . "[" . $args['label'] . "][" . $post . "]";

            echo "
                <div
                    class='{$args['classes']}'
                    style='margin-bottom: 15px;'
                >
                    <input
                        type='checkbox'
                        name='{$name}'
                        id='{$name}'
                        value='1'
                        {$attrubutes}
                    >
                    <label for='{$name}'><div></div></label>
                    <strong>{$post}</strong>
                </div>
            ";
        }
    }
}
