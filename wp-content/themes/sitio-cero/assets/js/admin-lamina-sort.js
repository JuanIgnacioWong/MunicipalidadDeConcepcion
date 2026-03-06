(function ($) {
    'use strict';

    function normalizeOrder($list) {
        return $list
            .children('tr.type-lamina_hero')
            .map(function () {
                var id = parseInt(String(this.id || '').replace('post-', ''), 10);
                return Number.isNaN(id) ? null : id;
            })
            .get()
            .filter(function (id) {
                return id && id > 0;
            });
    }

    $(function () {
        if (typeof sitioCeroLaminaSort === 'undefined') {
            return;
        }

        var $list = $('#the-list');
        if (!$list.length) {
            return;
        }

        var $rows = $list.children('tr.type-lamina_hero');
        if ($rows.length < 2) {
            return;
        }

        $rows.each(function () {
            var $row = $(this);
            var $titleCell = $row.find('.column-title');
            if (!$titleCell.length || $titleCell.find('.sitio-cero-sort-handle').length) {
                return;
            }

            $titleCell.prepend('<span class="dashicons dashicons-menu sitio-cero-sort-handle" aria-hidden="true"></span>');
        });

        var $status = $('<p class="sitio-cero-sort-status" aria-live="polite"></p>');
        $('.wrap h1').first().after($status);

        var xhr = null;

        function setStatus(message, stateClass) {
            $status.removeClass('is-saving is-success is-error');
            if (stateClass) {
                $status.addClass(stateClass);
            }
            $status.text(message || '');
        }

        function saveOrder(order) {
            if (!order.length) {
                return;
            }

            if (xhr && typeof xhr.abort === 'function') {
                xhr.abort();
            }

            setStatus(sitioCeroLaminaSort.savingText || 'Guardando...', 'is-saving');

            xhr = $.ajax({
                url: sitioCeroLaminaSort.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sitio_cero_sort_lamina_items',
                    nonce: sitioCeroLaminaSort.nonce,
                    order: order
                }
            })
                .done(function (response) {
                    if (response && response.success) {
                        setStatus(sitioCeroLaminaSort.savedText || 'Orden guardado.', 'is-success');
                        return;
                    }
                    setStatus(sitioCeroLaminaSort.errorText || 'No fue posible guardar.', 'is-error');
                })
                .fail(function () {
                    setStatus(sitioCeroLaminaSort.errorText || 'No fue posible guardar.', 'is-error');
                });
        }

        $list.sortable({
            items: 'tr.type-lamina_hero',
            handle: '.sitio-cero-sort-handle',
            axis: 'y',
            tolerance: 'pointer',
            helper: function (event, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            placeholder: 'sitio-cero-sort-placeholder',
            start: function (event, ui) {
                ui.placeholder.height(ui.item.outerHeight());
            },
            update: function () {
                saveOrder(normalizeOrder($list));
            }
        });
    });
})(jQuery);
