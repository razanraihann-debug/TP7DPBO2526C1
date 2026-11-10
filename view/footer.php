</main>
<script>
// Pencarian cepat pada tabel (client-side)
document.querySelectorAll('[data-filter]').forEach(function (input) {
    var table = document.getElementById(input.dataset.filter);
    input.addEventListener('input', function () {
        var q = input.value.toLowerCase();
        table.querySelectorAll('tbody tr[data-row]').forEach(function (tr) {
            tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
        });
    });
});
// Pesan notifikasi hilang otomatis
var flash = document.getElementById('flash');
if (flash) {
    setTimeout(function () { flash.style.opacity = '0'; flash.style.transform = 'translateY(-6px)'; }, 3500);
    setTimeout(function () { flash.remove(); }, 4000);
}
</script>
</body>
</html>
