(function () {
    var button = document.querySelector('.menu');
    var nav = document.getElementById('site-nav');
    if (!button || !nav) {
        return;
    }

    function setOpen(open) {
        nav.classList.toggle('open', open);
        button.setAttribute('aria-expanded', String(open));
        button.textContent = open ? '✕' : '☰';
    }

    button.addEventListener('click', function () {
        setOpen(!nav.classList.contains('open'));
    });

    nav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            setOpen(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && nav.classList.contains('open')) {
            setOpen(false);
            button.focus();
        }
    });
})();
