// Drawer logic for parent layout
document.addEventListener('DOMContentLoaded', () => {
    const drawer = document.getElementById('account-drawer');
    const backdrop = document.getElementById('account-backdrop');
    const btnTop = document.getElementById('btn-toggle-account');
    const btnBottom = document.getElementById('btn-bottom-account');
    const btnClose = document.getElementById('btn-close-account');

    function openDrawer() {
        if (drawer) drawer.classList.remove('hidden');
    }

    function closeDrawer() {
        if (drawer) drawer.classList.add('hidden');
    }

    if (btnTop) btnTop.addEventListener('click', openDrawer);
    if (btnBottom) btnBottom.addEventListener('click', openDrawer);
    if (btnClose) btnClose.addEventListener('click', closeDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);
});
