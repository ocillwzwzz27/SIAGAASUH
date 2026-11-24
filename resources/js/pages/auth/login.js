/** Halaman login: tampil/sembunyikan password & isi cepat akun demo. */
const password = document.getElementById('pwd');
const email = document.querySelector('[name="email"]');
const eyeIcon = document.getElementById('eyeIcon');

document.getElementById('togglePassword')?.addEventListener('click', () => {
    const hidden = password.type === 'password';
    password.type = hidden ? 'text' : 'password';
    eyeIcon.className = hidden ? 'fas fa-eye-slash' : 'fas fa-eye';
});

document.querySelectorAll('[data-demo-email]').forEach((button) => {
    button.addEventListener('click', () => {
        email.value = button.dataset.demoEmail;
        password.value = button.dataset.demoPassword;
    });
});
