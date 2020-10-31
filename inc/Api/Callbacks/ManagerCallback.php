<?php

/**
 * @package  AlecadddPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class ManagerCallback extends BaseController
{
    public function cheakboxSanitize($input)
    {
        $output = array();

        foreach ($this->managers as $key => $value) {
            $output[$key] = isset($input[$key]) ? true : false;
        }

        return $output;
    }

    public function adminSectionManager()
    {
        echo "Mange the feature of this plugin";
    }

    public function cheakboxField($args)
    {
        $option_name = $args['option_name'];
        $option = get_option($option_name);
        $name = $option_name . "[" . $args['label'] . "]";
        $attribues = $option[$args['label']] ? 'checked' : '';
        echo "
            <div class='{$args['classes']}'>
                <input
                    type='checkbox'
                    name='{$name}'
                    id='{$name}'
                    value='1'
                    {$attribues}
                >
                <label for='{$name}'><div></div></label>
            </div>
        ";
    }
}
