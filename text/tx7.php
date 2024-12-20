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
<pre>Przywracanie domyślnych uprawnień pierwsza komenda dla plików druga dla katalogów wchodzimy na czubek gałęzi od której chcemy żeby wykonało się w dół:
    
find ./ -type f -exec chmod 644 {} \;
find ./ -type d -exec chmod 755 {} \;
</pre>
</div>