<style>
.container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.column {
    flex: 1 1 45%;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

.text-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

pre{
    max-width: 1000px;
    white-space: pre-wrap;
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

<div class="text-container">
    <p><pre>Pierwsze polecenie wyszukuje w folderze uploads wszystkie nieporządane pliki z nieporządanymi roszerzeniami, a drugie robi to samo tylko odrazu je usuwa.

find ./* -type f \( -name "*.bin" -o -name "*.php" -o -name "*.js" -o  -name "*.ott" -o  -name "*.oti" \)
find ./* -type f \( -name "*.bin" -o -name "*.php" -o -name "*.js" -o  -name "*.ott" -o  -name "*.oti" \) -exec rm {} \;</pre></p>
</div>