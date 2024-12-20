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
    <p>Do stworzenia własnego antywirusa do strony możemy zaimplementować prosty "change detector" + nasz skaner z poprzedniego odcinka działa na zasadzie, że tworzy dwie listy i porównuje liste starą z nową i w przypadku pojawienia się nowego pliku automatycznie doknonano by skanu i zapisano wyników, a kod skanujący i listujący zmiany wykonywał by się automatycznie w crontab.</p>
</div>
