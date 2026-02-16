<?php
get_header();
?>

<main id="content" class="site-main section container content-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header class="section__header">
                <h1><?php the_title(); ?></h1>
            </header>

            <div class="content-body">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
