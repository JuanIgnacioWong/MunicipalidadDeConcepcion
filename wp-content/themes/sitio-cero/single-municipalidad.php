<?php
get_header();
?>

<main id="content" class="site-main municipalidad-page">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $municipalidad_pages = get_posts(
            array(
                'post_type'      => 'municipalidad',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => array(
                    'menu_order' => 'ASC',
                    'title'      => 'ASC',
                ),
                'order'          => 'ASC',
                'no_found_rows'  => true,
            )
        );

        $current_slug = (string) get_post_field('post_name', get_the_ID());
        $is_alcalde_page = 'alcalde' === $current_slug;
        $is_concejo_page = 'concejo-municipal' === $current_slug;
        $bio_enabled_value = (string) get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_bio_enabled', true);
        $bio_enabled = '1' === $bio_enabled_value || ($is_alcalde_page && '' === $bio_enabled_value);
        $concejo_enabled_value = (string) get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_concejo_enabled', true);
        $concejo_enabled = '1' === $concejo_enabled_value || ($is_concejo_page && '' === $concejo_enabled_value);
        $concejo_members = get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_concejo_members', true);
        $bio_title = get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_bio_title', true);
        $bio_text = get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_bio_text', true);
        $signer_name = get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_signer_name', true);
        $signer_role = get_post_meta(get_the_ID(), 'sitio_cero_municipalidad_signer_role', true);

        if (!is_array($concejo_members) || empty($concejo_members)) {
            $concejo_members = function_exists('sitio_cero_get_default_concejo_members')
                ? sitio_cero_get_default_concejo_members()
                : array();
        }
        $concejo_members = array_slice(array_values($concejo_members), 0, 10);
        while (count($concejo_members) < 10) {
            $concejo_members[] = array(
                'name'      => '',
                'email'     => '',
                'image_url' => '',
            );
        }

        if (!is_string($bio_title)) {
            $bio_title = '';
        }
        if (!is_string($bio_text)) {
            $bio_text = '';
        }
        if (!is_string($signer_name)) {
            $signer_name = '';
        }
        if (!is_string($signer_role)) {
            $signer_role = '';
        }
        $bio_display_title = '' !== trim($bio_title) ? $bio_title : __('Biografia del alcalde', 'sitio-cero');

        $bio_content_html = '';
        if ('' !== trim($bio_text)) {
            $bio_content_html = wpautop(wp_kses_post($bio_text));
        } elseif ($is_alcalde_page) {
            $raw_content = get_post_field('post_content', get_the_ID());
            if (is_string($raw_content) && '' !== trim($raw_content)) {
                $bio_content_html = apply_filters('the_content', $raw_content);
            }
        }

        $show_alcalde_bio = $is_alcalde_page && $bio_enabled;
        $show_concejo_grid = $is_concejo_page && $concejo_enabled;
        ?>

        <section class="municipalidad-page__hero">
            <div class="municipalidad-page__hero-inner">
                <p class="municipalidad-page__eyebrow"><?php esc_html_e('Municipalidad', 'sitio-cero'); ?></p>
                <h1><?php the_title(); ?></h1>
            </div>
        </section>

        <section class="municipalidad-page__content-wrap">
            <div class="municipalidad-page__content-inner">
                <?php if (count($municipalidad_pages) > 1) : ?>
                    <nav class="municipalidad-page__nav" aria-label="<?php esc_attr_e('Secciones Municipalidad', 'sitio-cero'); ?>">
                        <ul class="municipalidad-page__nav-list">
                            <?php foreach ($municipalidad_pages as $municipalidad_page) : ?>
                                <?php
                                if (!$municipalidad_page instanceof WP_Post) {
                                    continue;
                                }

                                $is_current_page = (int) $municipalidad_page->ID === (int) get_the_ID();
                                $link_classes = 'municipalidad-page__nav-link';
                                if ($is_current_page) {
                                    $link_classes .= ' is-active';
                                }
                                ?>
                                <li>
                                    <a
                                        class="<?php echo esc_attr($link_classes); ?>"
                                        href="<?php echo esc_url(get_permalink($municipalidad_page->ID)); ?>"
                                        <?php echo $is_current_page ? 'aria-current="page"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    >
                                        <?php echo esc_html(get_the_title($municipalidad_page->ID)); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <article <?php post_class('municipalidad-page__article'); ?>>
                    <?php if ($show_alcalde_bio) : ?>
                        <section class="municipalidad-bio">
                            <figure class="municipalidad-bio__media">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('class' => 'municipalidad-bio__image', 'loading' => 'eager')); ?>
                                <?php else : ?>
                                    <span class="municipalidad-bio__placeholder"><?php esc_html_e('Agrega una imagen destacada vertical', 'sitio-cero'); ?></span>
                                <?php endif; ?>
                            </figure>

                            <div class="municipalidad-bio__body">
                                <h2 class="municipalidad-bio__title"><?php echo esc_html($bio_display_title); ?></h2>

                                <span class="news-section__bar municipalidad-bio__bar" aria-hidden="true">
                                    <span class="news-section__bar-segment news-section__bar-segment--one"></span>
                                    <span class="news-section__bar-segment news-section__bar-segment--two"></span>
                                    <span class="news-section__bar-segment news-section__bar-segment--three"></span>
                                    <span class="news-section__bar-segment news-section__bar-segment--four"></span>
                                    <span class="news-section__bar-segment news-section__bar-segment--five"></span>
                                </span>

                                <?php if ('' !== trim($signer_name) || '' !== trim($signer_role)) : ?>
                                    <div class="municipalidad-bio__signature">
                                        <?php if ('' !== trim($signer_name)) : ?>
                                            <p class="municipalidad-bio__signer"><?php echo esc_html($signer_name); ?></p>
                                        <?php endif; ?>
                                        <?php if ('' !== trim($signer_role)) : ?>
                                            <p class="municipalidad-bio__role"><?php echo esc_html($signer_role); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ('' !== trim($bio_content_html)) : ?>
                                    <div class="municipalidad-bio__text">
                                        <?php echo wp_kses_post($bio_content_html); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php elseif ($show_concejo_grid) : ?>
                        <section class="concejo-team">
                            <div class="concejo-team__grid">
                                <?php foreach ($concejo_members as $member) : ?>
                                    <?php
                                    if (!is_array($member)) {
                                        continue;
                                    }

                                    $member_name = isset($member['name']) ? trim((string) $member['name']) : '';
                                    $member_email = isset($member['email']) ? trim((string) $member['email']) : '';
                                    $member_image_url = isset($member['image_url']) ? trim((string) $member['image_url']) : '';
                                    $member_facebook = isset($member['facebook']) ? trim((string) $member['facebook']) : '';
                                    $member_instagram = isset($member['instagram']) ? trim((string) $member['instagram']) : '';
                                    $member_x = isset($member['x']) ? trim((string) $member['x']) : '';
                                    $member_whatsapp = isset($member['whatsapp']) ? trim((string) $member['whatsapp']) : '';
                                    if (function_exists('sitio_cero_normalize_whatsapp_url')) {
                                        $member_whatsapp = sitio_cero_normalize_whatsapp_url($member_whatsapp);
                                    }
                                    if ('' === $member_name) {
                                        $member_name = __('Nombre por definir', 'sitio-cero');
                                    }
                                    $socials = array(
                                        'facebook'  => $member_facebook,
                                        'instagram' => $member_instagram,
                                        'x'         => $member_x,
                                        'whatsapp'  => $member_whatsapp,
                                    );
                                    $social_labels = array(
                                        'facebook'  => __('Facebook', 'sitio-cero'),
                                        'instagram' => __('Instagram', 'sitio-cero'),
                                        'x'         => __('X', 'sitio-cero'),
                                        'whatsapp'  => __('WhatsApp', 'sitio-cero'),
                                    );
                                    $social_icons = array(
                                        'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H7v3h3v5h3v-5h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>',
                                        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm6.5-2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/></svg>',
                                        'x'         => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 3H21l-6.5 7.4L22 21h-6.2l-4.8-6-5.2 6H3.7l7-8L2 3h6.3l4.3 5.4L18.9 3z"/></svg>',
                                        'whatsapp'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.4 19.8L2 22l2.3-1.3A11 11 0 0 0 20.5 3.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-2.7 1.4.9-2.9-.2-.3A9 9 0 1 1 12 21zm5-6.1c-.3-.1-1.7-.8-2-1-.3-.1-.5-.1-.7.1l-.8 1c-.2.2-.3.2-.6.1-1.7-.7-2.8-2.2-3-2.5-.2-.3 0-.5.1-.6.1-.1.3-.3.4-.5.1-.2.1-.3.2-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.7-1-2.3-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.7.3-.2.2-1 1-1 2.4 0 1.4 1 2.8 1.1 3 .1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.5-.1 1.7-.7 2-1.4.2-.7.2-1.3.1-1.4-.1-.1-.3-.2-.6-.3z"/></svg>',
                                    );
                                    ?>
                                    <article class="concejo-team__card">
                                        <figure class="concejo-team__media">
                                            <?php if ('' !== $member_image_url) : ?>
                                                <img class="concejo-team__image" src="<?php echo esc_url($member_image_url); ?>" alt="<?php echo esc_attr($member_name); ?>" loading="lazy" decoding="async">
                                            <?php else : ?>
                                                <span class="concejo-team__image-placeholder"><?php esc_html_e('Sin imagen', 'sitio-cero'); ?></span>
                                            <?php endif; ?>
                                        </figure>
                                        <h3 class="concejo-team__name"><?php echo esc_html($member_name); ?></h3>
                                        <?php
                                        $has_socials = false;
                                        foreach ($socials as $social_url) {
                                            if ('' !== trim((string) $social_url)) {
                                                $has_socials = true;
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($has_socials) : ?>
                                            <div class="concejo-team__socials">
                                                <?php foreach ($socials as $social_key => $social_url) : ?>
                                                    <?php
                                                    $social_url = trim((string) $social_url);
                                                    if ('' === $social_url) {
                                                        continue;
                                                    }
                                                    $label = isset($social_labels[$social_key]) ? $social_labels[$social_key] : $social_key;
                                                    $icon = isset($social_icons[$social_key]) ? $social_icons[$social_key] : '';
                                                    ?>
                                                    <a class="concejo-team__social concejo-team__social--<?php echo esc_attr($social_key); ?>" href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($label); ?>">
                                                        <?php echo $icon; ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ('' !== $member_email) : ?>
                                            <a class="concejo-team__email" href="mailto:<?php echo antispambot(esc_attr($member_email)); ?>">
                                                <?php echo esc_html(antispambot($member_email)); ?>
                                            </a>
                                        <?php else : ?>
                                            <p class="concejo-team__email concejo-team__email--empty"><?php esc_html_e('Correo por definir', 'sitio-cero'); ?></p>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php else : ?>
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="municipalidad-page__media">
                                <?php the_post_thumbnail('large', array('class' => 'municipalidad-page__image', 'loading' => 'eager')); ?>
                            </figure>
                        <?php endif; ?>

                        <div class="municipalidad-page__content content-body">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </section>
    <?php endwhile; endif; ?>
</main>

<?php
get_footer();
