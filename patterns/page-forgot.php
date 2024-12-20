<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secret = "ES_bb7c98ff935a46519a699817eadfb335";
    $hcaptcha_response = $_POST['h-captcha-response'];
    
    $data = array(
        'secret' => $secret,
        'response' => $hcaptcha_response
    );
    
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($verify);
    $responseData = json_decode($response);
    
    if ($responseData->success) {
        echo "Weryfikacja udana!";
        $flag = 1;
    } else {
        echo "Weryfikacja nieudana!";
    }
}

if($flag == 1){
    function generate_reset_token($user_id) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + 3600;
        update_user_meta($user_id, 'password_reset_token', $token);
        update_user_meta($user_id, 'password_reset_expiry', $expiry);
        return $token;
    }
    
    function verify_reset_token($user_id, $token) {
        $stored_token = get_user_meta($user_id, 'password_reset_token', true);
        $expiry = get_user_meta($user_id, 'password_reset_expiry', true);
        if ($stored_token && $stored_token === $token && time() < $expiry) {
            return true;
        }
        return false;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = sanitize_text_field($_POST['action']);
        
        if ($action === 'request_reset') {
            if (isset($_POST['email']) && is_email($_POST['email'])) {
                $email = sanitize_email($_POST['email']);
                
                if (email_exists($email)) {
                    $user = get_user_by('email', $email);
                    $reset_token = generate_reset_token($user->ID);
                    $reset_url = site_url("/page-reset/?token=$reset_token&user_id={$user->ID}");
                    
                    $subject = "Resetowanie hasła";
                    $message = "Cześć, {$user->user_login},\n\nKliknij poniższy link, aby zresetować hasło:\n\n$reset_url\n\nJeśli to nie Ty, zignoruj tę wiadomość.";
                    wp_mail($email, $subject, $message);
    
                    echo '<p class="success">Instrukcja resetowania hasła została wysłana na Twój e-mail.</p>';
                } else {
                    echo '<p class="error">Adres e-mail nie istnieje w naszej bazie danych.</p>';
                }
            }
        } elseif ($action === 'reset_password') {
            if (isset($_POST['password'], $_POST['token'], $_POST['user_id'])) {
                $user_id = intval($_POST['user_id']);
                $token = sanitize_text_field($_POST['token']);
                $password = sanitize_text_field($_POST['password']);
    
                if (verify_reset_token($user_id, $token)) {
                    wp_set_password($password, $user_id);
                    delete_user_meta($user_id, 'password_reset_token');
                    delete_user_meta($user_id, 'password_reset_expiry');
                    echo '<p class="success">Twoje hasło zostało zresetowane. Możesz się teraz zalogować.</p>';
                } else {
                    echo '<p class="error">Token resetujący jest nieprawidłowy lub wygasł.</p>';
                }
            }
        }
    }
}

?>

<div id="main-content">
    <img src="<?php echo get_template_directory_uri(); ?>/logo.webp" alt="Logo" style="display: block; margin: 0 auto; width: 150px;" />

    <form method="POST" action="" id="reset-password-form">
        <input type="hidden" name="action" value="request_reset" />

        <label for="email">Podaj swój adres e-mail:</label>
        <input type="email" name="email" id="email" required />

        <div class="h-captcha" data-sitekey="eaca6420-2bb8-469e-a150-1eac51720676"></div>

        <input type="submit" value="Resetuj hasło" />
    </form>

    <a href="<?php echo site_url('/page-login/'); ?>">Powrót do logowania</a>
</div>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
