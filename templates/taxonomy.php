<div class="wrap">
    <h1>Manage your custom custom Taxonomys</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?= ! isset($_POST['edit_taxonomy']) ? 'active' : '' ?>"><a href="#tab-1">taxonomys</a></li>
        <li class="<?= isset($_POST['edit_taxonomy']) ? 'active' : '' ?>">
            <a href="#tab-2"><?= isset($_POST['edit_taxonomy']) ? 'Edit' : 'Add new' ?></a>
        </li>
        <li><a href="#tab-3">export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?= ! isset($_POST['edit_taxonomy']) ? 'active' : '' ?>">
            <h2>List of Taxonomys</h2>
            <table class="cpt-table">
                <tr>
                    <th>name</th>
                    <th>singular name</th>
                    <th>action</th>
                </tr>
                <?php foreach (get_option('myplugin_taxonomy') as $option) : ?>
                    <tr>
                        <td><?= $option['taxonomy'] ?></td>
                        <td><?= $option['singular_name'] ?></td>
                        <td>
                            <form method="POST" action="" class="inline-block">
                                <input type="hidden" name="edit_taxonomy" value="<?= $option['taxonomy']; ?>">
                                <?php
                                    submit_button('Edit', 'primary small', 'submit', false);
                                ?>
                            </form>
                            <form method="POST" action="options.php" class="inline-block">
                                <input type="hidden" name="remove" value="<?= $option['taxonomy']; ?>">
                                <?php
                                    settings_fields('myplugin_taxonomy');
                                    submit_button('Delete', 'secondary small', 'submit', false, array(
                                        'onclick' => "return confirm('are you shure to delete taxonomy')"
                                    ));
                                ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="tab-2" class="tab-pane <?= isset($_POST['edit_taxonomy']) ? 'active' : '' ?>">
            <h4>Add new custom post tyep</h4>
            <form action="options.php" method="POST">
                <?php
                    settings_fields('myplugin_taxonomy');
                    do_settings_sections('myplugin_taxonomy');
                    submit_button();
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3>Export</h3>
        </div>
    </div>
</div>