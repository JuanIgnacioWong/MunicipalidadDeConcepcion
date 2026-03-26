<?php
get_header();
?>

<main id="content" class="site-main dm-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();

        $director = get_post_meta($post_id, 'sitio_cero_direccion_director', true);
        $profesion = get_post_meta($post_id, 'sitio_cero_direccion_profesion', true);
        $telefonos = get_post_meta($post_id, 'sitio_cero_direccion_telefonos', true);
        $email = get_post_meta($post_id, 'sitio_cero_direccion_email', true);
        $direccion = get_post_meta($post_id, 'sitio_cero_direccion_direccion', true);
        $mapa_url = get_post_meta($post_id, 'sitio_cero_direccion_mapa_url', true);
        $custom_html = get_post_meta($post_id, 'sitio_cero_direccion_custom_html', true);

        if (!is_string($director)) {
            $director = '';
        }
        if (!is_string($profesion)) {
            $profesion = '';
        }
        if (!is_array($telefonos)) {
            $telefonos = array();
        }
        if (!is_string($email)) {
            $email = '';
        }
        if (!is_string($direccion)) {
            $direccion = '';
        }
        if (!is_string($mapa_url)) {
            $mapa_url = '';
        }
        if (!is_string($custom_html)) {
            $custom_html = '';
        }

        $map_src = '';
        $clean_map_url = esc_url_raw($mapa_url);
        if ('' !== $clean_map_url) {
            $map_src = $clean_map_url;
        } elseif ('' !== trim($direccion)) {
            $map_src = 'https://www.google.com/maps?q=' . rawurlencode($direccion) . '&output=embed';
        }
        $map_link = '';
        if ('' !== $clean_map_url) {
            $map_link = $clean_map_url;
        } elseif ('' !== trim($direccion)) {
            $map_link = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($direccion);
        }

        $recursos_titulo = get_post_meta($post_id, 'sitio_cero_direccion_recursos_titulo', true);
        $resource_blocks = function_exists('sitio_cero_get_direccion_resource_blocks')
            ? sitio_cero_get_direccion_resource_blocks($post_id)
            : array();
        $sections = function_exists('sitio_cero_get_direccion_sections')
            ? sitio_cero_get_direccion_sections($post_id)
            : array();

        if (!is_string($recursos_titulo)) {
            $recursos_titulo = '';
        }

        $allowed_html = function_exists('sitio_cero_get_direccion_allowed_html')
            ? sitio_cero_get_direccion_allowed_html()
            : wp_kses_allowed_html('post');

        if (empty($resource_blocks)) {
            $legacy_documentos_raw = get_post_meta($post_id, 'sitio_cero_direccion_documentos', true);
            $legacy_archivos_raw = get_post_meta($post_id, 'sitio_cero_direccion_archivos', true);
            $legacy_documentos_titulo = get_post_meta($post_id, 'sitio_cero_direccion_documentos_titulo', true);
            $legacy_archivos_titulo = get_post_meta($post_id, 'sitio_cero_direccion_archivos_titulo', true);
            $legacy_documentos_html = get_post_meta($post_id, 'sitio_cero_direccion_documentos_html', true);
            $legacy_archivos_html = get_post_meta($post_id, 'sitio_cero_direccion_archivos_html', true);

            if (!is_string($legacy_documentos_raw)) {
                $legacy_documentos_raw = '';
            }
            if (!is_string($legacy_archivos_raw)) {
                $legacy_archivos_raw = '';
            }
            if (!is_string($legacy_documentos_titulo)) {
                $legacy_documentos_titulo = '';
            }
            if (!is_string($legacy_archivos_titulo)) {
                $legacy_archivos_titulo = '';
            }
            if (!is_string($legacy_documentos_html)) {
                $legacy_documentos_html = '';
            }
            if (!is_string($legacy_archivos_html)) {
                $legacy_archivos_html = '';
            }

            if ('' !== trim($legacy_documentos_raw) || '' !== trim($legacy_documentos_titulo) || '' !== trim((string) wp_strip_all_tags($legacy_documentos_html))) {
                $resource_blocks[] = array(
                    'type'  => 'documentos',
                    'title' => '' !== trim($legacy_documentos_titulo) ? $legacy_documentos_titulo : __('Documentos', 'sitio-cero'),
                    'html'  => $legacy_documentos_html,
                    'links' => $legacy_documentos_raw,
                );
            }

            if ('' !== trim($legacy_archivos_raw) || '' !== trim($legacy_archivos_titulo) || '' !== trim((string) wp_strip_all_tags($legacy_archivos_html))) {
                $resource_blocks[] = array(
                    'type'  => 'archivos',
                    'title' => '' !== trim($legacy_archivos_titulo) ? $legacy_archivos_titulo : __('Archivos', 'sitio-cero'),
                    'html'  => $legacy_archivos_html,
                    'links' => $legacy_archivos_raw,
                );
            }
        }

        $resource_blocks_view = array();
        foreach ($resource_blocks as $resource_block) {
            if (!is_array($resource_block)) {
                continue;
            }

            $block_type = isset($resource_block['type']) ? sanitize_key((string) $resource_block['type']) : 'documentos';
            if (!in_array($block_type, array('documentos', 'archivos'), true)) {
                $block_type = 'documentos';
            }

            $block_title = isset($resource_block['title']) ? sanitize_text_field((string) $resource_block['title']) : '';
            if ('' === $block_title) {
                $block_title = 'archivos' === $block_type
                    ? __('Archivos', 'sitio-cero')
                    : __('Documentos', 'sitio-cero');
            }

            $block_html_raw = isset($resource_block['html']) ? (string) $resource_block['html'] : '';
            $block_html = '';
            if ('' !== trim($block_html_raw)) {
                $block_html = do_shortcode(wp_kses($block_html_raw, $allowed_html));
            }

            $block_links_raw = isset($resource_block['links']) ? (string) $resource_block['links'] : '';
            $block_items = function_exists('sitio_cero_parse_aviso_links_textarea')
                ? sitio_cero_parse_aviso_links_textarea($block_links_raw)
                : array();

            if (empty($block_items) && '' === trim($block_html)) {
                continue;
            }

            $resource_blocks_view[] = array(
                'type'  => $block_type,
                'title' => $block_title,
                'html'  => $block_html,
                'items' => $block_items,
            );
        }

        $custom_html_output = '';
        if ('' !== trim($custom_html)) {
            $custom_html_output = do_shortcode(wp_kses($custom_html, $allowed_html));
        }

        $sections_view = array();
        $used_section_ids = array('descripcion', 'organizacion', 'recursos', 'extra');
        $valid_section_styles = array('paper', 'soft', 'dark');
        $valid_button_styles = array('pill', 'card', 'card-dark');

        foreach ($sections as $section_index => $section) {
            if (!is_array($section)) {
                continue;
            }

            $section_title = isset($section['title']) ? sanitize_text_field((string) $section['title']) : '';
            $section_kicker = isset($section['kicker']) ? sanitize_text_field((string) $section['kicker']) : '';
            $section_content_raw = isset($section['content']) ? (string) $section['content'] : '';
            $section_content = '';
            if ('' !== trim($section_content_raw)) {
                $section_content = do_shortcode(wp_kses($section_content_raw, $allowed_html));
            }

            $section_style = isset($section['style']) ? sanitize_key((string) $section['style']) : '';
            if (!in_array($section_style, $valid_section_styles, true)) {
                $section_style = '';
            }

            $section_buttons_style = isset($section['buttons_style']) ? sanitize_key((string) $section['buttons_style']) : '';
            if (!in_array($section_buttons_style, $valid_button_styles, true)) {
                $section_buttons_style = 'pill';
            }

            $section_buttons = array();
            if (isset($section['buttons']) && is_array($section['buttons'])) {
                foreach ($section['buttons'] as $button) {
                    if (!is_array($button)) {
                        continue;
                    }
                    $button_label = isset($button['label']) ? sanitize_text_field((string) $button['label']) : '';
                    $button_url = isset($button['url']) ? esc_url((string) $button['url']) : '';
                    $button_target = isset($button['target']) && '_blank' === $button['target'] ? '_blank' : '';

                    if ('' === $button_label || '' === $button_url) {
                        continue;
                    }

                    $section_buttons[] = array(
                        'label'  => $button_label,
                        'url'    => $button_url,
                        'target' => $button_target,
                    );
                }
            }

            $section_accordions = array();
            if (isset($section['accordions']) && is_array($section['accordions'])) {
                foreach ($section['accordions'] as $accordion) {
                    if (!is_array($accordion)) {
                        continue;
                    }
                    $accordion_title = isset($accordion['title']) ? sanitize_text_field((string) $accordion['title']) : '';
                    $accordion_content_raw = isset($accordion['content']) ? (string) $accordion['content'] : '';
                    $accordion_content = '';
                    if ('' !== trim($accordion_content_raw)) {
                        $accordion_content = do_shortcode(wp_kses($accordion_content_raw, $allowed_html));
                    }

                    if ('' === $accordion_title && '' === trim($accordion_content)) {
                        continue;
                    }

                    $section_accordions[] = array(
                        'title'   => $accordion_title,
                        'content' => $accordion_content,
                    );
                }
            }

            $section_subtabs = array();
            if (isset($section['subtabs']) && is_array($section['subtabs'])) {
                foreach ($section['subtabs'] as $subtab) {
                    if (!is_array($subtab)) {
                        continue;
                    }
                    $subtab_title = isset($subtab['title']) ? sanitize_text_field((string) $subtab['title']) : '';
                    $subtab_content_raw = isset($subtab['content']) ? (string) $subtab['content'] : '';
                    $subtab_content = '';
                    if ('' !== trim($subtab_content_raw)) {
                        $subtab_content = do_shortcode(wp_kses($subtab_content_raw, $allowed_html));
                    }

                    if ('' === $subtab_title && '' === trim($subtab_content)) {
                        continue;
                    }

                    $section_subtabs[] = array(
                        'title'   => $subtab_title,
                        'content' => $subtab_content,
                    );
                }
            }

            if (
                '' === $section_title
                && '' === $section_kicker
                && '' === trim($section_content)
                && empty($section_buttons)
                && empty($section_accordions)
                && empty($section_subtabs)
            ) {
                continue;
            }

            if ('' === $section_title) {
                $section_title = sprintf(__('Sección %d', 'sitio-cero'), $section_index + 1);
            }

            $section_anchor = isset($section['anchor']) ? sanitize_title(ltrim((string) $section['anchor'], '#')) : '';
            if ('' === $section_anchor) {
                $section_anchor = sanitize_title($section_title);
            }
            if ('' === $section_anchor) {
                $section_anchor = 'seccion-' . ($section_index + 1);
            }

            $base_anchor = $section_anchor;
            $suffix = 2;
            while (in_array($section_anchor, $used_section_ids, true)) {
                $section_anchor = $base_anchor . '-' . $suffix;
                $suffix++;
            }
            $used_section_ids[] = $section_anchor;

            $sections_view[] = array(
                'id'            => $section_anchor,
                'title'         => $section_title,
                'kicker'        => $section_kicker,
                'content'       => $section_content,
                'style'         => $section_style,
                'buttons_style' => $section_buttons_style,
                'buttons'       => $section_buttons,
                'accordions'    => $section_accordions,
                'subtabs'       => $section_subtabs,
            );
        }

        $has_sections = !empty($sections_view);

        $has_content = '' !== trim((string) get_the_content());
        $has_org_data = '' !== trim($director) || '' !== trim($profesion) || !empty($telefonos) || '' !== trim($email) || '' !== trim($direccion);
        $has_map = '' !== $map_src;
        $has_resources = !empty($resource_blocks_view);
        $has_custom = '' !== $custom_html_output;
        ?>

        <article id="direccion-municipal-<?php the_ID(); ?>" <?php post_class('dm-page pc-page'); ?>>
            <section class="pc-hero">
                <div class="pc-hero__inner dm-hero">
                    <p class="pc-hero__eyebrow"><?php esc_html_e('Direcciones Municipales', 'sitio-cero'); ?></p>
                    <h1 class="pc-hero__title"><?php the_title(); ?></h1>
                    <?php if ('' !== trim($director) || '' !== trim($email) || !empty($telefonos)) : ?>
                        <div class="dm-hero__meta">
                            <?php if ('' !== trim($director)) : ?>
                                <span class="dm-chip"><?php echo esc_html__('Director:', 'sitio-cero') . ' ' . esc_html($director); ?></span>
                            <?php endif; ?>
                            <?php if ('' !== trim($email)) : ?>
                                <a class="dm-chip dm-chip--link" href="mailto:<?php echo esc_attr(sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a>
                            <?php endif; ?>
                            <?php if (!empty($telefonos)) : ?>
                                <?php foreach ($telefonos as $telefono) : ?>
                                    <?php
                                    $phone_text = sanitize_text_field((string) $telefono);
                                    if ('' === trim($phone_text)) {
                                        continue;
                                    }
                                    $phone_href = preg_replace('/[^0-9+]/', '', $phone_text);
                                    ?>
                                    <?php if ('' !== trim((string) $phone_href)) : ?>
                                        <a class="dm-chip dm-chip--link" href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_text); ?></a>
                                    <?php else : ?>
                                        <span class="dm-chip"><?php echo esc_html($phone_text); ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ('' !== trim($map_link)) : ?>
                                <a class="dm-chip dm-chip--icon" href="<?php echo esc_url($map_link); ?>" target="_blank" rel="noopener">
                                    <span class="material-symbols-rounded" aria-hidden="true">location_on</span>
                                    <span><?php esc_html_e('Mapa', 'sitio-cero'); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="pc-subnav" aria-label="<?php esc_attr_e('Indice de contenidos', 'sitio-cero'); ?>">
                <div class="pc-subnav__inner">
                    <?php if ($has_content) : ?>
                        <a class="pc-subnav__link" href="#descripcion"><?php esc_html_e('Descripcion', 'sitio-cero'); ?></a>
                    <?php endif; ?>
                    <?php if ($has_org_data || $has_map) : ?>
                        <a class="pc-subnav__link" href="#organizacion"><?php esc_html_e('Organizacion', 'sitio-cero'); ?></a>
                    <?php endif; ?>
                    <?php if ($has_resources) : ?>
                        <a class="pc-subnav__link" href="#recursos"><?php echo esc_html('' !== trim($recursos_titulo) ? $recursos_titulo : __('Recursos', 'sitio-cero')); ?></a>
                    <?php endif; ?>
                    <?php if ($has_custom) : ?>
                        <a class="pc-subnav__link" href="#extra"><?php esc_html_e('Informacion adicional', 'sitio-cero'); ?></a>
                    <?php endif; ?>
                    <?php if ($has_sections) : ?>
                        <?php foreach ($sections_view as $section_item) : ?>
                            <?php
                            if (!is_array($section_item)) {
                                continue;
                            }
                            $section_link = isset($section_item['id']) ? (string) $section_item['id'] : '';
                            $section_label = isset($section_item['title']) ? (string) $section_item['title'] : '';
                            if ('' === $section_link || '' === $section_label) {
                                continue;
                            }
                            ?>
                            <a class="pc-subnav__link" href="#<?php echo esc_attr($section_link); ?>"><?php echo esc_html($section_label); ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <?php if ($has_content) : ?>
                <section id="descripcion" class="pc-section pc-section--paper">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Perfil', 'sitio-cero'); ?></p>
                            <h2><?php esc_html_e('Descripcion general', 'sitio-cero'); ?></h2>
                        </header>
                        <div class="pc-flow content-body dm-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($has_org_data || $has_map) : ?>
                <section id="organizacion" class="pc-section pc-section--soft">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Organizacion', 'sitio-cero'); ?></p>
                            <h2><?php esc_html_e('Equipo y contacto', 'sitio-cero'); ?></h2>
                        </header>
                        <div class="pc-columns dm-columns">
                            <div class="dm-org">
                                <ul class="dm-org__list">
                                    <?php if ('' !== trim($director)) : ?>
                                        <li class="dm-org__item">
                                            <span class="dm-org__label"><?php esc_html_e('Director', 'sitio-cero'); ?></span>
                                            <span class="dm-org__value"><?php echo esc_html($director); ?></span>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ('' !== trim($profesion)) : ?>
                                        <li class="dm-org__item">
                                            <span class="dm-org__label"><?php esc_html_e('Profesion', 'sitio-cero'); ?></span>
                                            <span class="dm-org__value"><?php echo esc_html($profesion); ?></span>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (!empty($telefonos)) : ?>
                                        <li class="dm-org__item">
                                            <span class="dm-org__label"><?php esc_html_e('Telefonos', 'sitio-cero'); ?></span>
                                            <span class="dm-org__value dm-org__value--chips">
                                                <?php foreach ($telefonos as $telefono) : ?>
                                                    <?php
                                                    $phone_text = sanitize_text_field((string) $telefono);
                                                    if ('' === trim($phone_text)) {
                                                        continue;
                                                    }
                                                    $phone_href = preg_replace('/[^0-9+]/', '', $phone_text);
                                                    ?>
                                                    <?php if ('' !== trim((string) $phone_href)) : ?>
                                                        <a class="dm-chip dm-chip--light" href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_text); ?></a>
                                                    <?php else : ?>
                                                        <span class="dm-chip dm-chip--light"><?php echo esc_html($phone_text); ?></span>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php if ('' !== trim($map_link)) : ?>
                                                    <a class="dm-chip dm-chip--light dm-chip--icon" href="<?php echo esc_url($map_link); ?>" target="_blank" rel="noopener">
                                                        <span class="material-symbols-rounded" aria-hidden="true">location_on</span>
                                                        <span><?php esc_html_e('Mapa', 'sitio-cero'); ?></span>
                                                    </a>
                                                <?php endif; ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ('' !== trim($email)) : ?>
                                        <li class="dm-org__item">
                                            <span class="dm-org__label"><?php esc_html_e('Email', 'sitio-cero'); ?></span>
                                            <span class="dm-org__value"><a class="dm-link" href="mailto:<?php echo esc_attr(sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a></span>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ('' !== trim($direccion)) : ?>
                                        <li class="dm-org__item">
                                            <span class="dm-org__label"><?php esc_html_e('Direccion', 'sitio-cero'); ?></span>
                                            <span class="dm-org__value"><?php echo esc_html($direccion); ?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <aside id="mapa" class="dm-map">
                                <h3 class="dm-map__title"><?php esc_html_e('Mapa', 'sitio-cero'); ?></h3>
                                <?php if ($has_map) : ?>
                                    <iframe class="dm-map__iframe" src="<?php echo esc_url($map_src); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                                <?php else : ?>
                                    <p class="dm-map__empty"><?php esc_html_e('Agrega una direccion o URL de mapa para mostrar la georeferencia.', 'sitio-cero'); ?></p>
                                <?php endif; ?>
                            </aside>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($has_resources) : ?>
                <section id="recursos" class="pc-section pc-section--paper">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Recursos', 'sitio-cero'); ?></p>
                            <h2><?php echo esc_html('' !== trim($recursos_titulo) ? $recursos_titulo : __('Documentos y archivos', 'sitio-cero')); ?></h2>
                        </header>
                        <div class="pc-accordion" aria-label="<?php esc_attr_e('Documentos y archivos', 'sitio-cero'); ?>">
                            <?php foreach ($resource_blocks_view as $block) : ?>
                                <?php
                                if (!is_array($block)) {
                                    continue;
                                }
                                $block_title = isset($block['title']) ? sanitize_text_field((string) $block['title']) : '';
                                $block_html = isset($block['html']) ? (string) $block['html'] : '';
                                $block_items = isset($block['items']) && is_array($block['items']) ? $block['items'] : array();
                                if ('' === $block_title && empty($block_items) && '' === trim($block_html)) {
                                    continue;
                                }
                                ?>
                                <details>
                                    <summary><?php echo esc_html($block_title); ?></summary>
                                    <div class="pc-accordion__body pc-flow">
                                        <?php if ('' !== trim($block_html)) : ?>
                                            <div class="pc-flow"><?php echo $block_html; ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($block_items)) : ?>
                                            <ul class="pc-list">
                                                <?php foreach ($block_items as $item) : ?>
                                                    <?php if (!is_array($item) || empty($item['url'])) {
                                                        continue;
                                                    } ?>
                                                    <li>
                                                        <a class="pc-link" href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                            <?php echo esc_html(isset($item['label']) ? (string) $item['label'] : __('Documento', 'sitio-cero')); ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </details>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($has_custom) : ?>
                <section id="extra" class="pc-section">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Complemento', 'sitio-cero'); ?></p>
                            <h2><?php esc_html_e('Informacion adicional', 'sitio-cero'); ?></h2>
                        </header>
                        <div class="pc-flow content-body dm-content">
                            <?php echo $custom_html_output; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($has_sections) : ?>
                <?php foreach ($sections_view as $section_item) : ?>
                    <?php
                    if (!is_array($section_item)) {
                        continue;
                    }

                    $section_id = isset($section_item['id']) ? (string) $section_item['id'] : '';
                    $section_title = isset($section_item['title']) ? (string) $section_item['title'] : '';
                    $section_kicker = isset($section_item['kicker']) ? (string) $section_item['kicker'] : '';
                    $section_content = isset($section_item['content']) ? (string) $section_item['content'] : '';
                    $section_style = isset($section_item['style']) ? (string) $section_item['style'] : '';
                    $section_buttons_style = isset($section_item['buttons_style']) ? (string) $section_item['buttons_style'] : 'pill';
                    $section_buttons = isset($section_item['buttons']) && is_array($section_item['buttons']) ? $section_item['buttons'] : array();
                    $section_accordions = isset($section_item['accordions']) && is_array($section_item['accordions']) ? $section_item['accordions'] : array();
                    $section_subtabs = isset($section_item['subtabs']) && is_array($section_item['subtabs']) ? $section_item['subtabs'] : array();

                    if ('' === $section_id) {
                        continue;
                    }

                    $section_class = 'pc-section';
                    if ('' !== $section_style) {
                        $section_class .= ' pc-section--' . $section_style;
                    }
                    ?>
                    <section id="<?php echo esc_attr($section_id); ?>" class="<?php echo esc_attr($section_class); ?>">
                        <div class="pc-section__inner">
                            <?php if ('' !== $section_kicker || '' !== $section_title) : ?>
                                <header class="pc-section__header">
                                    <?php if ('' !== $section_kicker) : ?>
                                        <p class="pc-kicker"><?php echo esc_html($section_kicker); ?></p>
                                    <?php endif; ?>
                                    <?php if ('' !== $section_title) : ?>
                                        <h2><?php echo esc_html($section_title); ?></h2>
                                    <?php endif; ?>
                                </header>
                            <?php endif; ?>

                            <?php if ('' !== trim($section_content)) : ?>
                                <div class="pc-flow content-body dm-content">
                                    <?php echo $section_content; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($section_buttons)) : ?>
                                <?php
                                $valid_button_styles = array('pill', 'card', 'card-dark');
                                if (!in_array($section_buttons_style, $valid_button_styles, true)) {
                                    $section_buttons_style = 'pill';
                                }

                                $buttons_wrapper = 'pc-hero__actions dm-section-buttons dm-section-buttons--' . $section_buttons_style;
                                $button_class = 'pc-card';
                                if ('card' === $section_buttons_style) {
                                    $buttons_wrapper = 'pc-grid dm-section-buttons dm-section-buttons--' . $section_buttons_style;
                                    $button_class = 'pc-pill';
                                } elseif ('card-dark' === $section_buttons_style) {
                                    $buttons_wrapper = 'pc-grid dm-section-buttons dm-section-buttons--' . $section_buttons_style;
                                    $button_class = 'pc-card pc-card--dark';
                                }
                                ?>
                                <div class="<?php echo esc_attr($buttons_wrapper); ?>" aria-label="<?php esc_attr_e('Botones de la sección', 'sitio-cero'); ?>">
                                    <?php foreach ($section_buttons as $button) : ?>
                                        <?php
                                        if (!is_array($button)) {
                                            continue;
                                        }
                                        $button_label = isset($button['label']) ? sanitize_text_field((string) $button['label']) : '';
                                        $button_url = isset($button['url']) ? esc_url((string) $button['url']) : '';
                                        $button_target = isset($button['target']) && '_blank' === $button['target'] ? '_blank' : '';
                                        if ('' === $button_label || '' === $button_url) {
                                            continue;
                                        }
                                        ?>
                                        <a class="<?php echo esc_attr($button_class); ?>" href="<?php echo esc_url($button_url); ?>"<?php echo '' !== $button_target ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html($button_label); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($section_accordions)) : ?>
                                <div class="direccion-municipal-accordion-wrap">
                                    <div class="direccion-municipal-accordion" data-direccion-accordion>
                                        <?php foreach ($section_accordions as $accordion_index => $accordion) : ?>
                                            <?php
                                            if (!is_array($accordion)) {
                                                continue;
                                            }
                                            $accordion_title = isset($accordion['title']) ? sanitize_text_field((string) $accordion['title']) : '';
                                            $accordion_content = isset($accordion['content']) ? (string) $accordion['content'] : '';
                                            if ('' === $accordion_title) {
                                                continue;
                                            }
                                            $is_open = 0 === $accordion_index;
                                            ?>
                                            <div class="direccion-municipal-accordion__item" data-direccion-accordion-item>
                                                <button class="direccion-municipal-accordion__toggle" type="button" data-direccion-accordion-toggle aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                                                    <span><?php echo esc_html($accordion_title); ?></span>
                                                    <span class="material-symbols-rounded direccion-municipal-accordion__icon" aria-hidden="true">expand_more</span>
                                                </button>
                                                <div class="direccion-municipal-accordion__panel pc-flow" data-direccion-accordion-panel<?php echo $is_open ? '' : ' hidden'; ?>>
                                                    <?php echo $accordion_content; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($section_subtabs)) : ?>
                                <div class="direccion-municipal-subtabs" data-direccion-subtabs>
                                    <div class="elementor-accordion">
                                        <?php foreach ($section_subtabs as $subtab_index => $subtab) : ?>
                                            <?php
                                            if (!is_array($subtab)) {
                                                continue;
                                            }
                                            $subtab_title = isset($subtab['title']) ? sanitize_text_field((string) $subtab['title']) : '';
                                            $subtab_content = isset($subtab['content']) ? (string) $subtab['content'] : '';
                                            if ('' === $subtab_title) {
                                                continue;
                                            }
                                            ?>
                                            <div class="elementor-accordion-item" data-direccion-subtab-item>
                                                <div class="elementor-tab-title" data-direccion-subtab-toggle role="button" tabindex="0" aria-expanded="false">
                                                    <span class="elementor-accordion-title"><?php echo esc_html($subtab_title); ?></span>
                                                    <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                                        <span class="elementor-accordion-icon-closed">+</span>
                                                        <span class="elementor-accordion-icon-opened">-</span>
                                                    </span>
                                                </div>
                                                <div class="elementor-tab-content" data-direccion-subtab-panel>
                                                    <?php echo $subtab_content; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
    <?php endwhile; else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
