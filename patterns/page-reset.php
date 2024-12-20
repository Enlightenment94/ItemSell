<div id="main-content">
    <form method="POST" action="<?php echo esc_url(site_url('/page-forgot.php')); ?>">
        <input type="hidden" name="action" value="reset_password" />
        <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? esc_attr($_GET['token']) : ''; ?>" />
        <input type="hidden" name="user_id" value="<?php echo isset($_GET['user_id']) ? intval($_GET['user_id']) : ''; ?>" />

        <label for="password">Nowe hasło:</label>
        <input type="password" name="password" id="password" required />

        <div class="h-captcha" data-sitekey="eaca6420-2bb8-469e-a150-1eac51720676"></div>
        
        <input type="submit" value="Resetuj hasło" />
    </form>
</div>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

