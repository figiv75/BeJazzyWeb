(function () {
    function setPreview(field, url) {
        var preview = field.querySelector('.bj-image-preview');
        if (url) {
            preview.src = url;
            preview.style.display = 'block';
        } else {
            preview.removeAttribute('src');
            preview.style.display = 'none';
        }
    }

    document.addEventListener('click', function (event) {
        var pick = event.target.closest('.bj-image-pick');
        var clear = event.target.closest('.bj-image-clear');
        if (!pick && !clear) {
            return;
        }
        var field = event.target.closest('.bj-image-field');
        var input = field.querySelector('.bj-image-id');

        if (clear) {
            input.value = '0';
            setPreview(field, field.getAttribute('data-default'));
            return;
        }

        var frame = wp.media({
            title: 'Izberi sliko',
            button: { text: 'Uporabi' },
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            input.value = attachment.id;
            var size = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium : attachment;
            setPreview(field, size.url);
        });
        frame.open();
    });
})();
