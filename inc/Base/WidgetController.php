<?php

namespace Inc\Base;

class WidgetController extends BaseController
{
    /**
     * register the widget
     *
     * @return void
     */
    public function register()
    {
        if (!$this->activated('media_widget')) {
            return;
        }

        $media_widget = new MediaWidget();
        $media_widget->register();
    }
}
