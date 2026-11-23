const navigationToggle = document.querySelector('.nav-toggle');
const navigation = document.querySelector('.main-navigation');

if (navigationToggle && navigation) {
    navigationToggle.addEventListener('click', () => {
        const expanded = navigationToggle.getAttribute('aria-expanded') === 'true';
        navigationToggle.setAttribute('aria-expanded', String(!expanded));
        navigation.classList.toggle('is-open', !expanded);
    });

    navigation.addEventListener('click', (event) => {
        if (event.target instanceof HTMLAnchorElement) {
            navigation.classList.remove('is-open');
            navigationToggle.setAttribute('aria-expanded', 'false');
        }
    });
}

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
