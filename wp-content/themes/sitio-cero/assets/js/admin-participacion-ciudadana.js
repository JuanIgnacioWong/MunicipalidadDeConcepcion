(function ($) {
    'use strict';

    const addRow = ($root) => {
        const $list = $root.find('[data-pc-list]').first();
        const template = $root.find('template[data-pc-template]')[0];
        if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
            return;
        }

        const fragment = template.content.cloneNode(true);
        const $row = $(fragment).filter('[data-pc-row]').first();
        const $target = $row.length > 0 ? $row : $(fragment).find('[data-pc-row]').first();
        if ($target.length === 0) {
            return;
        }

        $list.append($target);
    };

    const ensureRow = ($root) => {
        const $list = $root.find('[data-pc-list]').first();
        if ($list.length === 0) {
            return;
        }

        if ($list.find('[data-pc-row]').length === 0) {
            addRow($root);
        }
    };

    const initRepeater = (rootElement) => {
        const $root = $(rootElement);
        const $list = $root.find('[data-pc-list]').first();
        if ($list.length === 0) {
            return;
        }

        $root.find('[data-pc-add]').on('click', (event) => {
            event.preventDefault();
            addRow($root);
        });

        $root.on('click', '[data-pc-remove]', (event) => {
            event.preventDefault();
            $(event.currentTarget).closest('[data-pc-row]').remove();
            ensureRow($root);
        });

        ensureRow($root);
    };

    $(() => {
        $('[data-pc-repeater]').each((_, element) => {
            initRepeater(element);
        });
    });
})(jQuery);
