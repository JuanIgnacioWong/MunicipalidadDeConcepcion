(function ($) {
    function updateFilename($item, attachment) {
        var filename = attachment && attachment.filename ? attachment.filename : '';
        $item.find('[data-doc-filename]').text(filename);
    }

    function bindItem($item) {
        $item.find('[data-doc-select]').off('click').on('click', function (e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Seleccionar documento',
                button: { text: 'Usar este documento' },
                multiple: false
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                if (!attachment) {
                    return;
                }
                $item.find('[data-doc-id]').val(attachment.id);
                var $label = $item.find('[data-doc-label]');
                if ($label.val().trim() === '') {
                    $label.val(attachment.title || attachment.filename || '');
                }
                updateFilename($item, attachment);
            });

            frame.open();
        });

        $item.find('[data-doc-remove]').off('click').on('click', function (e) {
            e.preventDefault();
            $item.remove();
        });
    }

    function refreshIndexes($container) {
        $container.find('[data-doc-item]').each(function (index) {
            var $item = $(this);
            $item.find('[data-doc-id]').attr('name', 'sitio_cero_concurso_documentos[' + index + '][id]');
            $item.find('[data-doc-label]').attr('name', 'sitio_cero_concurso_documentos[' + index + '][label]');
        });
    }

    $(function () {
        var $root = $('[data-docs]');
        if (!$root.length) {
            return;
        }

        $root.find('[data-doc-item]').each(function () {
            bindItem($(this));
        });

        $root.find('[data-doc-add]').on('click', function (e) {
            e.preventDefault();
            var $list = $root.find('.sitio-cero-docs__list');
            var index = $list.find('[data-doc-item]').length;
            var $item = $(
                '<div class="sitio-cero-docs__item" data-doc-item>' +
                    '<input type="hidden" data-doc-id>' +
                    '<input type="text" class="widefat" data-doc-label placeholder="Etiqueta del documento (opcional)">' +
                    '<div class="sitio-cero-docs__actions">' +
                        '<button type="button" class="button sitio-cero-docs__select" data-doc-select>Seleccionar archivo</button>' +
                        '<button type="button" class="button-link-delete sitio-cero-docs__remove" data-doc-remove>Quitar</button>' +
                    '</div>' +
                    '<p class="description sitio-cero-docs__filename" data-doc-filename></p>' +
                '</div>'
            );
            $item.find('[data-doc-id]').attr('name', 'sitio_cero_concurso_documentos[' + index + '][id]');
            $item.find('[data-doc-label]').attr('name', 'sitio_cero_concurso_documentos[' + index + '][label]');
            $list.append($item);
            bindItem($item);
            refreshIndexes($root);
        });

        $root.on('click', '[data-doc-remove]', function () {
            refreshIndexes($root);
        });
    });
})(jQuery);
