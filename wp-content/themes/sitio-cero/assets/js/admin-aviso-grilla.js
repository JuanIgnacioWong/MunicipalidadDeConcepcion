(function ($) {
    'use strict';

    let mediaFrame = null;
    let currentTargetSelector = '';

    const getConfig = () => {
        if (typeof sitioCeroAvisoGrilla === 'undefined') {
            return {
                frameTitle: 'Selecciona una imagen',
                frameButton: 'Usar imagen'
            };
        }

        return {
            frameTitle: sitioCeroAvisoGrilla.frameTitle || 'Selecciona una imagen',
            frameButton: sitioCeroAvisoGrilla.frameButton || 'Usar imagen'
        };
    };

    const openMediaFrame = () => {
        if (typeof wp === 'undefined' || !wp.media) {
            return;
        }

        const config = getConfig();

        if (!mediaFrame) {
            mediaFrame = wp.media({
                title: config.frameTitle,
                button: {
                    text: config.frameButton
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            mediaFrame.on('select', () => {
                const selection = mediaFrame.state().get('selection').first();
                if (!selection || !currentTargetSelector) {
                    return;
                }

                const data = selection.toJSON();
                const imageUrl = data && data.url ? String(data.url) : '';
                if (imageUrl === '') {
                    return;
                }

                const $target = $(currentTargetSelector);
                if ($target.length === 0) {
                    return;
                }

                $target.val(imageUrl).trigger('change');
            });
        }

        mediaFrame.open();
    };

    const updateConcejoPreview = ($input) => {
        const $preview = $input.closest('td').find('.sitio-cero-concejo-image-preview');
        if ($preview.length === 0) {
            return;
        }

        const url = String($input.val() || '').trim();
        const $img = $preview.find('.sitio-cero-concejo-image-preview__img');
        const $placeholder = $preview.find('.sitio-cero-concejo-image-placeholder');

        if ($img.length === 0 || $placeholder.length === 0) {
            return;
        }

        if (url !== '') {
            $img.attr('src', url).show();
            $placeholder.hide();
        } else {
            $img.attr('src', '').hide();
            $placeholder.show();
        }
    };

    const bindConcejoPreview = ($input) => {
        if ($input.data('concejo-preview-bound')) {
            return;
        }

        $input.data('concejo-preview-bound', true);

        const $preview = $input.closest('td').find('.sitio-cero-concejo-image-preview');
        const $img = $preview.find('.sitio-cero-concejo-image-preview__img');
        const $placeholder = $preview.find('.sitio-cero-concejo-image-placeholder');

        if ($img.length > 0) {
            $img.on('error', () => {
                $img.hide();
                $placeholder.show();
            });
        }

        $input.on('input change', () => {
            updateConcejoPreview($input);
        });

        updateConcejoPreview($input);
    };

    $(document).on('click', '.sitio-cero-media-picker', (event) => {
        event.preventDefault();

        const $button = $(event.currentTarget);
        const targetSelector = String($button.attr('data-target') || '').trim();
        if (targetSelector === '') {
            return;
        }

        currentTargetSelector = targetSelector;
        openMediaFrame();
    });

    $(document).ready(() => {
        $('.sitio-cero-concejo-image-url').each((_, element) => {
            bindConcejoPreview($(element));
        });
    });
})(jQuery);
