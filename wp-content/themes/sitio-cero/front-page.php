<?php
get_header();
?>

<main id="content" class="site-main">
    <?php
    $news_archive_url = get_post_type_archive_link('noticia');
    $news_category_term = get_term_by('slug', 'noticias', 'categoria_noticia');
    if ($news_category_term instanceof WP_Term) {
        $news_category_link = get_term_link($news_category_term);
        if (!is_wp_error($news_category_link)) {
            $news_archive_url = $news_category_link;
        }
    }
    $hero_slider_query = new WP_Query(
        array(
            'post_type'      => 'lamina_hero',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
        )
    );
    $hero_total_slides = (int) $hero_slider_query->post_count;

    if (!$news_archive_url) {
        $news_archive_url = home_url('/');
    }

    ?>

    <section class="hero">
        <div class="container">
            <div class="row gx-md-0 hero-row">
                <div class="col-lg-8 col-sm-12 p-0 carrusel radius">
                    <?php if ($hero_slider_query->have_posts()) : ?>
                        <div id="carouselSlider" class="hero-slider carousel slide" data-hero-slider data-autoplay="true">
                            <?php if ($hero_total_slides > 1) : ?>
                                <div class="carousel-indicators hero-slider__dots" role="tablist" aria-label="Navegacion del hero">
                                    <?php for ($i = 0; $i < $hero_total_slides; $i++) : ?>
                                        <button
                                            class="hero-slider__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
                                            type="button"
                                            data-slide-dot="<?php echo esc_attr((string) $i); ?>"
                                            role="tab"
                                            aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
                                            aria-label="<?php echo esc_attr(sprintf(__('Ir al slide %d', 'sitio-cero'), $i + 1)); ?>"
                                        ></button>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-inner hero-slider__track">
                                <?php
                                $slide_index = 0;
                                while ($hero_slider_query->have_posts()) :
                                    $hero_slider_query->the_post();
                                    $slide_classes = 'hero-slide carousel-item';
                                    if (0 === $slide_index) {
                                        $slide_classes .= ' is-active active';
                                    }

                                    $hero_cta_url = trim((string) get_post_meta(get_the_ID(), 'sitio_cero_hero_cta_url', true));
                                    if ('' === $hero_cta_url) {
                                        $hero_cta_url = trim((string) get_post_meta(get_the_ID(), 'hero_cta_url', true));
                                    }
                                    if ('' === $hero_cta_url) {
                                        $hero_cta_url = '#';
                                    }
                                    ?>
                                    <article
                                        class="<?php echo esc_attr($slide_classes); ?>"
                                        data-slide
                                        aria-hidden="<?php echo 0 === $slide_index ? 'false' : 'true'; ?>"
                                    >
                                        <div class="hero-slide__media">
                                            <a href="<?php echo esc_url($hero_cta_url); ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('full', array('class' => 'hero-slide__image', 'loading' => 'lazy')); ?>
                                                <?php else : ?>
                                                    <span class="hero-slide__image hero-slide__image--placeholder">
                                                        <?php esc_html_e('Sin imagen destacada', 'sitio-cero'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    </article>
                                    <?php
                                    $slide_index++;
                                endwhile;
                                ?>
                            </div>

                            <?php if ($hero_total_slides > 1) : ?>
                                <button class="carousel-control-prev hero-slider__arrow hero-slider__arrow--prev" type="button" data-slide-prev aria-label="Slide anterior">
                                    <span class="ims-carrusel-icon">&#x276E;</span>
                                </button>
                                <button class="carousel-control-next hero-slider__arrow hero-slider__arrow--next" type="button" data-slide-next aria-label="Slide siguiente">
                                    <span class="ims-carrusel-icon">&#x276F;</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div class="hero-slider hero-slider--fallback">
                            <div class="hero-slider__track">
                                <article class="hero-slide is-active active" data-slide aria-hidden="false">
                                    <div class="hero-slide__media">
                                        <span class="hero-slide__image hero-slide__image--placeholder">
                                            <?php esc_html_e('Crea laminas en Laminas Hero para mostrar el carrusel.', 'sitio-cero'); ?>
                                        </span>
                                    </div>
                                </article>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 col-sm-12 servicios p-md-4 px-0 pb-4 pt-2 llamados">
                    <h3 class="hero-info-title">
                        <span class="material-symbols-rounded hero-info-title__icon" aria-hidden="true">info</span>
                        <span><?php esc_html_e('Informacion de', 'sitio-cero'); ?></span>
                    </h3>
                    <nav class="navbar navbar-default navbar-expand-lg pt-md-3 p-0">
                        <div class="collapse navbar-collapse text-center text-md-start show" id="quiero-info">
                            <div class="acciones pb-4">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'hero_info',
                                        'container'      => false,
                                        'menu_id'        => 'menu-quiero-informacion-de',
                                        'menu_class'     => 'acciones-bt',
                                        'depth'          => 1,
                                        'fallback_cb'    => 'sitio_cero_hero_info_menu_fallback',
                                        'walker'         => new Sitio_Cero_Hero_Info_Menu_Walker(),
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <section id="noticias" class="section">
        <div class="container">
            <div class="section__header section__header--split">
                <div>
                    <p class="eyebrow">Actualidad</p>
                    <h2>Noticias municipales</h2>
                </div>
                <a class="section-link" href="<?php echo esc_url($news_archive_url); ?>">Ver todas</a>
            </div>

            <?php
            $latest_posts = new WP_Query(
                array(
                    'post_type'           => 'noticia',
                    'post_status'         => 'publish',
                    'posts_per_page'      => 4,
                    'ignore_sticky_posts' => true,
                    'orderby'             => 'date',
                    'order'               => 'DESC',
                    'tax_query'           => array(
                        array(
                            'taxonomy' => 'categoria_noticia',
                            'field'    => 'slug',
                            'terms'    => 'noticias',
                        ),
                    ),
                )
            );
            ?>

            <?php if ($latest_posts->have_posts()) : ?>
                <div class="news-grid">
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                        <article <?php post_class('news-card'); ?>>
                            <a class="news-card__media" href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'news-card__image', 'loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <span class="news-card__image news-card__image--placeholder">Sin imagen</span>
                                <?php endif; ?>
                            </a>

                            <div class="news-card__body">
                                <p class="news-card__meta"><?php echo esc_html(get_the_date('d M Y')); ?></p>
                                <h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                <a class="news-card__link" href="<?php the_permalink(); ?>">Leer noticia</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
            <div class="news-section__actions">
                <a class="button" href="<?php echo esc_url($news_archive_url); ?>"><?php esc_html_e('Leer mas noticias', 'sitio-cero'); ?></a>
            </div>
        </div>
    </section>

    <section id="tramites" class="section section--paper">
        <div class="container">
            <?php
            $tramites_query = new WP_Query(
                array(
                    'post_type'      => 'tramite_servicio',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => array(
                        'menu_order' => 'ASC',
                        'date'       => 'DESC',
                    ),
                )
            );
            $tramites_inline_css = '';
            ?>

            <?php if ($tramites_query->have_posts()) : ?>
                <div class="service-grid">
                    <?php while ($tramites_query->have_posts()) : $tramites_query->the_post(); ?>
                        <?php
                        $tramite_id = get_the_ID();
                        $tramite_url = trim((string) get_post_meta($tramite_id, 'sitio_cero_tramite_url', true));
                        if ('' === $tramite_url) {
                            $tramite_url = '#';
                        }

                        $tramite_custom_html = get_post_meta($tramite_id, 'sitio_cero_tramite_custom_html', true);
                        if (!is_string($tramite_custom_html)) {
                            $tramite_custom_html = '';
                        }

                        $tramite_bg_color = get_post_meta($tramite_id, 'sitio_cero_tramite_bg_color', true);
                        if (!is_string($tramite_bg_color)) {
                            $tramite_bg_color = '';
                        }
                        $tramite_bg_color = sanitize_hex_color($tramite_bg_color);

                        $tramite_style = '';
                        if (is_string($tramite_bg_color) && '' !== $tramite_bg_color) {
                            $tramite_style = '--service-pastel-bg:' . $tramite_bg_color . ';';
                        }

                        $tramite_custom_css = trim((string) get_post_meta($tramite_id, 'sitio_cero_tramite_custom_css', true));
                        if ('' !== $tramite_custom_css) {
                            $card_selector = '.tramite-custom-' . $tramite_id;
                            if (false !== strpos($tramite_custom_css, '{')) {
                                $css_rule = str_replace('{{selector}}', $card_selector, $tramite_custom_css);
                            } else {
                                $css_rule = $card_selector . '{' . $tramite_custom_css . '}';
                            }
                            $tramites_inline_css .= $css_rule . "\n";
                        }
                        ?>
                        <article class="service-card tramite-custom-<?php echo esc_attr((string) $tramite_id); ?>"<?php echo '' !== $tramite_style ? ' style="' . esc_attr($tramite_style) . '"' : ''; ?>>
                            <a class="service-card__link" href="<?php echo esc_url($tramite_url); ?>">
                                <div class="service-card__body">
                                    <?php
                                    if ('' !== trim($tramite_custom_html)) {
                                        echo do_shortcode(wp_kses_post($tramite_custom_html));
                                    } else {
                                        echo wp_kses_post(apply_filters('the_content', get_the_content()));
                                    }
                                    ?>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php if ('' !== trim($tramites_inline_css)) : ?>
                    <style><?php echo wp_strip_all_tags($tramites_inline_css); ?></style>
                <?php endif; ?>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <section class="section section--light">
        <div class="container info-grid">
            <article class="info-panel">
                <p class="eyebrow">Quiero informacion de...</p>
                <h2>Temas ciudadanos</h2>
                <div class="topic-grid">
                    <a class="topic-pill" href="#">Aseo y ornato</a>
                    <a class="topic-pill" href="#">Seguridad publica</a>
                    <a class="topic-pill" href="#">Transito y movilidad</a>
                    <a class="topic-pill" href="#">Cultura y deporte</a>
                    <a class="topic-pill" href="#">Salud comunal</a>
                    <a class="topic-pill" href="#">Educacion municipal</a>
                </div>
            </article>

            <article class="info-panel info-panel--notice">
                <p class="eyebrow">Aviso ciudadano</p>
                <h2>Canal de reportes y emergencias urbanas</h2>
                <p>
                    Si detectas luminarias apagadas, semaforos con falla o situacion de riesgo vial, ingresa tu reporte en linea y recibe seguimiento.
                </p>
                <a class="button button--light" href="#canales">Ir a canales de atencion</a>
            </article>
        </div>
    </section>

    <section id="agenda" class="section section--accent">
        <div class="container agenda-grid">
            <article class="agenda-card">
                <p class="eyebrow">Agenda comunal</p>
                <h2>Proximas actividades</h2>
                <ul class="agenda-list">
                    <li>
                        <span class="agenda-date">18 FEB</span>
                        <div>
                            <h3>Operativo de limpieza barrial</h3>
                            <p>Plaza principal - 09:30 hrs</p>
                        </div>
                    </li>
                    <li>
                        <span class="agenda-date">21 FEB</span>
                        <div>
                            <h3>Feria de servicios municipales</h3>
                            <p>Centro comunitario - 11:00 hrs</p>
                        </div>
                    </li>
                    <li>
                        <span class="agenda-date">27 FEB</span>
                        <div>
                            <h3>Cabildo ciudadano sector norte</h3>
                            <p>Sede vecinal 12 - 18:30 hrs</p>
                        </div>
                    </li>
                </ul>
            </article>

            <article class="agenda-card agenda-card--channels" id="canales">
                <p class="eyebrow">Atencion ciudadana</p>
                <h2>Canales para resolver tus consultas</h2>
                <ul class="channel-list">
                    <li><strong>Presencial:</strong> Lunes a viernes, 08:30 a 14:00 hrs.</li>
                    <li><strong>Telefonico:</strong> +56 2 3386 8000.</li>
                    <li><strong>Correo:</strong> contacto@municipio.cl.</li>
                    <li><strong>Oficina virtual:</strong> Tramites, solicitudes y seguimiento.</li>
                </ul>
                <a class="button button--light" href="#">Ingresar a oficina virtual</a>
            </article>
        </div>
    </section>
</main>

<?php
get_footer();
