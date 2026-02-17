(function ($) {
    'use strict';

    const config = window.sitioCeroDireccionMunicipal || {};
    const resourceBlockLabel = String(config.resourceBlockLabel || 'Bloque');

    const initPhones = () => {
        $('[data-phones-list]').each((_, listElement) => {
            const $list = $(listElement);
            const $root = $list.closest('.sitio-cero-dm-phones');
            const $addButton = $root.find('[data-phone-add]').first();
            const template = $root.find('template[data-phone-template]')[0];

            const addPhoneRow = () => {
                if (!(template instanceof HTMLTemplateElement)) {
                    return;
                }

                const fragment = template.content.cloneNode(true);
                const $row = $(fragment).filter('[data-phone-row]').first();

                if ($row.length > 0) {
                    $list.append($row);
                    return;
                }

                const $fallbackRow = $(fragment).find('[data-phone-row]').first();
                if ($fallbackRow.length > 0) {
                    $list.append($fallbackRow);
                }
            };

            $addButton.on('click', (event) => {
                event.preventDefault();
                addPhoneRow();
            });

            $root.on('click', '[data-phone-remove]', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('[data-phone-row]').remove();

                if ($list.find('[data-phone-row]').length === 0) {
                    addPhoneRow();
                }
            });
        });
    };

    const refreshResourceBlockLabels = ($list) => {
        $list.find('[data-dm-resource-block-row]').each((index, rowElement) => {
            $(rowElement)
                .find('.sitio-cero-dm-resource-block__head strong')
                .text(`${resourceBlockLabel} ${index + 1}`);
        });
    };

    const initAvisoFileFields = (target) => {
        if (typeof window.sitioCeroInitAvisoFilesField === 'function') {
            window.sitioCeroInitAvisoFilesField(target);
            return;
        }

        $(document).trigger('sitio-cero:init-aviso-files', [target]);
    };

    const insertShortcodeInTextarea = ($textarea, shortcode) => {
        const value = String($textarea.val() || '');
        const field = $textarea.get(0);

        if (
            !field
            || typeof field.selectionStart !== 'number'
            || typeof field.selectionEnd !== 'number'
        ) {
            const needsNewline = value !== '' && !/\n\s*$/.test(value);
            const nextValue = needsNewline ? `${value}\n${shortcode}` : `${value}${shortcode}`;
            $textarea.val(nextValue).trigger('input').trigger('change');
            return;
        }

        const start = field.selectionStart;
        const end = field.selectionEnd;
        const before = value.slice(0, start);
        const after = value.slice(end);

        let insertion = shortcode;
        if (before !== '' && !before.endsWith('\n')) {
            insertion = `\n${insertion}`;
        }
        if (after !== '' && !after.startsWith('\n')) {
            insertion = `${insertion}\n`;
        }

        const nextValue = `${before}${insertion}${after}`;
        const cursor = before.length + insertion.length;

        $textarea.val(nextValue).trigger('input').trigger('change');
        field.focus();
        field.setSelectionRange(cursor, cursor);
    };

    const initEmbedShortcodePickers = () => {
        $(document).on('click', '[data-embed-shortcode-insert]', (event) => {
            event.preventDefault();

            const $button = $(event.currentTarget);
            const targetSelector = String($button.attr('data-target') || '').trim();
            if (targetSelector === '') {
                return;
            }

            const $picker = $button.closest('.sitio-cero-dm-embed-picker');
            const $select = $picker
                .find(`[data-embed-shortcode-select][data-target="${targetSelector}"]`)
                .first();
            if ($select.length === 0) {
                return;
            }

            const selectedId = parseInt(String($select.val() || ''), 10);
            if (!Number.isFinite(selectedId) || selectedId <= 0) {
                const message = String(config.selectAccordionMessage || 'Selecciona un acordeon para insertarlo.');
                if (message !== '') {
                    window.alert(message);
                }
                return;
            }

            const $textarea = $(targetSelector).first();
            if ($textarea.length === 0) {
                return;
            }

            insertShortcodeInTextarea($textarea, `[acordeon id="${selectedId}"]`);
        });
    };

    const buildResourceBlockFromTemplate = (template, key) => {
        if (!(template instanceof HTMLTemplateElement)) {
            return $();
        }

        const templateHtml = String(template.innerHTML || '').replace(/__KEY__/g, key);
        const $content = $(templateHtml);
        const $row = $content.filter('[data-dm-resource-block-row]').first();

        if ($row.length > 0) {
            return $row;
        }

        return $content.find('[data-dm-resource-block-row]').first();
    };

    const initResourceBlocks = () => {
        $('[data-dm-resource-blocks]').each((_, rootElement) => {
            const $root = $(rootElement);
            const $list = $root.find('[data-dm-resource-blocks-list]').first();
            const $addButton = $root.find('[data-dm-resource-block-add]').first();
            const template = $root.find('template[data-dm-resource-block-template]')[0];

            if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
                return;
            }

            let counter = $list.find('[data-dm-resource-block-row]').length;

            const addResourceBlock = () => {
                const key = `new-${Date.now()}-${counter++}`;
                const $row = buildResourceBlockFromTemplate(template, key);

                if ($row.length === 0) {
                    return;
                }

                $list.append($row);
                initAvisoFileFields($row);
                refreshResourceBlockLabels($list);
            };

            $addButton.on('click', (event) => {
                event.preventDefault();
                addResourceBlock();
            });

            $root.on('click', '[data-dm-resource-block-remove]', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('[data-dm-resource-block-row]').remove();

                if ($list.find('[data-dm-resource-block-row]').length === 0) {
                    addResourceBlock();
                } else {
                    refreshResourceBlockLabels($list);
                }
            });

            $list.find('[data-dm-resource-block-row]').each((_, rowElement) => {
                initAvisoFileFields(rowElement);
            });

            refreshResourceBlockLabels($list);
        });
    };

    $(() => {
        initPhones();
        initEmbedShortcodePickers();
        initResourceBlocks();
    });
})(jQuery);
