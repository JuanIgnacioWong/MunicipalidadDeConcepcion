(function ($) {
    'use strict';

    const config = window.sitioCeroAvisoFiles || {};

    const detectIconByUrl = (url) => {
        const normalized = String(url || '').trim().toLowerCase();
        if (normalized === '') {
            return 'file';
        }

        const cleanUrl = normalized.split('?')[0].split('#')[0];
        const extension = cleanUrl.includes('.') ? cleanUrl.split('.').pop() : '';

        if (['pdf'].includes(extension)) {
            return 'pdf';
        }

        if (['doc', 'docx', 'odt', 'rtf', 'txt'].includes(extension)) {
            return 'doc';
        }

        if (['xls', 'xlsx', 'csv', 'ods'].includes(extension)) {
            return 'xls';
        }

        if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'heic', 'bmp', 'tif', 'tiff'].includes(extension)) {
            return 'img';
        }

        return 'file';
    };

    const createRowFromTemplate = ($field) => {
        const template = $field.find('.sitio-cero-aviso-files__template')[0];
        if (!template || !(template instanceof HTMLTemplateElement)) {
            return null;
        }

        const fragment = template.content.cloneNode(true);
        const $row = $(fragment).filter('[data-file-row]').first();
        if ($row.length > 0) {
            return $row;
        }

        return $(fragment).find('[data-file-row]').first();
    };

    const getExistingUrls = ($list) => {
        return new Set(
            $list
                .find('[data-file-row] [data-key="url"]')
                .map((_, element) => String($(element).val() || '').trim())
                .get()
                .filter((value) => value !== '')
        );
    };

    const syncTextarea = ($field) => {
        const targetSelector = String($field.attr('data-target') || '').trim();
        if (targetSelector === '') {
            return;
        }

        const $textarea = $(targetSelector);
        if ($textarea.length === 0) {
            return;
        }

        const lines = $field
            .find('[data-file-row]')
            .map((_, row) => {
                const $row = $(row);
                const label = String($row.find('[data-key="label"]').val() || '').trim();
                const url = String($row.find('[data-key="url"]').val() || '').trim();
                const icon = String($row.find('[data-key="icon"]').val() || '').trim();

                if (url === '') {
                    return null;
                }

                let line = '';
                if (label !== '') {
                    line = `${label}|${url}`;
                    if (icon !== '') {
                        line += `|${icon}`;
                    }
                } else if (icon !== '') {
                    // Keep icon when label is empty: |url|icon
                    line = `|${url}|${icon}`;
                } else {
                    line = url;
                }

                return line;
            })
            .get()
            .filter((value) => typeof value === 'string' && value.trim() !== '');

        $textarea.val(lines.join('\n')).trigger('change');
    };

    const addRow = ($field, data = {}) => {
        const $list = $field.find('[data-file-list]');
        if ($list.length === 0) {
            return;
        }

        const $row = createRowFromTemplate($field);
        if (!$row || $row.length === 0) {
            return;
        }

        const label = String(data.label || '').trim();
        const url = String(data.url || '').trim();
        const icon = String(data.icon || '').trim();

        $row.find('[data-key="label"]').val(label);
        $row.find('[data-key="url"]').val(url);
        $row.find('[data-key="icon"]').val(icon);

        $list.append($row);
        syncTextarea($field);
    };

    const addAttachments = ($field, attachments) => {
        const $list = $field.find('[data-file-list]');
        if ($list.length === 0 || !Array.isArray(attachments)) {
            return;
        }

        const existingUrls = getExistingUrls($list);

        attachments.forEach((attachment) => {
            const url = String(attachment.url || '').trim();
            if (url === '' || existingUrls.has(url)) {
                return;
            }

            existingUrls.add(url);
            addRow($field, {
                label: String(attachment.title || attachment.filename || '').trim(),
                url,
                icon: detectIconByUrl(url)
            });
        });

        syncTextarea($field);
    };

    const initField = (fieldElement) => {
        const $field = $(fieldElement);
        const $list = $field.find('[data-file-list]');
        const $addButton = $field.find('.sitio-cero-aviso-files__add');
        const $libraryButton = $field.find('.sitio-cero-aviso-files__library');

        if ($list.length === 0) {
            return;
        }

        if ($list.find('[data-file-row]').length === 0) {
            addRow($field, {});
        } else {
            syncTextarea($field);
        }

        $addButton.on('click', (event) => {
            event.preventDefault();
            addRow($field, {});
        });

        $field.on('click', '.sitio-cero-aviso-files__remove', (event) => {
            event.preventDefault();
            $(event.currentTarget).closest('[data-file-row]').remove();

            if ($list.find('[data-file-row]').length === 0) {
                addRow($field, {});
            } else {
                syncTextarea($field);
            }
        });

        $field.on('input change', '[data-key="label"], [data-key="url"], [data-key="icon"]', () => {
            syncTextarea($field);
        });

        let mediaFrame = null;

        $libraryButton.on('click', (event) => {
            event.preventDefault();

            if (!mediaFrame) {
                mediaFrame = wp.media({
                    title: config.frameTitle || 'Selecciona archivos',
                    button: {
                        text: config.frameButton || 'Agregar archivos'
                    },
                    multiple: true
                });

                mediaFrame.on('select', () => {
                    const selection = mediaFrame.state().get('selection');
                    if (!selection) {
                        return;
                    }

                    addAttachments($field, selection.toJSON());
                });
            }

            mediaFrame.open();
        });
    };

    $(() => {
        $('.sitio-cero-aviso-files').each((_, element) => {
            initField(element);
        });
    });
})(jQuery);
