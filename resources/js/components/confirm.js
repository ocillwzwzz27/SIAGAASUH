/**
 * Konfirmasi sebelum aksi berbahaya, tanpa onclick/onsubmit inline.
 *
 *   <form data-confirm="Hapus data ini?"> ... </form>
 *   <button type="submit" data-confirm="Yakin?">...</button>
 */
document.addEventListener('submit', (event) => {
    const form = event.target;

    if (form.matches?.('form[data-confirm]') && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
    }
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('button[data-confirm]');

    if (button && !window.confirm(button.dataset.confirm)) {
        event.preventDefault();
    }
});
