<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class CptCallbacks extends BaseController
{
    public function cptSanitize($input)
    {
        $output = get_option('myplugin_cpt');

        foreach ($output as $key => $value) {
            if ($input['post_type'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['post_type']] = $input;
            }
        }

        return $output;
    }

    public function textField($args)
    {
        $option_name = $args['option_name'];
        $option = get_option($option_name);
        $name = $option_name . "[" . $args['label'] . "]";

        echo "
            <input
                type='text'
                id='{$args['label']}'
                class='regular-text'
                name='{$name}'
            >
        ";
    }
}
