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
        initResourceBlocks();
    });
})(jQuery);
