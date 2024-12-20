<?php

if ( is_user_logged_in() ) {
    //wp_redirect( home_url( '/page-dashboard' ));
    wp_redirect( home_url( '/page-checkout/' ));
    exit;
}


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
    if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
        $username = sanitize_text_field( $_POST['login'] );
        $password = sanitize_text_field( $_POST['password'] );

        $user = wp_authenticate( $username, $password );

        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => false,
        );

        $user = wp_signon( $creds, false );

        if ( ! is_wp_error( $user ) ) {
            //wp_redirect( home_url( '/page-dashboard' ) );
            wp_redirect( home_url( '/page-checkout/' ) );
            exit;
        } else {
            echo '<p class="error">Błędny login lub hasło.</p>';
        }
    }
}
?>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

<div id="main-content">
    <img src="<?php echo get_template_directory_uri(); ?>/logo.webp" alt="Logo" style="display: block; margin: 0 auto; width: 150px;" />
    
    <form method="POST" action="" id="login-form">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required />

        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required />

        <div class="h-captcha" data-sitekey="eaca6420-2bb8-469e-a150-1eac51720676"></div>

        <input type="submit" value="Zaloguj" />
    </form>
    <a href="<?php echo site_url('/page-register/'); ?>">register</a>
    <a href="<?php echo site_url('/page-forgot/'); ?>">forgot password</a>
</div>
