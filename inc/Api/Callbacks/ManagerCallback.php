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
        $option = get_option($args['label']);
        $attribues = $option ? 'checked' : '';
        $name = $args['label'];
        echo "
            <div class='{$args['classes']}'>
                <input
                    type='checkbox'
                    name='{$name}'
                    value='{$option}'
                    id='{$name}'
                    {$attribues}
                >
                <label for='{$name}'><div></div></label>
            </div>
        ";
    }
}
