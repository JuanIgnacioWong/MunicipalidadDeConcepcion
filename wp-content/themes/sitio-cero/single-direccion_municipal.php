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

        $accordion_items = function_exists('sitio_cero_get_direccion_accordion_items')
            ? sitio_cero_get_direccion_accordion_items($post_id)
            : array();

        $allowed_html = function_exists('sitio_cero_get_direccion_allowed_html')
            ? sitio_cero_get_direccion_allowed_html()
            : wp_kses_allowed_html('post');

        $custom_html_output = '';
        if ('' !== trim($custom_html)) {
            $custom_html_output = wp_kses($custom_html, $allowed_html);
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

            <?php if (!empty($accordion_items)) : ?>
                <section class="direccion-municipal-accordion-wrap">
                    <h2><?php esc_html_e('Tematicas y pestañas', 'sitio-cero'); ?></h2>
                    <div class="direccion-municipal-accordion" data-direccion-accordion>
                        <?php foreach ($accordion_items as $index => $item) : ?>
                            <?php
                            if (!is_array($item)) {
                                continue;
                            }

                            $item_title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
                            $item_border = isset($item['border']) ? (string) $item['border'] : '';
                            $item_margin = isset($item['margin']) ? (string) $item['margin'] : '';
                            $item_padding = isset($item['padding']) ? (string) $item['padding'] : '';
                            $item_subtabs = isset($item['subtabs']) && is_array($item['subtabs']) ? $item['subtabs'] : array();
                            $is_open = 0 === (int) $index;

                            $style_parts = array();
                            if ('' !== $item_border) {
                                $style_parts[] = '--dm-acc-border:' . $item_border;
                            }
                            if ('' !== $item_margin) {
                                $style_parts[] = '--dm-acc-margin:' . $item_margin;
                            }
                            if ('' !== $item_padding) {
                                $style_parts[] = '--dm-acc-padding:' . $item_padding;
                            }
                            ?>
                            <article class="direccion-municipal-accordion__item" data-direccion-accordion-item style="<?php echo esc_attr(implode(';', $style_parts)); ?>">
                                <button type="button" class="direccion-municipal-accordion__toggle" data-direccion-accordion-toggle aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                                    <span><?php echo esc_html($item_title); ?></span>
                                    <span class="material-symbols-rounded direccion-municipal-accordion__icon" aria-hidden="true">expand_more</span>
                                </button>
                                <div class="direccion-municipal-accordion__panel" data-direccion-accordion-panel <?php echo $is_open ? '' : 'hidden'; ?>>
                                    <?php if (!empty($item_subtabs)) : ?>
                                        <div class="direccion-municipal-subtabs elementor-accordion" data-direccion-subtabs>
                                            <?php foreach ($item_subtabs as $subtab_index => $subtab) : ?>
                                                <?php
                                                if (!is_array($subtab)) {
                                                    continue;
                                                }
                                                $subtab_title = isset($subtab['title']) ? sanitize_text_field((string) $subtab['title']) : '';
                                                $subtab_content = isset($subtab['content']) ? wp_kses((string) $subtab['content'], $allowed_html) : '';
                                                $subtab_number = (int) $subtab_index + 1;
                                                $subtab_id = 'direccion-subtab-' . $post_id . '-' . $index . '-' . $subtab_index;
                                                ?>
                                                <article class="direccion-municipal-subtabs__item elementor-accordion-item" data-direccion-subtab-item>
                                                    <div
                                                        id="elementor-tab-title-<?php echo esc_attr($subtab_id); ?>"
                                                        class="direccion-municipal-subtabs__toggle elementor-tab-title"
                                                        data-direccion-subtab-toggle
                                                        data-tab="<?php echo esc_attr((string) $subtab_number); ?>"
                                                        role="button"
                                                        tabindex="0"
                                                        aria-controls="elementor-tab-content-<?php echo esc_attr($subtab_id); ?>"
                                                        aria-expanded="false"
                                                    >
                                                        <span class="elementor-accordion-icon elementor-accordion-icon-left" aria-hidden="true">
                                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus" aria-hidden="true">+</i></span>
                                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus" aria-hidden="true">-</i></span>
                                                        </span>
                                                        <a class="elementor-accordion-title" tabindex="0"><?php echo esc_html($subtab_title); ?></a>
                                                    </div>
                                                    <div
                                                        id="elementor-tab-content-<?php echo esc_attr($subtab_id); ?>"
                                                        class="direccion-municipal-subtabs__panel elementor-tab-content elementor-clearfix"
                                                        data-direccion-subtab-panel
                                                        data-tab="<?php echo esc_attr((string) $subtab_number); ?>"
                                                        role="region"
                                                        aria-labelledby="elementor-tab-title-<?php echo esc_attr($subtab_id); ?>"
                                                    >
                                                        <?php echo $subtab_content; ?>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <p class="direccion-municipal-subtabs__empty"><?php esc_html_e('Sin pestañas en este item.', 'sitio-cero'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
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
