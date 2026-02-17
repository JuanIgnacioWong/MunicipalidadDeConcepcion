(function ($) {
    'use strict';

    const renumberAccordion = ($list) => {
        $list.find('[data-accordion-row]').each((index, row) => {
            $(row).find('.sitio-cero-dm-accordion__row-head strong').text(`Item ${index + 1}`);
        });
    };

    const collectSubtabs = ($root) => {
        return $root
            .find('[data-subtab-row]')
            .map((_, row) => {
                const $row = $(row);
                const title = String($row.find('[data-subtab-title]').val() || '').trim();
                const content = String($row.find('[data-subtab-content]').val() || '').trim();

                if (title === '' && content === '') {
                    return null;
                }

                return {
                    title,
                    content
                };
            })
            .get()
            .filter((item) => item && (item.title !== '' || item.content !== ''));
    };

    const syncSubtabsHidden = ($root) => {
        const $hidden = $root.find('[data-subtabs-hidden]').first();
        if ($hidden.length === 0) {
            return;
        }

        const items = collectSubtabs($root);
        $hidden.val(JSON.stringify(items));
    };

    const addSubtabRow = ($root, data = {}) => {
        const $list = $root.find('[data-subtabs-list]').first();
        const template = $root.find('template[data-subtab-template]')[0];

        if ($list.length === 0 || !(template instanceof HTMLTemplateElement)) {
            return;
        }

        const fragment = template.content.cloneNode(true);
        const $row = $(fragment).filter('[data-subtab-row]').first();
        const $targetRow = $row.length > 0 ? $row : $(fragment).find('[data-subtab-row]').first();

        if ($targetRow.length === 0) {
            return;
        }

        $targetRow.find('[data-subtab-title]').val(String(data.title || '').trim());
        $targetRow.find('[data-subtab-content]').val(String(data.content || '').trim());

        $list.append($targetRow);
        syncSubtabsHidden($root);
    };

    const initSubtabs = ($accordionRow) => {
        $accordionRow.find('[data-subtabs-root]').each((_, rootElement) => {
            const $root = $(rootElement);
            const $addButton = $root.find('[data-subtab-add]').first();
            const $list = $root.find('[data-subtabs-list]').first();

            $addButton.on('click', (event) => {
                event.preventDefault();
                addSubtabRow($root, {});
            });

            $root.on('click', '[data-subtab-remove]', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('[data-subtab-row]').remove();
                syncSubtabsHidden($root);
            });

            $root.on('input change', '[data-subtab-title], [data-subtab-content]', () => {
                syncSubtabsHidden($root);
            });

            if ($list.find('[data-subtab-row]').length === 0) {
                const rawValue = String($root.find('[data-subtabs-hidden]').val() || '').trim();
                if (rawValue !== '') {
                    try {
                        const parsed = JSON.parse(rawValue);
                        if (Array.isArray(parsed)) {
                            parsed.forEach((item) => {
                                if (item && typeof item === 'object') {
                                    addSubtabRow($root, item);
                                }
                            });
                        }
                    } catch (_error) {
                        // Ignore malformed legacy data.
                    }
                }
            }

            syncSubtabsHidden($root);
        });
    };

    const initPhones = () => {
        $('[data-phones-list]').each((_, listElement) => {
            const $list = $(listElement);
            const $root = $list.closest('.sitio-cero-dm-phones');
            const $addButton = $root.find('[data-phone-add]');
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

                $list.append($(fragment).find('[data-phone-row]').first());
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

    const initAccordion = () => {
        $('[data-accordion-root]').each((_, rootElement) => {
            const $root = $(rootElement);
            const $list = $root.find('[data-accordion-list]');
            const $addButton = $root.find('[data-accordion-add]');
            const template = $root.find('template[data-accordion-template]')[0];

            const addAccordionRow = () => {
                if (!(template instanceof HTMLTemplateElement)) {
                    return;
                }

                const fragment = template.content.cloneNode(true);
                const $row = $(fragment).filter('[data-accordion-row]').first();
                const $targetRow = $row.length > 0 ? $row : $(fragment).find('[data-accordion-row]').first();

                if ($targetRow.length === 0) {
                    return;
                }

                $list.append($targetRow);
                initSubtabs($targetRow);
                renumberAccordion($list);
            };

            if ($.fn.sortable) {
                $list.sortable({
                    handle: '[data-accordion-drag]',
                    placeholder: 'sitio-cero-dm-accordion__row--placeholder',
                    forcePlaceholderSize: true,
                    update: () => {
                        renumberAccordion($list);
                    }
                });
            }

            $addButton.on('click', (event) => {
                event.preventDefault();
                addAccordionRow();
            });

            $root.on('click', '[data-accordion-remove]', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('[data-accordion-row]').remove();

                if ($list.find('[data-accordion-row]').length === 0) {
                    addAccordionRow();
                }

                renumberAccordion($list);
            });

            $list.find('[data-accordion-row]').each((_, rowElement) => {
                initSubtabs($(rowElement));
            });

            renumberAccordion($list);
        });
    };

    $(() => {
        initPhones();
        initAccordion();
    });
})(jQuery);
