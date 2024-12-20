<style>
.container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; 
}

.column {
    flex: 1 1 45%;
    max-width: 500px; 
    text-align: center;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto; 
    transition: transform 0.3s ease-in-out; 
}

img:hover {
    transform: scale(1.1);
}

.text-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .column {
        flex: 1 1 100%;
    }

    .text-container {
        padding: 15px;
    }
}

</style>

<div>
    <p>Replikacja wirusa następuje</p>
</div>

<?php
$scheme = $_SERVER['REQUEST_SCHEME']; 
$host = $_SERVER['HTTP_HOST']; 
$url = $scheme . '://' . $host . $_SERVER['REQUEST_URI'];  

$lastSlashPos = strrpos($url, '/');
$cleaned_url = substr($url, 0, $lastSlashPos);
$path = $cleaned_url . "/text-img/";
?>

<div class="container">
    <div class="column">
        <img src="<?php echo $path; ?>tx1-1.png" alt="Virus replication method 1">
        <p>Wirus rozprzestrzenia się przez kliknięcie w odpowiedni link. Prawdopodobnie boty przechowują informacje o lokalizacjach, w których mogą wystąpić mechanizmy replikacji wirusa, i wchodzą w nie automatycznie.</p>
    </div>
    <div class="column">
        <img src="<?php echo $path; ?>tx1-2.png" alt="Virus replication method 2">
        <p>Wirus może również kopiować się poprzez dodawanie specjalnych instrukcji do nagłówków plików. W wyniku tego, przy każdym wywołaniu strony, wirus jest replikowany.</p>
    </div>
</div>

