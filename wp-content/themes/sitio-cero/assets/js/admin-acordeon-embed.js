(function ($) {
    'use strict';

    const renumberRows = ($list) => {
        $list.find('[data-acordeon-admin-row]').each((index, row) => {
            $(row).find('.sitio-cero-acordeon-embed-admin__row-head strong').text(`Item ${index + 1}`);
        });
    };

    const addRow = ($root) => {
        const $list = $root.find('[data-acordeon-admin-list]').first();
        const template = $root.find('template[data-acordeon-admin-template]')[0];
        if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
            return;
        }

        const fragment = template.content.cloneNode(true);
        const $row = $(fragment).filter('[data-acordeon-admin-row]').first();
        const $target = $row.length > 0 ? $row : $(fragment).find('[data-acordeon-admin-row]').first();
        if ($target.length === 0) {
            return;
        }

        $list.append($target);
        renumberRows($list);
    };

    const initAcordeonAdmin = (rootElement) => {
        const $root = $(rootElement);
        const $list = $root.find('[data-acordeon-admin-list]').first();
        if ($list.length === 0) {
            return;
        }

        $root.find('[data-acordeon-admin-add]').on('click', (event) => {
            event.preventDefault();
            addRow($root);
        });

        $root.on('click', '[data-acordeon-admin-remove]', (event) => {
            event.preventDefault();
            $(event.currentTarget).closest('[data-acordeon-admin-row]').remove();
            if ($list.find('[data-acordeon-admin-row]').length === 0) {
                addRow($root);
            } else {
                renumberRows($list);
            }
        });

        renumberRows($list);
    };

    $(() => {
        $('[data-acordeon-admin-root]').each((_, element) => {
            initAcordeonAdmin(element);
        });
    });
})(jQuery);
