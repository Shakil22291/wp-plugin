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

        if(isset($_POST['remove'])) {
            unset($output[$_POST['remove']]);

            return $output;
        }

        if (count($output) == 0) {
            $output[$input['post_type']] = $input;

            return $output;
        }

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
                required
            >
        ";
    }
}
