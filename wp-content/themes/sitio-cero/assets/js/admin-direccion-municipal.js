(function ($) {
    'use strict';

    const config = window.sitioCeroDireccionMunicipal || {};
    const resourceBlockLabel = String(config.resourceBlockLabel || 'Bloque');
    const sectionLabel = String(config.sectionLabel || 'Seccion');

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

    const buildRowFromTemplate = (template, rowSelector) => {
        if (!(template instanceof HTMLTemplateElement)) {
            return $();
        }

        const fragment = template.content.cloneNode(true);
        const $row = $(fragment).filter(rowSelector).first();

        if ($row.length > 0) {
            return $row;
        }

        return $(fragment).find(rowSelector).first();
    };

    const applySectionIndex = ($scope, index) => {
        if (!Number.isFinite(index)) {
            return;
        }

        $scope.find('[data-name-template]').each((_, element) => {
            const template = element.getAttribute('data-name-template');
            if (!template) {
                return;
            }

            element.setAttribute('name', template.replace(/__SECTION__/g, index));
        });
    };

    const refreshSectionLabels = ($list) => {
        $list.find('[data-dm-section-row]').each((index, rowElement) => {
            const $row = $(rowElement);
            $row.attr('data-section-index', index);
            $row.find('[data-section-label]').text(`${sectionLabel} ${index + 1}`);
            applySectionIndex($row, index);
        });
    };

    const initRepeater = (rootElement, getSectionIndex) => {
        const $root = $(rootElement);
        const $list = $root.find('[data-dm-list]').first();
        const $addButton = $root.find('[data-dm-add]').first();
        const template = $root.find('template[data-dm-template]')[0];

        if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
            return;
        }

        const addRow = () => {
            const $row = buildRowFromTemplate(template, '[data-dm-row]');
            if ($row.length === 0) {
                return;
            }

            $list.append($row);

            if (typeof getSectionIndex === 'function') {
                const index = parseInt(getSectionIndex(), 10);
                if (Number.isFinite(index)) {
                    applySectionIndex($row, index);
                }
            }
        };

        $addButton.on('click', (event) => {
            event.preventDefault();
            addRow();
        });

        $root.on('click', '[data-dm-remove]', (event) => {
            event.preventDefault();
            $(event.currentTarget).closest('[data-dm-row]').remove();
        });
    };

    const initSectionRow = ($row) => {
        const getSectionIndex = () => $row.attr('data-section-index');

        $row.find('[data-dm-repeater]').each((_, repeater) => {
            initRepeater(repeater, getSectionIndex);
        });
    };

    const initSections = () => {
        $('[data-dm-sections]').each((_, rootElement) => {
            const $root = $(rootElement);
            const $list = $root.find('[data-dm-sections-list]').first();
            const $addButton = $root.find('[data-dm-section-add]').first();
            const template = $root.find('template[data-dm-sections-template]')[0];

            if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
                return;
            }

            const addSection = () => {
                const $row = buildRowFromTemplate(template, '[data-dm-section-row]');
                if ($row.length === 0) {
                    return;
                }

                $list.append($row);
                initSectionRow($row);
                refreshSectionLabels($list);
            };

            $addButton.on('click', (event) => {
                event.preventDefault();
                addSection();
            });

            $root.on('click', '[data-dm-section-remove]', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('[data-dm-section-row]').remove();
                refreshSectionLabels($list);
            });

            $list.find('[data-dm-section-row]').each((_, rowElement) => {
                initSectionRow($(rowElement));
            });

            refreshSectionLabels($list);
        });
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
        initSections();
    });
})(jQuery);
