<?php
$flag = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['h-captcha-response'])) {
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
        $flag = 1;
    } else {
        echo "Weryfikacja nieudana!";
    }
}

if ($flag == 1) {
    $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    $user_id = wp_create_user($username, $password, $email);
    
    if (!is_wp_error($user_id)) {
        wp_safe_redirect(  esc_url( home_url( '/page-login/' )) );
        exit;
    } else {
        echo 'Wystąpił błąd podczas rejestracji: ' . $user_id->get_error_message();
    }
}
?>

<!-- Formularz HTML do przesyłania danych przez użytkownika 
<form action="<?php echo esc_url(get_permalink()); ?>" method="post" id="register-payment-form">
    <?php wp_nonce_field('register_payment_form', 'register_payment_nonce'); ?>

    <h3>Rejestracja na kurs</h3>

    <label for="username">Nazwa użytkownika:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" required />

    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? esc_attr($_POST['password']) : ''; ?>" required />

    <label for="h-captcha-response">hCaptcha:</label>

    <input type="submit" value="Zapisz się" />
</form>

-->