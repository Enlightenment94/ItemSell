<?php
if (!is_user_logged_in()) {
    wp_redirect(home_url('/page-login/'));
    exit;
}

$user_id = get_current_user_id();
$customer_orders = wc_get_orders(array(
    'customer' => $user_id,
    'status' => array('completed', 'processing'),
    'orderby' => 'date',
    'order' => 'DESC'
));

$product_found_and_paid = false;
$order_completed = false;
$product_id = 75;

foreach ($customer_orders as $order) {
    foreach ($order->get_items() as $item) {
        if ($item->get_product_id() == $product_id) {
            $product_found_and_paid = true;
            
            if ($order->get_status() === 'processing') {
                $order->update_status('completed');
                $order_completed = true;
            } elseif ($order->get_status() === 'completed') {
                $order_completed = true;
            }
            break;
        }
    }
    if ($product_found_and_paid && $order_completed) {
        break;
    }
}

echo "<center>";
if (!$product_found_and_paid) {
    echo "Not paid for product ID " . $product_id . ".";
    wp_safe_redirect(site_url("/page-checkout/"));
    exit();
} else {
    if ($order_completed) {
        echo "Product ID " . $product_id . " has been paid and the order is completed.";
    } else {
        echo "Product ID " . $product_id . " is completed first access.";
    }
}
echo "</center>";

$episodes = [
    [
        'file' => '1-Replikacja-wirusa.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx1.php'),
        'title' => 'Replikacja wirusa'
    ],
    [
        'file' => '2-Skanowanie-wirusa.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx2.php'),
        'title' => 'Skanowanie wirusa'
    ],
    [
        'file' => '3-Czyszczenie-rdzenia.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx3.php'),
        'title' => 'Wyczyszczenie core'
    ],
    [
        'file' => '4-Oczyszczanie-wtyczek.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx4.php'),
        'title' => 'Oczyszczanie plugins'
    ],
    [
        'file' => '5-Oczyszczanie-tematu.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx5.php'),
        'title' => 'Oczyszczanie tematu'
    ],
    [
        'file' => '6-Uploads.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx6.php'),
        'title' => 'Czyszczenie uploads'
    ],
    [
        'file' => '7-Uprawnienia.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx7.php'),
        'title' => 'Uprawnienia'
    ],
    [
        'file' => '8-Weryfikacja-czystosci-malcare.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx8.php'),
        'title' => 'Weryfikacja czystosci - malcare'
    ],
    [
        'file' => '10-baza.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx10.php'),
        'title' => 'Czyszczenie bazy danych'
    ],
    [
        'file' => '9-Zabezpieczanie-strony.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx9.php'),
        'title' => 'Zabezpieczanie strony'
    ],    
    [
        'file' => '11-Skanowanie-wirusów-ai.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx11.php'),
        'title' => 'Skanowanie wirusów ai'
    ],
    [
        'file' => '12-Wkrywanie-losowych-nazw.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx12.php'),
        'title' => 'Wkrywanie losowych nazw'
    ],
    [
        'file' => '13-3.5-Gpt-scanner change detector.m3u8',
        'text' =>  file_get_contents(get_template_directory_uri() . '/text/tx13.php'),
        'title' => '3.5 Gpt scanner change detector'
    ],
];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurs wideo</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <div id='dashboard'>
        <div id='col-left'>
            <ul>
                <?php
                foreach ($episodes as $key => $episode) {
                    echo "<li><a href='#' class='episode-link' data-episode-index='{$key}'>Odcinek " . ($key + 1) . ": {$episode['title']}</a></li>";
                }
                ?>
            </ul>
        </div>
        <div id='screen'>
            <video id="video-screen" controls></video>
        </div>
        <div id='text-bottom'>
            <p id="episode-text"></p>
        </div>
    </div>

    <script>
        const videoScreen = document.getElementById('video-screen');
        const episodeText = document.getElementById('episode-text');
        const episodes = <?php echo json_encode($episodes); ?>;
        let hls = null;

        function loadEpisode(index) {
            const episode = episodes[index];
            const videoUrl = `<?php echo get_template_directory_uri(); ?>/videos/${episode.file}`;
            
            if (hls) {
                hls.destroy();
            }

            if (Hls.isSupported()) {
                hls = new Hls({
                    startLevel: -1,
                    liveSyncDurationCount: 3,
                    loader: Hls.DefaultConfig.loader
                });

                hls.loadSource(videoUrl);
                hls.attachMedia(videoScreen);
                
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    videoScreen.play().catch(function(error) {
                        console.log("Autoplay prevented:", error);
                    });
                });

                hls.on(Hls.Events.ERROR, function(event, data) {
                    console.error('HLS Error:', data);
                });
            } else if (videoScreen.canPlayType('application/vnd.apple.mpegurl')) {
                videoScreen.src = videoUrl;
            }

            episodeText.innerHTML = episode.text;
        }

        document.querySelectorAll('.episode-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const index = parseInt(this.getAttribute('data-episode-index'));
                loadEpisode(index);
                
                document.querySelectorAll('.episode-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        loadEpisode(0);
        document.querySelector('.episode-link').classList.add('active');
    </script>
</body>
</html>

