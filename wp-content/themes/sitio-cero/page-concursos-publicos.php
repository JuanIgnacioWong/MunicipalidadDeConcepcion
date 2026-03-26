<?php
/*
Template Name: Concursos Públicos
*/
get_header();
?>

<main id="content" class="site-main pc-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('pc-article'); ?>>
            <div class="pc-page">
                <section class="pc-hero">
                    <div class="pc-hero__inner">
                        <p class="pc-hero__eyebrow"><?php esc_html_e('Municipalidad de Concepción', 'sitio-cero'); ?></p>
                        <h1 class="pc-hero__title"><?php echo esc_html(get_the_title()); ?></h1>
                        <p class="pc-hero__lead">
                            <?php
                            $hero_lead = get_the_excerpt();
                            if ('' === trim($hero_lead)) {
                                $hero_lead = __('Repositorio de concursos públicos vigentes y cerrados, con sus documentos oficiales y antecedentes.', 'sitio-cero');
                            }
                            echo esc_html($hero_lead);
                            ?>
                        </p>
                    </div>
                </section>

                <section class="pc-section pc-section--soft" id="concursos-activos">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Concursos', 'sitio-cero'); ?></p>
                            <h2><?php esc_html_e('Concursos Públicos Activos', 'sitio-cero'); ?></h2>
                        </header>
                        <?php
                        $concursos_activos = get_posts(
                            array(
                                'post_type'      => 'concurso_publico',
                                'post_status'    => 'publish',
                                'posts_per_page' => -1,
                                'no_found_rows'  => true,
                                'orderby'        => array(
                                    'menu_order' => 'ASC',
                                    'date'       => 'DESC',
                                ),
                                'meta_query'     => array(
                                    'relation' => 'OR',
                                    array(
                                        'key'     => 'sitio_cero_concurso_estado',
                                        'value'   => 'activo',
                                        'compare' => '=',
                                    ),
                                    array(
                                        'key'     => 'sitio_cero_concurso_estado',
                                        'compare' => 'NOT EXISTS',
                                    ),
                                ),
                            )
                        );
                        sitio_cero_render_concursos_publicos_accordion($concursos_activos);
                        ?>
                    </div>
                </section>

                <section class="pc-section pc-section--paper" id="concursos-inactivos">
                    <div class="pc-section__inner">
                        <header class="pc-section__header">
                            <p class="pc-kicker"><?php esc_html_e('Concursos', 'sitio-cero'); ?></p>
                            <h2><?php esc_html_e('Concursos Públicos Inactivos', 'sitio-cero'); ?></h2>
                        </header>
                        <?php
                        $concursos_inactivos = get_posts(
                            array(
                                'post_type'      => 'concurso_publico',
                                'post_status'    => 'publish',
                                'posts_per_page' => -1,
                                'no_found_rows'  => true,
                                'orderby'        => array(
                                    'menu_order' => 'ASC',
                                    'date'       => 'DESC',
                                ),
                                'meta_query'     => array(
                                    array(
                                        'key'     => 'sitio_cero_concurso_estado',
                                        'value'   => 'inactivo',
                                        'compare' => '=',
                                    ),
                                ),
                            )
                        );
                        sitio_cero_render_concursos_publicos_accordion($concursos_inactivos);
                        ?>
                    </div>
                </section>
            </div>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
?>
