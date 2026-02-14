<?php
get_header();
?>

<main id="content" class="site-main section container">
    <?php if (have_posts()) : ?>
        <?php
        $archive_title = get_the_archive_title();
        if (!is_string($archive_title) || '' === trim($archive_title)) {
            $archive_title = single_post_title('', false);
        }
        if (!is_string($archive_title) || '' === trim($archive_title)) {
            $archive_title = __('Noticias', 'sitio-cero');
        }
        $archive_description = get_the_archive_description();
        ?>
        <header class="section__header">
            <p class="eyebrow"><?php esc_html_e('Actualidad', 'sitio-cero'); ?></p>
            <h1><?php echo esc_html(wp_strip_all_tags($archive_title)); ?></h1>
            <?php if (is_string($archive_description) && '' !== trim($archive_description)) : ?>
                <div class="archive-description"><?php echo wp_kses_post($archive_description); ?></div>
            <?php endif; ?>
        </header>

        <div class="posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card news-card'); ?>>
                    <a class="news-card__media" href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array('class' => 'news-card__image', 'loading' => 'lazy')); ?>
                        <?php else : ?>
                            <span class="news-card__image news-card__image--placeholder"><?php esc_html_e('Sin imagen', 'sitio-cero'); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="news-card__body">
                        <p class="news-card__meta"><?php echo esc_html(get_the_date('d M Y')); ?></p>
                        <h2 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                        <a class="news-card__link post-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e('Leer mas', 'sitio-cero'); ?></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
