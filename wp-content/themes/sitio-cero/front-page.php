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
                                    if ('#tramites' === $hero_cta_url) {
                                        $hero_cta_url = '#avisos';
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
                                <?php if ('noticia' === get_post_type()) : ?>
                                    <p class="news-card__meta"><?php echo esc_html(get_the_date('d M Y')); ?></p>
                                <?php endif; ?>
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

    <section id="avisos" class="section section--paper section--avisos-full">
        <div class="container avisos-section__inner">
            <?php
            $avisos_query = new WP_Query(
                array(
                    'post_type'      => 'aviso',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => array(
                        'menu_order' => 'ASC',
                        'date'       => 'DESC',
                    ),
                )
            );
            $avisos_total = (int) $avisos_query->post_count;
            ?>

            <div class="section__header section__header--split">
                <div>
                    <h2><?php esc_html_e('Avisos municipales', 'sitio-cero'); ?></h2>
                </div>
            </div>

            <?php if ($avisos_query->have_posts()) : ?>
                <div class="avisos-carousel" data-avisos-carousel>
                    <div class="avisos-carousel__viewport" data-avisos-viewport>
                        <div class="avisos-carousel__track" data-avisos-track>
                            <?php
                            while ($avisos_query->have_posts()) :
                                $avisos_query->the_post();
                                $aviso_image_url = function_exists('sitio_cero_get_aviso_image_url')
                                    ? sitio_cero_get_aviso_image_url(get_the_ID(), 'large')
                                    : '';
                                ?>
                                <article class="aviso-card" data-aviso-card>
                                    <a class="aviso-card__link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                        <div class="aviso-card__media">
                                            <?php if ('' !== $aviso_image_url) : ?>
                                                <img class="aviso-card__image" src="<?php echo esc_url($aviso_image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                            <?php else : ?>
                                                <span class="aviso-card__image aviso-card__image--placeholder">
                                                    <?php esc_html_e('Sin imagen', 'sitio-cero'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <?php if ($avisos_total > 5) : ?>
                        <div class="avisos-carousel__controls">
                            <button class="avisos-carousel__arrow avisos-carousel__arrow--prev" type="button" data-avisos-prev aria-label="<?php esc_attr_e('Avisos anteriores', 'sitio-cero'); ?>">
                                <span aria-hidden="true">&#x276E;</span>
                            </button>
                            <button class="avisos-carousel__arrow avisos-carousel__arrow--next" type="button" data-avisos-next aria-label="<?php esc_attr_e('Siguientes avisos', 'sitio-cero'); ?>">
                                <span aria-hidden="true">&#x276F;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <p><?php esc_html_e('Aun no hay avisos publicados. Crea avisos desde el menu Avisos del administrador.', 'sitio-cero'); ?></p>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <section class="section section--light">
        <?php
        $canal_ciudadano_query = new WP_Query(
            array(
                'post_type'      => 'canal_ciudadano',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => array(
                    'menu_order' => 'ASC',
                    'date'       => 'DESC',
                ),
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'sitio_cero_canal_visible',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'sitio_cero_canal_visible',
                        'value'   => '1',
                        'compare' => '=',
                    ),
                ),
            )
        );
        $has_canal_ciudadano = $canal_ciudadano_query->have_posts();
        ?>
        <div class="container info-grid<?php echo $has_canal_ciudadano ? '' : ' info-grid--single'; ?>">
            <article class="info-panel">
                <h2>Temas ciudadanos</h2>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'temas_ciudadanos',
                        'container'      => false,
                        'menu_id'        => 'menu-temas-ciudadanos',
                        'menu_class'     => 'topic-grid',
                        'depth'          => 1,
                        'fallback_cb'    => 'sitio_cero_temas_ciudadanos_menu_fallback',
                    )
                );
                ?>
            </article>

            <?php if ($has_canal_ciudadano) : ?>
                <?php
                while ($canal_ciudadano_query->have_posts()) :
                    $canal_ciudadano_query->the_post();
                    $canal_button_label = get_post_meta(get_the_ID(), 'sitio_cero_canal_button_label', true);
                    $canal_button_url = get_post_meta(get_the_ID(), 'sitio_cero_canal_button_url', true);
                    $canal_content_raw = get_post_field('post_content', get_the_ID());

                    if (!is_string($canal_button_label)) {
                        $canal_button_label = '';
                    }
                    if (!is_string($canal_button_url)) {
                        $canal_button_url = '';
                    }
                    if (!is_string($canal_content_raw)) {
                        $canal_content_raw = '';
                    }

                    $canal_content_html = '';
                    if ('' !== trim($canal_content_raw)) {
                        $canal_content_html = apply_filters('the_content', $canal_content_raw);
                    }
                    ?>
                    <article class="info-panel info-panel--notice">
                        <h2><?php the_title(); ?></h2>
                        <?php if ('' !== trim($canal_content_html)) : ?>
                            <?php echo wp_kses_post($canal_content_html); ?>
                        <?php endif; ?>
                        <?php if ('' !== trim($canal_button_label) && '' !== trim($canal_button_url)) : ?>
                            <a class="button button--light" href="<?php echo esc_url($canal_button_url); ?>"><?php echo esc_html($canal_button_label); ?></a>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </section>

    <section id="agenda" class="section section--accent">
        <?php
        $eventos_archive_url = get_post_type_archive_link('evento_municipal');
        if (!is_string($eventos_archive_url) || '' === trim($eventos_archive_url)) {
            $eventos_archive_url = home_url('/#agenda');
        }

        $agenda_events = function_exists('sitio_cero_get_home_eventos')
            ? sitio_cero_get_home_eventos(3)
            : array();
        $has_active_agenda_events = !empty($agenda_events);
        ?>
        <div class="container agenda-grid<?php echo $has_active_agenda_events ? '' : ' agenda-grid--single'; ?>">
            <?php if ($has_active_agenda_events) : ?>
                <article class="agenda-card">
                    <div class="agenda-card__head">
                        <h2><?php esc_html_e('Proximas actividades', 'sitio-cero'); ?></h2>
                    </div>
                    <ul class="agenda-list">
                        <?php foreach ($agenda_events as $agenda_event) : ?>
                            <?php
                            if (!$agenda_event instanceof WP_Post) {
                                continue;
                            }

                            $event_id = (int) $agenda_event->ID;
                            $event_permalink = get_permalink($event_id);
                            if (!is_string($event_permalink) || '' === trim($event_permalink)) {
                                $event_permalink = '#';
                            }

                            $event_badge = function_exists('sitio_cero_get_evento_badge_fecha')
                                ? sitio_cero_get_evento_badge_fecha($event_id)
                                : '';
                            if ('' === $event_badge) {
                                $event_badge = get_the_date('d M', $event_id);
                                if (!is_string($event_badge)) {
                                    $event_badge = '';
                                }
                                if (function_exists('mb_strtoupper')) {
                                    $event_badge = mb_strtoupper($event_badge, 'UTF-8');
                                } else {
                                    $event_badge = strtoupper($event_badge);
                                }
                            }

                            $event_time = function_exists('sitio_cero_get_evento_hora')
                                ? sitio_cero_get_evento_hora($event_id)
                                : '';
                            $event_place = get_post_meta($event_id, 'sitio_cero_evento_lugar', true);
                            $event_map_url = get_post_meta($event_id, 'sitio_cero_evento_mapa_url', true);

                            if (!is_string($event_place)) {
                                $event_place = '';
                            }
                            if (!is_string($event_map_url)) {
                                $event_map_url = '';
                            }

                            $event_details = trim($event_place);
                            if ('' !== $event_time) {
                                $event_details .= ('' !== $event_details ? ' - ' : '') . $event_time . ' hrs';
                            }
                            ?>
                            <li>
                                <span class="agenda-date"><?php echo esc_html($event_badge); ?></span>
                                <div>
                                    <h3><a href="<?php echo esc_url($event_permalink); ?>"><?php echo esc_html(get_the_title($event_id)); ?></a></h3>
                                    <?php if ('' !== $event_details || '' !== trim($event_map_url)) : ?>
                                        <p>
                                            <?php if ('' !== $event_details) : ?>
                                                <?php echo esc_html($event_details); ?>
                                            <?php endif; ?>
                                            <?php if ('' !== trim($event_map_url)) : ?>
                                                <a class="agenda-map-link" href="<?php echo esc_url($event_map_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Ver mapa', 'sitio-cero'); ?></a>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a class="button button--light agenda-card__cta" href="<?php echo esc_url($eventos_archive_url); ?>">
                        <?php esc_html_e('Ver todos los eventos', 'sitio-cero'); ?>
                    </a>
                </article>
            <?php endif; ?>

            <article class="agenda-card agenda-card--channels" id="canales">
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
