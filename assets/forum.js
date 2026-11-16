document.querySelectorAll('[data-count-for]').forEach((counter) => {
    const field = document.getElementById(counter.dataset.countFor);
    if (!field) return;

    const updateCount = () => {
        counter.textContent = String(field.value.length);
    };
    field.addEventListener('input', updateCount);
    updateCount();
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

const postSearch = document.querySelector('#post-search');
if (postSearch) {
    const posts = [...document.querySelectorAll('#post-list .post-card')];
    const emptyMessage = document.querySelector('#no-search-results');

    postSearch.addEventListener('input', () => {
        const query = postSearch.value.trim().toLocaleLowerCase('en');
        let visible = 0;
        posts.forEach((post) => {
            const matches = post.dataset.searchable.includes(query);
            post.hidden = !matches;
            if (matches) visible += 1;
        });
        emptyMessage.hidden = visible !== 0;
    });
}

const adminSearch = document.querySelector('#admin-post-search');
if (adminSearch) {
    const posts = [...document.querySelectorAll('#admin-post-list .moderation-card')];
    const emptyMessage = document.querySelector('#admin-no-results');

    adminSearch.addEventListener('input', () => {
        const query = adminSearch.value.trim().toLocaleLowerCase('en');
        let visible = 0;
        posts.forEach((post) => {
            const matches = post.dataset.searchable.includes(query);
            post.hidden = !matches;
            if (matches) visible += 1;
        });
        emptyMessage.hidden = visible !== 0;
    });
}
