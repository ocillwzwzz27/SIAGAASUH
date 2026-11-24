/** Halaman admin: verifikasi pengasuh (setujui / tolak dengan alasan). */
const form = document.getElementById('verifyForm');

if (form) {
    const actionInput = document.getElementById('actionInput');
    const reasonBox = document.getElementById('alasanContainer');

    const submitWith = (action) => {
        actionInput.value = action;
        form.submit();
    };

    document.querySelector('[data-verify="approve"]')?.addEventListener('click', () => {
        submitWith('terverifikasi');
    });

    document.querySelector('[data-verify="reject"]')?.addEventListener('click', () => {
        if (reasonBox.classList.contains('hidden')) {
            reasonBox.classList.remove('hidden');
            actionInput.value = 'ditolak';
        } else {
            form.submit();
        }
    });
}
