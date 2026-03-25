<?php
get_header();
?>

<main id="content" class="site-main pc-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('pc-article'); ?>>
            <?php sitio_cero_render_participacion_ciudadana(get_the_ID()); ?>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
?>
