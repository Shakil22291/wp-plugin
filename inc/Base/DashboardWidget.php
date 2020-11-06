<?php

namespace Inc\Base;

use Inc\Api\Callbacks\DashboardWidgetCallbacks;

class DashboardWidget extends BaseController
{
    public $dash_widget_callbacks;

    public function register()
    {
        // $this->dash_widget_callbacks = new DashboardWidgetCallbacks();
        add_action('wp_dashboard_setup', array($this, 'registerWidget'));
    }

    public function registerWidget()
    {
        wp_add_dashboard_widget(
            'my_widget',
            'My Widget',
            array($this, 'myWidget')
        );
    }

    /**
     * Temporary function
     */
    public function myWidget()
    {
        return require_once("$this->plugin_path/templates/dashbordwidget.php");
    }
}