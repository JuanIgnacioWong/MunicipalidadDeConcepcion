<?php
get_header();
?>

<main id="content" class="site-main section container content-single direccion-municipal-page">
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
        $custom_css = get_post_meta($post_id, 'sitio_cero_direccion_custom_css', true);

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
        if (!is_string($custom_css)) {
            $custom_css = '';
        }

        $map_src = '';
        $clean_map_url = esc_url_raw($mapa_url);
        if ('' !== $clean_map_url) {
            $map_src = $clean_map_url;
        } elseif ('' !== trim($direccion)) {
            $map_src = 'https://www.google.com/maps?q=' . rawurlencode($direccion) . '&output=embed';
        }

        $recursos_titulo = get_post_meta($post_id, 'sitio_cero_direccion_recursos_titulo', true);
        $resource_blocks = function_exists('sitio_cero_get_direccion_resource_blocks')
            ? sitio_cero_get_direccion_resource_blocks($post_id)
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

        $custom_css_output = '';
        if ('' !== trim($custom_css)) {
            $clean_css = function_exists('sitio_cero_sanitize_tramite_custom_css')
                ? sitio_cero_sanitize_tramite_custom_css($custom_css)
                : trim((string) wp_kses((string) $custom_css, array()));

            if ('' !== $clean_css) {
                $selector = '#direccion-municipal-' . $post_id;
                if (false !== strpos($clean_css, '{{selector}}')) {
                    $custom_css_output = str_replace('{{selector}}', $selector, $clean_css);
                } else {
                    $custom_css_output = $selector . ' { ' . $clean_css . ' }';
                }
            }
        }
        ?>

        <article id="direccion-municipal-<?php the_ID(); ?>" <?php post_class('direccion-municipal-single'); ?>>
            <header class="section__header direccion-municipal-single__header">
                <p class="direccion-municipal-single__kicker"><?php esc_html_e('Direccion municipal', 'sitio-cero'); ?></p>
                <h1><?php the_title(); ?></h1>
            </header>

            <?php if ('' !== trim((string) get_the_content())) : ?>
                <section class="direccion-municipal-content content-body">
                    <?php the_content(); ?>
                </section>
            <?php endif; ?>

            <div class="direccion-municipal-single__intro">
                <section class="direccion-municipal-org">
                    <h2><?php esc_html_e('Organizacion', 'sitio-cero'); ?></h2>
                    <ul class="direccion-municipal-org__list">
                        <?php if ('' !== trim($director)) : ?>
                            <li data-type="director">
                                <span class="direccion-municipal-org__label"><?php esc_html_e('Director:', 'sitio-cero'); ?></span>
                                <span><?php echo esc_html($director); ?></span>
                            </li>
                        <?php endif; ?>

                        <?php if ('' !== trim($profesion)) : ?>
                            <li data-type="profesion">
                                <span class="direccion-municipal-org__label"><?php esc_html_e('Profesion:', 'sitio-cero'); ?></span>
                                <span><?php echo esc_html($profesion); ?></span>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($telefonos)) : ?>
                            <li data-type="telefonos">
                                <span class="direccion-municipal-org__label"><?php esc_html_e('Telefonos:', 'sitio-cero'); ?></span>
                                <span class="direccion-municipal-org__phones">
                                    <?php foreach ($telefonos as $telefono) : ?>
                                        <?php
                                        $phone_text = sanitize_text_field((string) $telefono);
                                        if ('' === trim($phone_text)) {
                                            continue;
                                        }
                                        $phone_href = preg_replace('/[^0-9+]/', '', $phone_text);
                                        ?>
                                        <?php if ('' !== trim((string) $phone_href)) : ?>
                                            <a class="direccion-municipal-org__chip" href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_text); ?></a>
                                        <?php else : ?>
                                            <span class="direccion-municipal-org__chip"><?php echo esc_html($phone_text); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </span>
                            </li>
                        <?php endif; ?>

                        <?php if ('' !== trim($email)) : ?>
                            <li data-type="email">
                                <span class="direccion-municipal-org__label"><?php esc_html_e('Email:', 'sitio-cero'); ?></span>
                                <a class="direccion-municipal-org__mail" href="mailto:<?php echo esc_attr(sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ('' !== trim($direccion)) : ?>
                            <li data-type="direccion">
                                <span class="direccion-municipal-org__label"><?php esc_html_e('Direccion:', 'sitio-cero'); ?></span>
                                <span><?php echo esc_html($direccion); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </section>

                <aside class="direccion-municipal-map">
                    <h2><?php esc_html_e('Mapa', 'sitio-cero'); ?></h2>
                    <?php if ('' !== $map_src) : ?>
                        <iframe class="direccion-municipal-map__iframe" src="<?php echo esc_url($map_src); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    <?php else : ?>
                        <p class="direccion-municipal-map__empty"><?php esc_html_e('Agrega una direccion o URL de mapa para mostrar la georeferencia.', 'sitio-cero'); ?></p>
                    <?php endif; ?>
                </aside>
            </div>

            <?php if (!empty($resource_blocks_view)) : ?>
                <section class="direccion-municipal-accordion-wrap">
                    <h2><?php echo esc_html('' !== trim($recursos_titulo) ? $recursos_titulo : __('Documentos y archivos', 'sitio-cero')); ?></h2>
                    <div class="aviso-single-layout__left">
                        <?php foreach ($resource_blocks_view as $block) : ?>
                            <?php
                            if (!is_array($block)) {
                                continue;
                            }
                            $block_title = isset($block['title']) ? sanitize_text_field((string) $block['title']) : '';
                            $block_type = isset($block['type']) ? sanitize_key((string) $block['type']) : 'documentos';
                            $block_html = isset($block['html']) ? (string) $block['html'] : '';
                            $block_items = isset($block['items']) && is_array($block['items']) ? $block['items'] : array();
                            ?>
                            <section class="aviso-single-block">
                                <h2><?php echo esc_html($block_title); ?></h2>
                                <?php if ('' !== trim($block_html)) : ?>
                                    <div class="aviso-single-block__content">
                                        <?php echo $block_html; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($block_items)) : ?>
                                    <ul class="aviso-single-list">
                                        <?php foreach ($block_items as $item) : ?>
                                            <?php if (!is_array($item) || empty($item['url'])) {
                                                continue;
                                            } ?>
                                            <?php
                                            $icon_key = isset($item['icon']) ? (string) $item['icon'] : '';
                                            if ('' === $icon_key && function_exists('sitio_cero_detect_aviso_file_icon_by_url')) {
                                                $icon_key = sitio_cero_detect_aviso_file_icon_by_url((string) $item['url']);
                                            }
                                            $icon_key = is_string($icon_key) ? $icon_key : 'file';
                                            $icon_symbol = function_exists('sitio_cero_get_aviso_file_icon_symbol')
                                                ? sitio_cero_get_aviso_file_icon_symbol($icon_key)
                                                : 'attach_file';
                                            $fallback_label = 'archivos' === $block_type ? 'Archivo' : 'Documento';
                                            ?>
                                            <li>
                                                <a class="aviso-single-file-btn aviso-single-file-btn--<?php echo esc_attr($icon_key); ?>" href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                    <span class="material-symbols-rounded aviso-single-file-btn__icon" aria-hidden="true"><?php echo esc_html($icon_symbol); ?></span>
                                                    <span class="aviso-single-file-btn__text"><?php echo esc_html(isset($item['label']) ? (string) $item['label'] : $fallback_label); ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ('' !== $custom_html_output) : ?>
                <section class="direccion-municipal-custom content-body">
                    <?php echo $custom_html_output; ?>
                </section>
            <?php endif; ?>

            <?php if ('' !== trim($custom_css_output)) : ?>
                <style><?php echo esc_html($custom_css_output); ?></style>
            <?php endif; ?>
        </article>
    <?php endwhile; else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
