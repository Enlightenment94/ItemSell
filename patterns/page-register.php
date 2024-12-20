<?php
/*
Template Name: Simple Payment Page
https://enlightenment.xaa.pl/wp/course/page-login/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_template_part('patterns/header');

?>

<div id="service-description">
    <h1>Sprawdzony model bezpieczeństwa i odwirusowania stron CMS WordPress. Zapraszamy!</h1>

    <center>
        <img src="<?php echo get_template_directory_uri() . "/logo.webp"; ?>" width="200" height="200" />
    </center>

    <p class="intro">Za jedyne 17.50 zł otrzymasz kompaktowy kurs zawierający maksimum praktycznej wiedzy. Nauczysz się, jak skutecznie chronić swoją stronę WordPress przed ciągłymi atakami, nawet przy nieaktualizowanym motywie i wtyczkach. Zdobędziesz umiejętności niezbędne do rozwiązywania problemów z zawirusowanymi instalacjami najpopularniejszego systemu CMS. Kurs powstał w odpowiedzi na rosnące zapotrzebowanie na kompleksową ochronę stron WordPress przed współczesnymi zagrożeniami.</p>
    
    <div>
        <div></div>
        <div>
            <h2>Zakres usługi:</h2>
            <ol class="service-list">
                <li>Kopia - Backup</li>
                <li>Analiza replikacji wirusów w WordPress</li>
                <li>Profesjonalne skanowanie wirusów z użyciem VirusTotal</li>
                <li>Oczyszczanie rdzenia WordPress</li>
                <li>Oczyszczanie wtyczek</li>
                <li>Oczyszczanie motywu</li>
                <li>Czyszczenie katalogu uploads</li>
                <li>Prawidłowa konfiguracja uprawnień</li>
                <li>Weryfikacja skuteczności czyszczenia przez Malcare</li>
                <li>Czyszczenie bazy danych</li>
                <li>Zaawansowane zabezpieczenia - maskowanie wtyczek, motywów i sygnatur</li>
                <li>Budowanie własnego skanera w oparciu LLM - kod do pobrania</li>
            </ol>
        </div>
    </div>
    
    <p class="price">Cena: <strong>17.50 zł</strong></p>

    <div class='img-container'>
    <?php
    ini_set("display_errors", true);
    
    $folder_path = get_template_directory_uri() . '/img'; 
    $image_dir = get_template_directory() . '/img'; 
    
    if (is_dir($image_dir)) {
        $images = array_diff(scandir($image_dir), array('..', '.')); 
        $captions = ['Zaciemniony kod', "Szkodliwe nagłówki", "Złośliwe przekierowania"];
        
        $i = 0;
        foreach ($images as $index => $image) {
            $image_path = $folder_path . '/' . $image; 
            $caption = $captions[$i]; 
            
            echo '<div class="image-item">';
            echo '<img src="' . $image_path . '" alt="Image ' . ($index + 1) . '" style="max-width: 100%; height: auto;" />';
            echo '<p>' . htmlspecialchars($caption) . '</p>';
            echo '</div>';
            $i++;
        }
    } else {
        echo '<p>Folder z obrazkami nie istnieje.</p>';
    }
    ?>
    </div>
</div>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

<div id="main-content">
    <div class="form-container">
        <!-- Kolumna 1: Formularz rejestracji -->
        <div class="form-column">
            <form action="<?php echo esc_url( home_url( '/page-verify/' ) ); ?>" method="post" id="register-payment-form">
                <h3>Rejestracja na kurs</h3>

                <label for="username">Nazwa użytkownika:</label>
                <input type="text" id="username" name="username" required />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required />

                <div class="h-captcha" data-sitekey="eaca6420-2bb8-469e-a150-1eac51720676"></div>
                
                <input type="submit" value="Zapisz się" />
            </form>
            <a href='<?php echo esc_url( home_url( '/page-login/' ) ); ?>'>login</a>
        </div>

        <!-- Kolumna 2: Informacje o płatności -->
        <div class="info-column">
            <h3>Po rejestracji:</h3>
            <p>Po zakończeniu rejestracji zostaniesz przekierowany do panelu płatności, gdzie będziesz mógł opłacić kurs i uzyskać nielimitowany dostęp do jego zawartości.</p>
            <p>Kurs sprawi, że staniesz się prawdziwym czarodziejem wordpress.</p>
            <center>
                <img src="<?php echo get_template_directory_uri() . "/logo.webp"; ?>" width="200" height="200" />
            </center>
        </div>
    </div>
</div>

<div style='text-align: center;'>Środki z kursu przeznaczone są na rozwój enlsoftware - jeśli jesteś zaintersowany dalszym rozwojem kursu dodaj swą opinie.</div>

<div id="payment-info">
    <div class='wrapper-info'>
        <h2>Metody płatności:</h2>
        <p>Akceptujemy następujące metody płatności:</p>
        <ul>
            <li>credit / debet card</li>
            <li>Przelewy24</li>
            <li>Przelew manualny (po zatwierdzeniu)</li>
        </ul>
        
        <h2>W razie problemów z płatnością:</h2>
        <p>Jeśli napotkasz problem przy płatności prosimy o kontakt pod mail <b></b></p> 
        </br><strong>+48 516 569 287</strong>.</p>
    </div>
</div>

<?php
get_template_part('patterns/footer');
?>


