<div class="wrap">
    <h1>Manage your custom post type</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">post types</a></li>
        <li><a href="#tab-2">add new</a></li>
        <li><a href="#tab-3">export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <h2>List of post tyepe</h2>
        </div>

        <div id="tab-2" class="tab-pane">
            <h4>Add new custom post tyep</h4>
            <form action="options.php" method="POST">
                <?php
                settings_fields('myplugin_cpt');
                do_settings_sections('myplugin_cpt');
                submit_button();
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3>Export</h3>
        </div>
    </div>
</div>