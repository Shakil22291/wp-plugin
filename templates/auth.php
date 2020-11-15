<form 
    action="#"
    method="POST"
    id="myplugin-auth-form"
    data-url="<?= admin_url('admin-ajax.php') ?>"
>
    <div class="auth-btn">
        <input type="button" value="login" id="mypluging-show-auth-form">
    </div>

    <div class="auth-form-wrapper">
        <div id="myplugin-auth-container" class="auth-container">
            <a id="myplugin-auth-close" class="close" href="#">&times;</a>
            <h2>Site Login</h2>
            <label for="username">Username</label>
            <input id="username" type="text" name="username">
            <label for="password">Password</label>
            <input id="password" type="password" name="password">
            <input class="submit_button" type="submit" value="Login" name="submit">
            <p class="status" data-message="status">This is the message</p>
    
            <p class="actions">
                <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a> - <a href="<?php echo wp_registration_url(); ?>">Register</a>
            </p>
    
            <input type="hidden" name="action" value="myplugin_login">
            <?php wp_nonce_field( 'ajax-login-nonce', 'myplugin_auth' ); ?>
        </div>
    </div>
</form>