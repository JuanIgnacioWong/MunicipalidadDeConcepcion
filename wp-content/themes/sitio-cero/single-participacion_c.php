<?php
get_header();
?>

<main id="content" class="site-main pc-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('pc-article'); ?>>
            <?php
            remove_filter('the_content', 'wpautop');
            remove_filter('the_content', 'wptexturize');
            the_content();
            add_filter('the_content', 'wpautop');
            add_filter('the_content', 'wptexturize');
            ?>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
?>
