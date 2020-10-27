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
        return isset($input) ? true : false;
    }

    public function adminSectionManager()
    {
        echo "Mange the feature of this plugin";
    }

    public function cheakboxField($args)
    {
        $option = get_option($args['label_for']);
        $attribues = $option ? 'checked' : '';

        echo "
            <input
                type='checkbox'
                name='{$args['label_for']}'
                class='{$args['classes']}'
                value='{$option}'
                {$attribues}
            >
        ";
    }
}
