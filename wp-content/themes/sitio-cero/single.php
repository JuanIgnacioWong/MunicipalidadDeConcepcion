<?php
get_header();
?>

<main id="content" class="site-main section container content-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $is_noticia = 'noticia' === get_post_type();
        $has_media = has_post_thumbnail();
        $gallery_items = array();

        if ($is_noticia && function_exists('sitio_cero_get_noticia_gallery_ids')) {
            $gallery_ids = sitio_cero_get_noticia_gallery_ids(get_the_ID());

            foreach ($gallery_ids as $gallery_id) {
                $image_large = wp_get_attachment_image_src($gallery_id, 'large');
                if (!is_array($image_large) || empty($image_large[0])) {
                    continue;
                }

                $image_full = wp_get_attachment_image_src($gallery_id, 'full');
                $alt_text = get_post_meta($gallery_id, '_wp_attachment_image_alt', true);

                if (!is_string($alt_text) || '' === trim($alt_text)) {
                    $alt_text = get_the_title($gallery_id);
                }

                if (!is_string($alt_text)) {
                    $alt_text = '';
                }

                $gallery_items[] = array(
                    'id'    => (int) $gallery_id,
                    'large' => $image_large[0],
                    'full'  => (is_array($image_full) && !empty($image_full[0])) ? $image_full[0] : $image_large[0],
                    'alt'   => $alt_text,
                );
            }
        }
        ?>
        <article <?php post_class(); ?>>
            <header class="section__header">
                <p class="eyebrow"><?php echo $is_noticia ? esc_html__('Noticia', 'sitio-cero') : esc_html__('Articulo', 'sitio-cero'); ?></p>
                <h1><?php the_title(); ?></h1>
                <p class="meta"><?php echo esc_html(get_the_date()); ?></p>
            </header>

            <?php if ($is_noticia) : ?>
                <div class="content-body single-noticia__content single-noticia__content--with-media">
                    <?php if ($has_media) : ?>
                        <figure class="single-noticia__media-inline">
                            <?php the_post_thumbnail('large', array('class' => 'single-noticia__image', 'loading' => 'eager')); ?>
                        </figure>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </div>

                <?php if (!empty($gallery_items)) : ?>
                    <?php $gallery_total = count($gallery_items); ?>
                    <section class="single-noticia-gallery" data-news-gallery>
                        <div class="single-noticia-gallery__header">
                            <h2><?php esc_html_e('Galeria de imagenes', 'sitio-cero'); ?></h2>
                        </div>

                        <div class="single-noticia-gallery__stage">
                            <?php if ($gallery_total > 1) : ?>
                                <button type="button" class="single-noticia-gallery__arrow single-noticia-gallery__arrow--prev" data-news-gallery-prev aria-label="<?php esc_attr_e('Imagen anterior', 'sitio-cero'); ?>">
                                    <span aria-hidden="true">&#10094;</span>
                                </button>
                            <?php endif; ?>

                            <div class="single-noticia-gallery__slides">
                                <?php foreach ($gallery_items as $index => $item) : ?>
                                    <figure
                                        class="single-noticia-gallery__slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
                                        data-news-gallery-slide
                                        data-index="<?php echo esc_attr((string) $index); ?>"
                                        aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
                                    >
                                        <button type="button" class="single-noticia-gallery__zoom" data-news-gallery-open aria-label="<?php esc_attr_e('Abrir imagen en grande', 'sitio-cero'); ?>">
                                            <img
                                                class="single-noticia-gallery__image"
                                                src="<?php echo esc_url($item['large']); ?>"
                                                data-full-src="<?php echo esc_url($item['full']); ?>"
                                                alt="<?php echo esc_attr($item['alt']); ?>"
                                                loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
                                            >
                                        </button>
                                    </figure>
                                <?php endforeach; ?>
                            </div>

                            <?php if ($gallery_total > 1) : ?>
                                <button type="button" class="single-noticia-gallery__arrow single-noticia-gallery__arrow--next" data-news-gallery-next aria-label="<?php esc_attr_e('Imagen siguiente', 'sitio-cero'); ?>">
                                    <span aria-hidden="true">&#10095;</span>
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if ($gallery_total > 1) : ?>
                            <div class="single-noticia-gallery__thumbs" role="tablist" aria-label="<?php esc_attr_e('Miniaturas de la galeria', 'sitio-cero'); ?>">
                                <?php foreach ($gallery_items as $index => $item) : ?>
                                    <button
                                        type="button"
                                        class="single-noticia-gallery__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
                                        data-news-gallery-thumb
                                        data-index="<?php echo esc_attr((string) $index); ?>"
                                        role="tab"
                                        aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                                        aria-label="<?php echo esc_attr(sprintf(__('Ver imagen %d', 'sitio-cero'), $index + 1)); ?>"
                                    >
                                        <img src="<?php echo esc_url($item['large']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="single-noticia-gallery__lightbox" data-news-gallery-lightbox hidden aria-hidden="true">
                            <button type="button" class="single-noticia-gallery__backdrop" data-news-gallery-close aria-label="<?php esc_attr_e('Cerrar galeria', 'sitio-cero'); ?>"></button>
                            <div class="single-noticia-gallery__lightbox-dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Galeria de imagenes', 'sitio-cero'); ?>">
                                <button type="button" class="single-noticia-gallery__lightbox-close" data-news-gallery-close aria-label="<?php esc_attr_e('Cerrar', 'sitio-cero'); ?>">&times;</button>
                                <?php if ($gallery_total > 1) : ?>
                                    <button type="button" class="single-noticia-gallery__lightbox-arrow single-noticia-gallery__lightbox-arrow--prev" data-news-gallery-prev aria-label="<?php esc_attr_e('Imagen anterior', 'sitio-cero'); ?>">
                                        <span aria-hidden="true">&#10094;</span>
                                    </button>
                                    <button type="button" class="single-noticia-gallery__lightbox-arrow single-noticia-gallery__lightbox-arrow--next" data-news-gallery-next aria-label="<?php esc_attr_e('Imagen siguiente', 'sitio-cero'); ?>">
                                        <span aria-hidden="true">&#10095;</span>
                                    </button>
                                <?php endif; ?>
                                <img class="single-noticia-gallery__lightbox-image" data-news-gallery-lightbox-image src="<?php echo esc_url($gallery_items[0]['full']); ?>" alt="<?php echo esc_attr($gallery_items[0]['alt']); ?>">
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            <?php else : ?>
                <div class="content-body">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
