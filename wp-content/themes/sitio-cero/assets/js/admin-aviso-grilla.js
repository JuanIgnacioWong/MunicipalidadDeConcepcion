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
})(jQuery);
