<?php

if (!defined('ABSPATH')) {
    exit;
}

function sitio_cero_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 80,
            'width'       => 320,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(
        array(
            'primary'   => __('Menu principal', 'sitio-cero'),
            'hero_info' => __('Menu Quiero informacion', 'sitio-cero'),
        )
    );
}
add_action('after_setup_theme', 'sitio_cero_setup');

function sitio_cero_assets()
{
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style('dashicons');
    wp_enqueue_style(
        'sitio-cero-material-symbols',
        sitio_cero_get_material_symbols_stylesheet_url(),
        array(),
        null
    );

    wp_enqueue_style(
        'sitio-cero-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        $version
    );

    $logo_height_desktop = sitio_cero_sanitize_logo_height(get_theme_mod('sitio_cero_brand_logo_height_desktop', 102));
    $logo_height_mobile = sitio_cero_sanitize_logo_height(get_theme_mod('sitio_cero_brand_logo_height_mobile', 72));
    $inline_css = ':root{--sitio-cero-brand-logo-max-height:' . $logo_height_desktop . 'px;--sitio-cero-brand-logo-max-height-mobile:' . $logo_height_mobile . 'px;}';
    wp_add_inline_style('sitio-cero-main', $inline_css);

    wp_enqueue_script(
        'sitio-cero-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'sitio_cero_assets');

function sitio_cero_get_material_symbols_stylesheet_url()
{
    return 'https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0';
}

function sitio_cero_admin_assets($hook_suffix)
{
    if ('nav-menus.php' !== $hook_suffix) {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'sitio-cero-material-symbols',
        sitio_cero_get_material_symbols_stylesheet_url(),
        array(),
        null
    );

    wp_enqueue_style(
        'sitio-cero-admin-menu-icons',
        get_template_directory_uri() . '/assets/css/admin-menu-icons.css',
        array('sitio-cero-material-symbols'),
        $version
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_admin_assets');

function sitio_cero_enqueue_noticia_admin_assets($hook_suffix)
{
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'noticia' !== $screen->post_type) {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_media();

    wp_enqueue_style(
        'sitio-cero-admin-noticia-gallery',
        get_template_directory_uri() . '/assets/css/admin-noticia-gallery.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'sitio-cero-admin-noticia-gallery',
        get_template_directory_uri() . '/assets/js/admin-noticia-gallery.js',
        array('jquery', 'jquery-ui-sortable'),
        $version,
        true
    );

    wp_localize_script(
        'sitio-cero-admin-noticia-gallery',
        'sitioCeroNoticiaGallery',
        array(
            'frameTitle'  => __('Selecciona imagenes para la galeria', 'sitio-cero'),
            'frameButton' => __('Usar imagenes', 'sitio-cero'),
            'emptyText'   => __('No hay imagenes seleccionadas. Haz clic en "Seleccionar imagenes".', 'sitio-cero'),
            'removeText'  => __('Quitar', 'sitio-cero'),
        )
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_enqueue_noticia_admin_assets');

function sitio_cero_sanitize_attachment_id($value)
{
    return absint($value);
}

function sitio_cero_sanitize_logo_height($value)
{
    $value = absint($value);
    if ($value < 36) {
        return 36;
    }
    if ($value > 280) {
        return 280;
    }

    return $value;
}

function sitio_cero_get_brand_logo_data()
{
    $fallback = array(
        'url'    => get_template_directory_uri() . '/assets/images/logo-concepcion-2025.png',
        'width'  => 1241,
        'height' => 739,
    );

    $logo_id = absint(get_theme_mod('sitio_cero_brand_logo_id', 0));
    if ($logo_id > 0) {
        $logo_src = wp_get_attachment_image_src($logo_id, 'full');
        if (is_array($logo_src) && !empty($logo_src[0])) {
            $width = !empty($logo_src[1]) ? (int) $logo_src[1] : $fallback['width'];
            $height = !empty($logo_src[2]) ? (int) $logo_src[2] : $fallback['height'];
            return array(
                'url'    => $logo_src[0],
                'width'  => $width,
                'height' => $height,
            );
        }
    }

    $custom_logo_id = absint(get_theme_mod('custom_logo', 0));
    if ($custom_logo_id > 0) {
        $logo_src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (is_array($logo_src) && !empty($logo_src[0])) {
            $width = !empty($logo_src[1]) ? (int) $logo_src[1] : $fallback['width'];
            $height = !empty($logo_src[2]) ? (int) $logo_src[2] : $fallback['height'];
            return array(
                'url'    => $logo_src[0],
                'width'  => $width,
                'height' => $height,
            );
        }
    }

    return $fallback;
}

function sitio_cero_customize_register($wp_customize)
{
    $wp_customize->add_section(
        'sitio_cero_header_brand',
        array(
            'title'       => __('Cabecera: Logo superior', 'sitio-cero'),
            'priority'    => 35,
            'description' => __('Configura el archivo y tamaño del logo ubicado entre el topbar y el menu principal.', 'sitio-cero'),
        )
    );

    $wp_customize->add_setting(
        'sitio_cero_brand_logo_id',
        array(
            'default'           => 0,
            'sanitize_callback' => 'sitio_cero_sanitize_attachment_id',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'sitio_cero_brand_logo_id',
            array(
                'label'       => __('Archivo de logo', 'sitio-cero'),
                'section'     => 'sitio_cero_header_brand',
                'mime_type'   => 'image',
                'description' => __('Si no seleccionas uno, se usara el logo de Sitio (Identidad del sitio) o el logo por defecto del tema.', 'sitio-cero'),
            )
        )
    );

    $wp_customize->add_setting(
        'sitio_cero_brand_logo_height_desktop',
        array(
            'default'           => 102,
            'sanitize_callback' => 'sitio_cero_sanitize_logo_height',
        )
    );

    $wp_customize->add_control(
        'sitio_cero_brand_logo_height_desktop',
        array(
            'label'       => __('Altura logo desktop (px)', 'sitio-cero'),
            'section'     => 'sitio_cero_header_brand',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 36,
                'max'  => 280,
                'step' => 1,
            ),
        )
    );

    $wp_customize->add_setting(
        'sitio_cero_brand_logo_height_mobile',
        array(
            'default'           => 72,
            'sanitize_callback' => 'sitio_cero_sanitize_logo_height',
        )
    );

    $wp_customize->add_control(
        'sitio_cero_brand_logo_height_mobile',
        array(
            'label'       => __('Altura logo movil (px)', 'sitio-cero'),
            'section'     => 'sitio_cero_header_brand',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 36,
                'max'  => 220,
                'step' => 1,
            ),
        )
    );
}
add_action('customize_register', 'sitio_cero_customize_register');

function sitio_cero_menu_fallback()
{
    $home = esc_url(home_url('/'));

    echo '<ul class="site-nav__list">';
    echo '<li><a href="' . $home . '">' . esc_html__('Inicio', 'sitio-cero') . '</a></li>';
    echo '<li><a href="' . $home . '#tramites">' . esc_html__('Tramites', 'sitio-cero') . '</a></li>';
    echo '<li><a href="' . $home . '#noticias">' . esc_html__('Noticias', 'sitio-cero') . '</a></li>';
    echo '<li><a href="' . $home . '#agenda">' . esc_html__('Agenda', 'sitio-cero') . '</a></li>';
    echo '</ul>';
}

function sitio_cero_register_noticia_post_type()
{
    $labels = array(
        'name'               => __('Noticias', 'sitio-cero'),
        'singular_name'      => __('Noticia', 'sitio-cero'),
        'menu_name'          => __('Noticias', 'sitio-cero'),
        'name_admin_bar'     => __('Noticia', 'sitio-cero'),
        'add_new'            => __('Agregar nueva', 'sitio-cero'),
        'add_new_item'       => __('Agregar noticia', 'sitio-cero'),
        'new_item'           => __('Nueva noticia', 'sitio-cero'),
        'edit_item'          => __('Editar noticia', 'sitio-cero'),
        'view_item'          => __('Ver noticia', 'sitio-cero'),
        'all_items'          => __('Todas las noticias', 'sitio-cero'),
        'search_items'       => __('Buscar noticias', 'sitio-cero'),
        'not_found'          => __('No se encontraron noticias.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay noticias en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'noticia',
        array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_admin_bar' => true,
            'show_in_rest'      => true,
            'has_archive'       => true,
            'rewrite'           => array('slug' => 'noticias'),
            'menu_position'     => 21,
            'menu_icon'         => 'dashicons-megaphone',
            'supports'          => array('title', 'editor', 'excerpt', 'thumbnail', 'author'),
        )
    );
}
add_action('init', 'sitio_cero_register_noticia_post_type');

function sitio_cero_register_noticia_taxonomy()
{
    $labels = array(
        'name'              => __('Categorias de noticias', 'sitio-cero'),
        'singular_name'     => __('Categoria de noticia', 'sitio-cero'),
        'search_items'      => __('Buscar categorias', 'sitio-cero'),
        'all_items'         => __('Todas las categorias', 'sitio-cero'),
        'edit_item'         => __('Editar categoria', 'sitio-cero'),
        'update_item'       => __('Actualizar categoria', 'sitio-cero'),
        'add_new_item'      => __('Agregar categoria', 'sitio-cero'),
        'new_item_name'     => __('Nombre de nueva categoria', 'sitio-cero'),
        'menu_name'         => __('Categorias de noticias', 'sitio-cero'),
    );

    register_taxonomy(
        'categoria_noticia',
        array('noticia'),
        array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'categoria-noticias'),
            'default_term'      => array(
                'name' => __('Noticias', 'sitio-cero'),
                'slug' => 'noticias',
            ),
        )
    );
}
add_action('init', 'sitio_cero_register_noticia_taxonomy', 11);

function sitio_cero_sanitize_attachment_ids_list($value)
{
    $ids = array();

    if (is_array($value)) {
        $raw_values = $value;
    } else {
        $raw_values = explode(',', (string) $value);
    }

    foreach ($raw_values as $raw_value) {
        $attachment_id = absint($raw_value);
        if ($attachment_id <= 0) {
            continue;
        }

        if (!in_array($attachment_id, $ids, true)) {
            $ids[] = $attachment_id;
        }
    }

    return $ids;
}

function sitio_cero_get_noticia_gallery_ids($post_id)
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return array();
    }

    $gallery_ids = get_post_meta($post_id, 'sitio_cero_noticia_gallery_ids', true);
    return sitio_cero_sanitize_attachment_ids_list($gallery_ids);
}

function sitio_cero_add_noticia_metaboxes()
{
    add_meta_box(
        'sitio_cero_noticia_gallery',
        __('Galeria de imagenes', 'sitio-cero'),
        'sitio_cero_render_noticia_gallery_metabox',
        'noticia',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_noticia_metaboxes');

function sitio_cero_render_noticia_gallery_metabox($post)
{
    wp_nonce_field('sitio_cero_save_noticia_gallery_meta', 'sitio_cero_noticia_gallery_nonce');

    $gallery_ids = sitio_cero_get_noticia_gallery_ids($post->ID);
    $empty_text = __('No hay imagenes seleccionadas. Haz clic en "Seleccionar imagenes".', 'sitio-cero');
    ?>
    <div class="sitio-cero-noticia-gallery" data-empty-text="<?php echo esc_attr($empty_text); ?>">
        <input
            type="hidden"
            id="sitio_cero_noticia_gallery_ids"
            name="sitio_cero_noticia_gallery_ids"
            value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>"
        >

        <div class="sitio-cero-noticia-gallery__actions">
            <button type="button" class="button button-primary sitio-cero-noticia-gallery__choose">
                <?php esc_html_e('Seleccionar imagenes', 'sitio-cero'); ?>
            </button>
            <button type="button" class="button button-secondary sitio-cero-noticia-gallery__clear">
                <?php esc_html_e('Limpiar galeria', 'sitio-cero'); ?>
            </button>
        </div>

        <p class="description">
            <?php esc_html_e('Puedes seleccionar multiples imagenes y arrastrarlas para cambiar el orden.', 'sitio-cero'); ?>
        </p>

        <ul class="sitio-cero-noticia-gallery__list">
            <?php foreach ($gallery_ids as $attachment_id) : ?>
                <?php
                $thumb_html = wp_get_attachment_image(
                    $attachment_id,
                    'thumbnail',
                    false,
                    array(
                        'class'   => 'sitio-cero-noticia-gallery__thumb-image',
                        'loading' => 'lazy',
                    )
                );
                if ('' === $thumb_html) {
                    continue;
                }
                ?>
                <li class="sitio-cero-noticia-gallery__item" data-id="<?php echo esc_attr((string) $attachment_id); ?>">
                    <div class="sitio-cero-noticia-gallery__thumb"><?php echo $thumb_html; ?></div>
                    <button type="button" class="button-link-delete sitio-cero-noticia-gallery__remove">
                        <?php esc_html_e('Quitar', 'sitio-cero'); ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="sitio-cero-noticia-gallery__empty<?php echo empty($gallery_ids) ? '' : ' is-hidden'; ?>">
            <?php echo esc_html($empty_text); ?>
        </p>
    </div>
    <?php
}

function sitio_cero_save_noticia_gallery_meta($post_id)
{
    if (!isset($_POST['sitio_cero_noticia_gallery_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_noticia_gallery_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_noticia_gallery_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $gallery_ids = array();
    if (isset($_POST['sitio_cero_noticia_gallery_ids'])) {
        $raw_gallery_ids = wp_unslash($_POST['sitio_cero_noticia_gallery_ids']);
        $gallery_ids = sitio_cero_sanitize_attachment_ids_list($raw_gallery_ids);
    }

    if (!empty($gallery_ids)) {
        update_post_meta($post_id, 'sitio_cero_noticia_gallery_ids', implode(',', $gallery_ids));
    } else {
        delete_post_meta($post_id, 'sitio_cero_noticia_gallery_ids');
    }
}
add_action('save_post_noticia', 'sitio_cero_save_noticia_gallery_meta');

function sitio_cero_get_or_create_demo_noticia_image_id()
{
    $cached_id = (int) get_option('sitio_cero_demo_noticia_image_id', 0);
    if ($cached_id > 0 && get_post($cached_id) instanceof WP_Post) {
        return $cached_id;
    }

    $source_file = get_template_directory() . '/assets/images/logo-concepcion-2025.png';
    if (!file_exists($source_file)) {
        return 0;
    }

    $upload = wp_upload_bits(
        'noticia-ejemplo-concepcion.png',
        null,
        file_get_contents($source_file)
    );

    if (!is_array($upload) || !empty($upload['error']) || empty($upload['file'])) {
        return 0;
    }

    $filetype = wp_check_filetype($upload['file']);
    $attachment_id = wp_insert_attachment(
        array(
            'post_mime_type' => isset($filetype['type']) ? $filetype['type'] : 'image/png',
            'post_title'     => __('Imagen de ejemplo para noticias', 'sitio-cero'),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ),
        $upload['file']
    );

    if (is_wp_error($attachment_id) || !$attachment_id) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (is_array($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    update_option('sitio_cero_demo_noticia_image_id', (int) $attachment_id);
    return (int) $attachment_id;
}

function sitio_cero_seed_demo_noticias()
{
    if (!post_type_exists('noticia') || !taxonomy_exists('categoria_noticia')) {
        return;
    }

    $already_seeded = get_option('sitio_cero_demo_noticias_seeded', '0');
    if ('1' === (string) $already_seeded) {
        return;
    }

    $existing_demo = get_posts(
        array(
            'post_type'      => 'noticia',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_sitio_cero_demo_noticia',
            'meta_value'     => '1',
            'no_found_rows'  => true,
        )
    );

    if (!empty($existing_demo)) {
        update_option('sitio_cero_demo_noticias_seeded', '1');
        return;
    }

    $term = get_term_by('slug', 'noticias', 'categoria_noticia');
    if (!$term instanceof WP_Term) {
        $term_result = wp_insert_term(
            __('Noticias', 'sitio-cero'),
            'categoria_noticia',
            array('slug' => 'noticias')
        );
        if (!is_wp_error($term_result) && is_array($term_result) && isset($term_result['term_id'])) {
            $term = get_term((int) $term_result['term_id'], 'categoria_noticia');
        }
    }

    $term_id = $term instanceof WP_Term ? (int) $term->term_id : 0;
    $featured_image_id = sitio_cero_get_or_create_demo_noticia_image_id();

    $items = array(
        array(
            'title'   => __('Concepcion refuerza plan preventivo de incendios urbanos', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: equipos municipales y Senapred activan patrullajes y puntos de abastecimiento.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. Se informa el despliegue coordinado de cuadrillas, monitoreo de zonas de riesgo y campañas de autocuidado en barrios de Concepcion, dentro de un plan de alcance nacional para temporada de altas temperaturas.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Nueva agenda de seguridad publica integra apoyo regional en Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: autoridades anuncian medidas de vigilancia y recuperacion de espacios.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El municipio presenta una agenda de seguridad con enfoque preventivo, trabajo comunitario y coordinacion con instituciones regionales para fortalecer la convivencia y el uso seguro del espacio publico.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Concepcion inicia programa nacional de empleo juvenil en servicios locales', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: se abren cupos de capacitacion y practica para jovenes de la comuna.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El plan considera formacion en competencias digitales, atencion ciudadana y apoyo a proyectos locales, con foco en insercion laboral y acompanamiento durante los primeros meses.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Obras de movilidad sustentable conectaran ejes estrategicos de Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: nuevo trazado prioriza transporte publico, ciclovias y accesibilidad universal.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El proyecto incorpora mejoras de veredas, cruces seguros, paraderos y senaletica, alineado con lineamientos nacionales de ciudades sostenibles y reduccion de tiempos de traslado.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Concepcion amplifica red cultural con actividades abiertas en toda la comuna', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: cartelera incluye musica, patrimonio, lectura y talleres familiares.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. La programacion busca descentralizar el acceso cultural y activar espacios barriales mediante alianzas con organizaciones comunitarias y actores del ecosistema creativo local.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Plan comunal de salud preventiva suma operativos territoriales en Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: se refuerzan controles, orientacion y vacunacion en distintos sectores.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El despliegue considera atencion primaria en terreno, educacion para el autocuidado y derivaciones oportunas, en coordinacion con la red publica de salud y equipos de apoyo comunitario.', 'sitio-cero'),
        ),
    );

    foreach ($items as $index => $item) {
        $timestamp = strtotime('-' . (int) $index . ' days 09:00:00');
        if (false === $timestamp) {
            $timestamp = time();
        }

        $post_id = wp_insert_post(
            array(
                'post_type'     => 'noticia',
                'post_status'   => 'publish',
                'post_title'    => $item['title'],
                'post_excerpt'  => $item['excerpt'],
                'post_content'  => $item['content'],
                'post_date'     => gmdate('Y-m-d H:i:s', $timestamp + (int) (get_option('gmt_offset', 0) * HOUR_IN_SECONDS)),
                'post_date_gmt' => gmdate('Y-m-d H:i:s', $timestamp),
            ),
            true
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, '_sitio_cero_demo_noticia', '1');

        if ($term_id > 0) {
            wp_set_object_terms($post_id, array($term_id), 'categoria_noticia', false);
        }

        if ($featured_image_id > 0) {
            set_post_thumbnail($post_id, $featured_image_id);
        }
    }

    update_option('sitio_cero_demo_noticias_seeded', '1');
}
add_action('init', 'sitio_cero_seed_demo_noticias', 40);

function sitio_cero_normalize_demo_noticias()
{
    if (!post_type_exists('noticia') || !taxonomy_exists('categoria_noticia')) {
        return;
    }

    $already_normalized = get_option('sitio_cero_demo_noticias_normalized', '0');
    if ('1' === (string) $already_normalized) {
        return;
    }

    $term = get_term_by('slug', 'noticias', 'categoria_noticia');
    $term_id = $term instanceof WP_Term ? (int) $term->term_id : 0;
    $featured_image_id = sitio_cero_get_or_create_demo_noticia_image_id();

    $items = array(
        array(
            'title'   => __('Concepcion refuerza plan preventivo de incendios urbanos', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: equipos municipales y Senapred activan patrullajes y puntos de abastecimiento.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. Se informa el despliegue coordinado de cuadrillas, monitoreo de zonas de riesgo y campañas de autocuidado en barrios de Concepcion, dentro de un plan de alcance nacional para temporada de altas temperaturas.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Nueva agenda de seguridad publica integra apoyo regional en Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: autoridades anuncian medidas de vigilancia y recuperacion de espacios.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El municipio presenta una agenda de seguridad con enfoque preventivo, trabajo comunitario y coordinacion con instituciones regionales para fortalecer la convivencia y el uso seguro del espacio publico.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Concepcion inicia programa nacional de empleo juvenil en servicios locales', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: se abren cupos de capacitacion y practica para jovenes de la comuna.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El plan considera formacion en competencias digitales, atencion ciudadana y apoyo a proyectos locales, con foco en insercion laboral y acompanamiento durante los primeros meses.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Obras de movilidad sustentable conectaran ejes estrategicos de Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: nuevo trazado prioriza transporte publico, ciclovias y accesibilidad universal.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El proyecto incorpora mejoras de veredas, cruces seguros, paraderos y senaletica, alineado con lineamientos nacionales de ciudades sostenibles y reduccion de tiempos de traslado.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Concepcion amplifica red cultural con actividades abiertas en toda la comuna', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: cartelera incluye musica, patrimonio, lectura y talleres familiares.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. La programacion busca descentralizar el acceso cultural y activar espacios barriales mediante alianzas con organizaciones comunitarias y actores del ecosistema creativo local.', 'sitio-cero'),
        ),
        array(
            'title'   => __('Plan comunal de salud preventiva suma operativos territoriales en Concepcion', 'sitio-cero'),
            'excerpt' => __('Noticia de ejemplo: se refuerzan controles, orientacion y vacunacion en distintos sectores.', 'sitio-cero'),
            'content' => __('Contenido de referencia para maquetacion. El despliegue considera atencion primaria en terreno, educacion para el autocuidado y derivaciones oportunas, en coordinacion con la red publica de salud y equipos de apoyo comunitario.', 'sitio-cero'),
        ),
    );

    $existing_demo_posts = get_posts(
        array(
            'post_type'      => 'noticia',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array(
                'date' => 'DESC',
                'ID'   => 'DESC',
            ),
            'meta_key'       => '_sitio_cero_demo_noticia',
            'meta_value'     => '1',
            'no_found_rows'  => true,
        )
    );

    $keep_ids = array();

    foreach ($items as $index => $item) {
        $post_id = 0;

        foreach ($existing_demo_posts as $candidate_post) {
            if (in_array((int) $candidate_post->ID, $keep_ids, true)) {
                continue;
            }

            if ((string) $candidate_post->post_title === (string) $item['title']) {
                $post_id = (int) $candidate_post->ID;
                break;
            }
        }

        if ($post_id <= 0) {
            $timestamp = strtotime('-' . (int) $index . ' days 09:00:00');
            if (false === $timestamp) {
                $timestamp = time();
            }

            $post_id = wp_insert_post(
                array(
                    'post_type'     => 'noticia',
                    'post_status'   => 'publish',
                    'post_title'    => $item['title'],
                    'post_excerpt'  => $item['excerpt'],
                    'post_content'  => $item['content'],
                    'post_date'     => gmdate('Y-m-d H:i:s', $timestamp + (int) (get_option('gmt_offset', 0) * HOUR_IN_SECONDS)),
                    'post_date_gmt' => gmdate('Y-m-d H:i:s', $timestamp),
                ),
                true
            );

            if (is_wp_error($post_id) || !$post_id) {
                continue;
            }
        } else {
            wp_update_post(
                array(
                    'ID'           => $post_id,
                    'post_status'  => 'publish',
                    'post_excerpt' => $item['excerpt'],
                    'post_content' => $item['content'],
                )
            );
        }

        $keep_ids[] = (int) $post_id;
        update_post_meta($post_id, '_sitio_cero_demo_noticia', '1');

        if ($term_id > 0) {
            wp_set_object_terms($post_id, array($term_id), 'categoria_noticia', false);
        }

        if ($featured_image_id > 0) {
            set_post_thumbnail($post_id, $featured_image_id);
        }
    }

    foreach ($existing_demo_posts as $candidate_post) {
        $candidate_id = (int) $candidate_post->ID;
        if (!in_array($candidate_id, $keep_ids, true)) {
            wp_trash_post($candidate_id);
        }
    }

    update_option('sitio_cero_demo_noticias_normalized', '1');
}
add_action('init', 'sitio_cero_normalize_demo_noticias', 41);

function sitio_cero_register_tramite_post_type()
{
    $labels = array(
        'name'               => __('Tramites y Servicios', 'sitio-cero'),
        'singular_name'      => __('Tramite o Servicio', 'sitio-cero'),
        'menu_name'          => __('Tramites', 'sitio-cero'),
        'name_admin_bar'     => __('Tramite', 'sitio-cero'),
        'add_new'            => __('Agregar nuevo', 'sitio-cero'),
        'add_new_item'       => __('Agregar tramite o servicio', 'sitio-cero'),
        'new_item'           => __('Nuevo tramite o servicio', 'sitio-cero'),
        'edit_item'          => __('Editar tramite o servicio', 'sitio-cero'),
        'view_item'          => __('Ver tramite o servicio', 'sitio-cero'),
        'all_items'          => __('Todos los tramites', 'sitio-cero'),
        'search_items'       => __('Buscar tramites', 'sitio-cero'),
        'not_found'          => __('No se encontraron tramites.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay tramites en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'tramite_servicio',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => false,
            'show_in_admin_bar'  => true,
            'show_in_rest'       => true,
            'publicly_queryable' => false,
            'exclude_from_search'=> true,
            'has_archive'        => false,
            'rewrite'            => false,
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-welcome-write-blog',
            'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        )
    );
}
add_action('init', 'sitio_cero_register_tramite_post_type');

function sitio_cero_add_tramite_metaboxes()
{
    add_meta_box(
        'sitio_cero_tramite_options',
        __('Opciones del tramite', 'sitio-cero'),
        'sitio_cero_render_tramite_metabox',
        'tramite_servicio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_tramite_metaboxes');

function sitio_cero_enqueue_tramite_admin_assets($hook_suffix)
{
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'tramite_servicio' !== $screen->post_type) {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    wp_add_inline_script(
        'wp-color-picker',
        "jQuery(function($){ $('.sitio-cero-color-picker').wpColorPicker(); });"
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_enqueue_tramite_admin_assets');

function sitio_cero_render_tramite_metabox($post)
{
    wp_nonce_field('sitio_cero_save_tramite_meta', 'sitio_cero_tramite_meta_nonce');

    $url = get_post_meta($post->ID, 'sitio_cero_tramite_url', true);
    $bg_color = get_post_meta($post->ID, 'sitio_cero_tramite_bg_color', true);
    $custom_html = get_post_meta($post->ID, 'sitio_cero_tramite_custom_html', true);
    $custom_css = get_post_meta($post->ID, 'sitio_cero_tramite_custom_css', true);

    if (!is_string($url)) {
        $url = '';
    }

    if (!is_string($bg_color)) {
        $bg_color = '';
    }

    $bg_color = sanitize_hex_color($bg_color);
    if (!is_string($bg_color)) {
        $bg_color = '';
    }

    if (!is_string($custom_html)) {
        $custom_html = '';
    }

    if (!is_string($custom_css)) {
        $custom_css = '';
    }
    ?>
    <p>
        <label for="sitio_cero_tramite_url"><strong><?php esc_html_e('URL del servicio', 'sitio-cero'); ?></strong></label><br>
        <input
            id="sitio_cero_tramite_url"
            name="sitio_cero_tramite_url"
            type="url"
            class="widefat"
            value="<?php echo esc_attr($url); ?>"
            placeholder="https://..."
        >
    </p>
    <p>
        <label for="sitio_cero_tramite_bg_color"><strong><?php esc_html_e('Color de caja (pastel)', 'sitio-cero'); ?></strong></label><br>
        <input
            id="sitio_cero_tramite_bg_color"
            name="sitio_cero_tramite_bg_color"
            type="text"
            class="sitio-cero-color-picker"
            value="<?php echo esc_attr($bg_color); ?>"
            data-default-color="#82b1ff"
        >
        <small><?php esc_html_e('Selecciona un color para el fondo de esta tarjeta.', 'sitio-cero'); ?></small>
        <br>
        <small><?php esc_html_e('Sugeridos Material (llamativos):', 'sitio-cero'); ?> <code>#82b1ff</code> <code>#b9f6ca</code> <code>#ffd180</code> <code>#b388ff</code></small>
    </p>
    <p>
        <label for="sitio_cero_tramite_custom_html"><strong><?php esc_html_e('HTML personalizado del servicio (opcional)', 'sitio-cero'); ?></strong></label><br>
        <textarea
            id="sitio_cero_tramite_custom_html"
            name="sitio_cero_tramite_custom_html"
            class="widefat"
            rows="7"
            placeholder="<?php esc_attr_e('Ejemplo: <h3>Permisos</h3><p>Texto</p><ul><li>Item</li></ul>', 'sitio-cero'); ?>"
        ><?php echo esc_textarea($custom_html); ?></textarea>
        <small><?php esc_html_e('Si completas este campo, se mostrara en lugar del contenido principal del tramite.', 'sitio-cero'); ?></small>
    </p>
    <p>
        <label for="sitio_cero_tramite_custom_css"><strong><?php esc_html_e('CSS propio de la tarjeta', 'sitio-cero'); ?></strong></label><br>
        <textarea
            id="sitio_cero_tramite_custom_css"
            name="sitio_cero_tramite_custom_css"
            class="widefat"
            rows="6"
            placeholder="<?php esc_attr_e('Escribe declaraciones CSS o una regla completa. Usa {{selector}} para apuntar esta tarjeta.', 'sitio-cero'); ?>"
        ><?php echo esc_textarea($custom_css); ?></textarea>
        <small><?php esc_html_e('Ejemplo declaraciones: background:#f6f0ff; border-color:#cdb5f7;', 'sitio-cero'); ?></small><br>
        <small><?php esc_html_e('Ejemplo regla completa: {{selector}} .service-card__body h3 { color:#234; }', 'sitio-cero'); ?></small>
    </p>
    <p>
        <button type="submit" class="button button-secondary" name="sitio_cero_tramite_restore_example" value="1">
            <?php esc_html_e('Restaurar ejemplo en este tramite', 'sitio-cero'); ?>
        </button>
        <br>
        <small><?php esc_html_e('Al guardar con este boton se reemplazan color, HTML y CSS por un ejemplo de referencia.', 'sitio-cero'); ?></small>
    </p>
    <?php
}

function sitio_cero_sanitize_tramite_custom_css($css)
{
    $css = wp_kses((string) $css, array());
    $css = preg_replace('/<\/?style[^>]*>/i', '', $css);

    return trim((string) $css);
}

function sitio_cero_save_tramite_meta($post_id)
{
    if (!isset($_POST['sitio_cero_tramite_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_tramite_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_tramite_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $url = '';
    if (isset($_POST['sitio_cero_tramite_url'])) {
        $url = esc_url_raw(wp_unslash($_POST['sitio_cero_tramite_url']));
    }

    if ('' !== $url) {
        update_post_meta($post_id, 'sitio_cero_tramite_url', $url);
    } else {
        delete_post_meta($post_id, 'sitio_cero_tramite_url');
    }

    $bg_color = '';
    if (isset($_POST['sitio_cero_tramite_bg_color'])) {
        $bg_color = sanitize_hex_color(wp_unslash($_POST['sitio_cero_tramite_bg_color']));
    }

    if (is_string($bg_color) && '' !== $bg_color) {
        update_post_meta($post_id, 'sitio_cero_tramite_bg_color', $bg_color);
    } else {
        delete_post_meta($post_id, 'sitio_cero_tramite_bg_color');
    }

    $custom_html = '';
    if (isset($_POST['sitio_cero_tramite_custom_html'])) {
        $custom_html = wp_unslash($_POST['sitio_cero_tramite_custom_html']);
        if (current_user_can('unfiltered_html')) {
            $custom_html = preg_replace('/<\/?script[^>]*>/i', '', (string) $custom_html);
            $custom_html = preg_replace('/\s+on[a-z]+\s*=\s*([\'"]).*?\1/i', '', (string) $custom_html);
        } else {
            $custom_html = wp_kses_post($custom_html);
        }
        $custom_html = trim((string) $custom_html);
    }

    if ('' !== $custom_html) {
        update_post_meta($post_id, 'sitio_cero_tramite_custom_html', $custom_html);
    } else {
        delete_post_meta($post_id, 'sitio_cero_tramite_custom_html');
    }

    $custom_css = '';
    if (isset($_POST['sitio_cero_tramite_custom_css'])) {
        $custom_css = wp_unslash($_POST['sitio_cero_tramite_custom_css']);
        if (!current_user_can('unfiltered_html')) {
            $custom_css = sanitize_textarea_field($custom_css);
        }
        $custom_css = sitio_cero_sanitize_tramite_custom_css($custom_css);
    }

    if ('' !== $custom_css) {
        update_post_meta($post_id, 'sitio_cero_tramite_custom_css', $custom_css);
    } else {
        delete_post_meta($post_id, 'sitio_cero_tramite_custom_css');
    }

    if (isset($_POST['sitio_cero_tramite_restore_example'])) {
        $example = sitio_cero_get_tramite_reference_example_for_post($post_id);
        if (is_array($example) && !empty($example)) {
            if (isset($example['bg_color']) && is_string($example['bg_color'])) {
                update_post_meta($post_id, 'sitio_cero_tramite_bg_color', $example['bg_color']);
            }
            if (isset($example['custom_html']) && is_string($example['custom_html'])) {
                update_post_meta($post_id, 'sitio_cero_tramite_custom_html', $example['custom_html']);
            }
            if (isset($example['custom_css']) && is_string($example['custom_css'])) {
                update_post_meta($post_id, 'sitio_cero_tramite_custom_css', $example['custom_css']);
            }
        }
    }
}
add_action('save_post_tramite_servicio', 'sitio_cero_save_tramite_meta');

function sitio_cero_seed_default_tramites()
{
    if (!post_type_exists('tramite_servicio')) {
        return;
    }

    $already_seeded = get_option('sitio_cero_default_tramites_seeded', '0');
    if ('1' === (string) $already_seeded) {
        return;
    }

    $existing_items = get_posts(
        array(
            'post_type'      => 'tramite_servicio',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if (!empty($existing_items)) {
        update_option('sitio_cero_default_tramites_seeded', '1');
        return;
    }

    $defaults = sitio_cero_get_tramite_reference_examples();

    foreach ($defaults as $index => $item) {
        $post_id = wp_insert_post(
            array(
                'post_type'    => 'tramite_servicio',
                'post_status'  => 'publish',
                'post_title'   => $item['title'],
                'post_content' => $item['content'],
                'menu_order'   => $index + 1,
            ),
            true
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, 'sitio_cero_tramite_url', $item['url']);
        update_post_meta($post_id, 'sitio_cero_tramite_bg_color', $item['bg_color']);
        update_post_meta($post_id, 'sitio_cero_tramite_custom_html', $item['custom_html']);
        update_post_meta($post_id, 'sitio_cero_tramite_custom_css', $item['custom_css']);
    }

    update_option('sitio_cero_default_tramites_seeded', '1');
}
add_action('init', 'sitio_cero_seed_default_tramites', 31);

function sitio_cero_get_tramite_reference_examples()
{
    return array(
        array(
            'title'   => __('Permiso de circulacion', 'sitio-cero'),
            'content' => __('Consulta deuda, carga tus datos y paga en linea desde el portal comunal.', 'sitio-cero'),
            'url'     => '#',
            'bg_color' => '#82b1ff',
            'custom_html' => '<h3>Permiso de circulacion 2026</h3><p>Paga en linea y descarga tu comprobante al instante.</p><ul><li>Consulta deuda por patente</li><li>Paga con tarjeta o transferencia</li><li>Descarga PDF de respaldo</li></ul><p><strong>Referencia:</strong> este bloque se edita desde HTML personalizado.</p>',
            'custom_css' => '{{selector}} .service-card__body h3{font-size:1.2rem;} {{selector}} .service-card__body ul{margin:0.55rem 0 0;padding-left:1.1rem;}',
        ),
        array(
            'title'   => __('Patente comercial', 'sitio-cero'),
            'content' => __('Solicita, renueva o revisa el estado de tu patente comercial vigente.', 'sitio-cero'),
            'url'     => '#',
            'bg_color' => '#b9f6ca',
            'custom_html' => '<h3>Patente comercial</h3><p>Gestiona apertura, renovacion y seguimiento del tramite.</p><p><a href="#">Ver requisitos base</a></p><p><small>Referencia: enlace, textos y estructura HTML personalizable.</small></p>',
            'custom_css' => '{{selector}} .service-card__body a{display:inline-block;margin-top:0.15rem;text-decoration:none;padding:0.35rem 0.6rem;border-radius:999px;background:rgba(15,35,67,0.08);} {{selector}} .service-card__body small{opacity:.9;}',
        ),
        array(
            'title'   => __('Multas de transito', 'sitio-cero'),
            'content' => __('Revisa infracciones asociadas y paga en linea con actualizacion inmediata.', 'sitio-cero'),
            'url'     => '#',
            'bg_color' => '#ffd180',
            'custom_html' => '<h3>Multas de transito</h3><p>Consulta por RUT o patente y regulariza en linea.</p><div class="tramite-mini-tag">Atencion 24/7</div><p>Referencia: puedes usar clases propias dentro del HTML.</p>',
            'custom_css' => '{{selector}} .tramite-mini-tag{display:inline-block;margin:0.4rem 0 0.55rem;padding:0.28rem 0.55rem;border-radius:8px;background:#ffe0c2;color:#74431a;font-weight:700;font-size:.8rem;}',
        ),
        array(
            'title'   => __('Subsidios y beneficios', 'sitio-cero'),
            'content' => __('Conoce programas abiertos y descarga requisitos para cada convocatoria.', 'sitio-cero'),
            'url'     => '#',
            'bg_color' => '#b388ff',
            'custom_html' => '<h3>Subsidios y beneficios</h3><p>Postula a programas municipales con formulario guiado.</p><ol><li>Revisa fechas</li><li>Adjunta antecedentes</li><li>Recibe estado por correo</li></ol>',
            'custom_css' => '{{selector}} .service-card__body ol{margin:0.55rem 0 0;padding-left:1.15rem;} {{selector}} .service-card__body li{margin-bottom:0.2rem;}',
        ),
    );
}

function sitio_cero_get_tramite_reference_example_for_post($post_id)
{
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return array();
    }

    $examples = sitio_cero_get_tramite_reference_examples();
    if (empty($examples)) {
        return array();
    }

    $ids = get_posts(
        array(
            'post_type'      => 'tramite_servicio',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'ASC',
            ),
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    $position = array_search($post_id, $ids, true);
    if (false === $position) {
        $position = 0;
    }

    $index = (int) $position % count($examples);
    return $examples[$index];
}

function sitio_cero_seed_tramite_reference_content()
{
    if (!post_type_exists('tramite_servicio')) {
        return;
    }

    $already_seeded = get_option('sitio_cero_tramite_reference_seeded', '0');
    if ('1' === (string) $already_seeded) {
        return;
    }

    $tramites = get_posts(
        array(
            'post_type'      => 'tramite_servicio',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'ASC',
            ),
            'no_found_rows'  => true,
        )
    );

    if (empty($tramites)) {
        update_option('sitio_cero_tramite_reference_seeded', '1');
        return;
    }

    $examples = sitio_cero_get_tramite_reference_examples();
    $total_examples = count($examples);

    foreach ($tramites as $index => $tramite) {
        $example = $examples[$index % $total_examples];
        $post_id = $tramite->ID;

        $current_custom_html = get_post_meta($post_id, 'sitio_cero_tramite_custom_html', true);
        $current_custom_css = get_post_meta($post_id, 'sitio_cero_tramite_custom_css', true);
        $current_bg_color = get_post_meta($post_id, 'sitio_cero_tramite_bg_color', true);

        if (!is_string($current_custom_html) || '' === trim($current_custom_html)) {
            update_post_meta($post_id, 'sitio_cero_tramite_custom_html', $example['custom_html']);
        }

        if (!is_string($current_custom_css) || '' === trim($current_custom_css)) {
            update_post_meta($post_id, 'sitio_cero_tramite_custom_css', $example['custom_css']);
        }

        if (!is_string($current_bg_color) || '' === trim($current_bg_color)) {
            update_post_meta($post_id, 'sitio_cero_tramite_bg_color', $example['bg_color']);
        }
    }

    update_option('sitio_cero_tramite_reference_seeded', '1');
}
add_action('init', 'sitio_cero_seed_tramite_reference_content', 32);

function sitio_cero_replace_tramite_colors_with_material_palette()
{
    if (!post_type_exists('tramite_servicio')) {
        return;
    }

    $already_applied = get_option('sitio_cero_tramite_material_palette_applied', '0');
    if ('1' === (string) $already_applied) {
        return;
    }

    $tramites = get_posts(
        array(
            'post_type'      => 'tramite_servicio',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'ASC',
            ),
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if (empty($tramites)) {
        update_option('sitio_cero_tramite_material_palette_applied', '1');
        return;
    }

    $examples = sitio_cero_get_tramite_reference_examples();
    $total_examples = count($examples);
    if (0 === $total_examples) {
        update_option('sitio_cero_tramite_material_palette_applied', '1');
        return;
    }

    foreach ($tramites as $index => $post_id) {
        $example = $examples[$index % $total_examples];
        if (isset($example['bg_color']) && is_string($example['bg_color']) && '' !== $example['bg_color']) {
            update_post_meta((int) $post_id, 'sitio_cero_tramite_bg_color', $example['bg_color']);
        }
    }

    update_option('sitio_cero_tramite_material_palette_applied', '1');
}
add_action('init', 'sitio_cero_replace_tramite_colors_with_material_palette', 33);

function sitio_cero_get_hero_info_default_items()
{
    return array(
        array(
            'label' => __('Alcalde de Santiago', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Seguridad', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Juzgados', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Emergencias', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Vacunas', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Concejo Municipal', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Plan Regulador', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Bus Vecinal', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Mascotas', 'sitio-cero'),
            'url'   => '#',
        ),
        array(
            'label' => __('Aseo', 'sitio-cero'),
            'url'   => '#',
        ),
    );
}

function sitio_cero_get_menu_item_icon_markup($menu_item_id)
{
    $icon_value = trim((string) get_post_meta($menu_item_id, '_sitio_cero_menu_icon', true));
    if ('' === $icon_value) {
        return '';
    }

    if (0 === strpos($icon_value, 'google:')) {
        $icon_name = sitio_cero_sanitize_google_menu_icon_name(substr($icon_value, 7));
        if ('' !== $icon_name) {
            return '<span class="acciones-bt__icon acciones-bt__icon--google material-symbols-rounded" aria-hidden="true">' . esc_html($icon_name) . '</span>';
        }
    }

    if (wp_http_validate_url($icon_value)) {
        return '<span class="acciones-bt__icon acciones-bt__icon--image" aria-hidden="true"><img class="acciones-bt__icon-image" src="' . esc_url($icon_value) . '" alt=""></span>';
    }

    $icon_classes = preg_replace('/[^A-Za-z0-9_\\-\\s]/', '', $icon_value);
    $icon_classes = trim((string) $icon_classes);
    if ('' === $icon_classes) {
        return '';
    }

    return '<span class="acciones-bt__icon ' . esc_attr($icon_classes) . '" aria-hidden="true"></span>';
}

function sitio_cero_get_google_menu_icon_options()
{
    return array(
        'info'                    => __('Informacion', 'sitio-cero'),
        'help'                    => __('Ayuda', 'sitio-cero'),
        'contact_support'         => __('Soporte', 'sitio-cero'),
        'campaign'                => __('Noticias', 'sitio-cero'),
        'announcement'            => __('Comunicados', 'sitio-cero'),
        'newspaper'               => __('Prensa', 'sitio-cero'),
        'article'                 => __('Articulos', 'sitio-cero'),
        'feed'                    => __('Novedades', 'sitio-cero'),
        'notifications'           => __('Alertas', 'sitio-cero'),
        'warning'                 => __('Advertencia', 'sitio-cero'),
        'error'                   => __('Error', 'sitio-cero'),
        'report'                  => __('Reportes', 'sitio-cero'),
        'emergency_home'          => __('Emergencias hogar', 'sitio-cero'),
        'local_fire_department'   => __('Bomberos', 'sitio-cero'),
        'health_and_safety'       => __('Salud y seguridad', 'sitio-cero'),
        'sos'                     => __('SOS', 'sitio-cero'),
        'medical_information'     => __('Informacion medica', 'sitio-cero'),
        'local_hospital'          => __('Hospital', 'sitio-cero'),
        'vaccines'                => __('Vacunas', 'sitio-cero'),
        'medication'              => __('Medicacion', 'sitio-cero'),
        'monitor_heart'           => __('Monitoreo de salud', 'sitio-cero'),
        'contact_phone'           => __('Contacto telefonico', 'sitio-cero'),
        'phone_in_talk'           => __('Linea directa', 'sitio-cero'),
        'support_agent'           => __('Atencion ciudadana', 'sitio-cero'),
        'chat'                    => __('Chat', 'sitio-cero'),
        'forum'                   => __('Foro', 'sitio-cero'),
        'mail'                    => __('Correo', 'sitio-cero'),
        'mail_outline'            => __('Correo simple', 'sitio-cero'),
        'alternate_email'         => __('Email alternativo', 'sitio-cero'),
        'account_circle'          => __('Cuenta', 'sitio-cero'),
        'groups'                  => __('Vecinos', 'sitio-cero'),
        'group'                   => __('Grupo', 'sitio-cero'),
        'person'                  => __('Persona', 'sitio-cero'),
        'badge'                   => __('Credencial', 'sitio-cero'),
        'public'                  => __('Comunidad', 'sitio-cero'),
        'travel_explore'          => __('Turismo', 'sitio-cero'),
        'place'                   => __('Lugar', 'sitio-cero'),
        'location_on'             => __('Ubicacion', 'sitio-cero'),
        'map'                     => __('Mapa', 'sitio-cero'),
        'explore'                 => __('Explorar', 'sitio-cero'),
        'directions'              => __('Rutas', 'sitio-cero'),
        'directions_bus'          => __('Bus', 'sitio-cero'),
        'directions_car'          => __('Auto', 'sitio-cero'),
        'directions_walk'         => __('Peaton', 'sitio-cero'),
        'train'                   => __('Tren', 'sitio-cero'),
        'two_wheeler'             => __('Motocicleta', 'sitio-cero'),
        'traffic'                 => __('Transito', 'sitio-cero'),
        'local_taxi'              => __('Taxi', 'sitio-cero'),
        'payments'                => __('Pagos', 'sitio-cero'),
        'receipt_long'            => __('Boletas', 'sitio-cero'),
        'credit_card'             => __('Tarjeta', 'sitio-cero'),
        'account_balance_wallet'  => __('Billetera', 'sitio-cero'),
        'account_balance'         => __('Municipio', 'sitio-cero'),
        'request_quote'           => __('Cotizaciones', 'sitio-cero'),
        'paid'                    => __('Pagado', 'sitio-cero'),
        'gavel'                   => __('Juzgados', 'sitio-cero'),
        'policy'                  => __('Politicas', 'sitio-cero'),
        'verified_user'           => __('Usuario verificado', 'sitio-cero'),
        'fact_check'              => __('Fiscalizacion', 'sitio-cero'),
        'assignment'              => __('Tramites', 'sitio-cero'),
        'description'             => __('Documentos', 'sitio-cero'),
        'folder'                  => __('Carpetas', 'sitio-cero'),
        'folder_shared'           => __('Expedientes', 'sitio-cero'),
        'home'                    => __('Hogar', 'sitio-cero'),
        'apartment'               => __('Urbanismo', 'sitio-cero'),
        'home_work'               => __('Vivienda', 'sitio-cero'),
        'storefront'              => __('Comercio', 'sitio-cero'),
        'business'                => __('Empresas', 'sitio-cero'),
        'construction'            => __('Obras', 'sitio-cero'),
        'engineering'             => __('Ingenieria', 'sitio-cero'),
        'build'                   => __('Mantencion', 'sitio-cero'),
        'handyman'                => __('Reparaciones', 'sitio-cero'),
        'plumbing'                => __('Agua y alcantarillado', 'sitio-cero'),
        'electrical_services'     => __('Electricidad', 'sitio-cero'),
        'cleaning_services'       => __('Aseo', 'sitio-cero'),
        'delete_sweep'            => __('Limpieza urbana', 'sitio-cero'),
        'recycling'               => __('Reciclaje', 'sitio-cero'),
        'park'                    => __('Parques', 'sitio-cero'),
        'forest'                  => __('Areas verdes', 'sitio-cero'),
        'pets'                    => __('Mascotas', 'sitio-cero'),
        'eco'                     => __('Medio ambiente', 'sitio-cero'),
        'water_drop'              => __('Agua', 'sitio-cero'),
        'wb_sunny'                => __('Clima', 'sitio-cero'),
        'thunderstorm'            => __('Alerta climatica', 'sitio-cero'),
        'school'                  => __('Educacion', 'sitio-cero'),
        'menu_book'               => __('Biblioteca', 'sitio-cero'),
        'library_books'           => __('Libros', 'sitio-cero'),
        'event'                   => __('Eventos', 'sitio-cero'),
        'event_available'         => __('Agenda', 'sitio-cero'),
        'calendar_month'          => __('Calendario', 'sitio-cero'),
        'celebration'             => __('Celebraciones', 'sitio-cero'),
        'sports_soccer'           => __('Deportes', 'sitio-cero'),
        'sports_basketball'       => __('Canchas', 'sitio-cero'),
        'museum'                  => __('Museo', 'sitio-cero'),
        'theaters'                => __('Cultura', 'sitio-cero'),
        'music_note'              => __('Musica', 'sitio-cero'),
        'camera_alt'              => __('Galeria', 'sitio-cero'),
        'photo_library'           => __('Fotos', 'sitio-cero'),
        'image'                   => __('Imagen', 'sitio-cero'),
        'volunteer_activism'      => __('Voluntariado', 'sitio-cero'),
        'diversity_3'             => __('Inclusion', 'sitio-cero'),
        'local_police'            => __('Seguridad publica', 'sitio-cero'),
        'security'                => __('Seguridad', 'sitio-cero'),
        'shield'                  => __('Proteccion', 'sitio-cero'),
        'admin_panel_settings'    => __('Administracion', 'sitio-cero'),
        'bolt'                    => __('Urgente', 'sitio-cero'),
        'wifi'                    => __('Conectividad', 'sitio-cero'),
        'language'                => __('Web municipal', 'sitio-cero'),
        'search'                  => __('Busqueda', 'sitio-cero'),
    );
}

function sitio_cero_sanitize_google_menu_icon_name($icon_name)
{
    $icon_name = strtolower(trim((string) $icon_name));
    $icon_name = preg_replace('/[^a-z0-9_]/', '', $icon_name);

    $allowed_icons = sitio_cero_get_google_menu_icon_options();
    if (!isset($allowed_icons[$icon_name])) {
        return '';
    }

    return $icon_name;
}

function sitio_cero_hero_info_menu_fallback()
{
    $default_items = sitio_cero_get_hero_info_default_items();

    echo '<ul id="menu-quiero-informacion-de" class="acciones-bt">';
    foreach ($default_items as $item) {
        echo '<li class="menu-item">';
        echo '<a href="' . esc_url($item['url']) . '">';
        echo '<span class="acciones-bt__text">' . esc_html($item['label']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    echo '</ul>';
}

if (!class_exists('Sitio_Cero_Hero_Info_Menu_Walker')) {
    class Sitio_Cero_Hero_Info_Menu_Walker extends Walker_Nav_Menu
    {
        public function start_el(&$output, $data_object, $depth = 0, $args = null, $id = 0)
        {
            if (!$data_object instanceof WP_Post) {
                return;
            }

            $menu_item = $data_object;

            $classes = empty($menu_item->classes) ? array() : (array) $menu_item->classes;
            $classes[] = 'menu-item-' . $menu_item->ID;
            $class_names = '';
            if (!empty($classes)) {
                $sanitized_classes = array_filter(array_map('sanitize_html_class', $classes));
                $class_names = ' class="' . esc_attr(implode(' ', $sanitized_classes)) . '"';
            }

            $output .= '<li' . $class_names . '>';

            $atts = array();
            $atts['title']  = !empty($menu_item->attr_title) ? $menu_item->attr_title : '';
            $atts['target'] = !empty($menu_item->target) ? $menu_item->target : '';
            $atts['rel']    = !empty($menu_item->xfn) ? $menu_item->xfn : '';
            $atts['href']   = !empty($menu_item->url) ? $menu_item->url : '';

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if ('' === $value) {
                    continue;
                }

                $escaped_value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $escaped_value . '"';
            }

            $title = apply_filters('the_title', $menu_item->title, $menu_item->ID);
            $title = apply_filters('nav_menu_item_title', $title, $menu_item, $args, $depth);

            $item_output = isset($args->before) ? $args->before : '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= sitio_cero_get_menu_item_icon_markup($menu_item->ID);
            $item_output .= '<span class="acciones-bt__text">';
            $item_output .= (isset($args->link_before) ? $args->link_before : '') . $title . (isset($args->link_after) ? $args->link_after : '');
            $item_output .= '</span>';
            $item_output .= '</a>';
            $item_output .= isset($args->after) ? $args->after : '';

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args);
        }
    }
}

function sitio_cero_render_menu_item_icon_field($item_id, $menu_item)
{
    $stored_icon_value = get_post_meta($item_id, '_sitio_cero_menu_icon', true);
    if (!is_string($stored_icon_value)) {
        $stored_icon_value = '';
    }

    $selected_google_icon = '';
    $custom_icon_value = '';

    if (0 === strpos($stored_icon_value, 'google:')) {
        $selected_google_icon = sitio_cero_sanitize_google_menu_icon_name(substr($stored_icon_value, 7));
    } else {
        $custom_icon_value = $stored_icon_value;
    }

    $icon_options = sitio_cero_get_google_menu_icon_options();
    ?>
    <p class="description description-wide sitio-cero-menu-icon-field">
        <span class="sitio-cero-icon-picker__title"><?php esc_html_e('Icono Google (Material Symbols)', 'sitio-cero'); ?></span>
        <span class="description"><?php esc_html_e('Selecciona el icono visualmente.', 'sitio-cero'); ?></span>
    </p>
    <div class="sitio-cero-icon-picker" role="radiogroup" aria-label="<?php esc_attr_e('Seleccion de icono Google', 'sitio-cero'); ?>">
        <label class="sitio-cero-icon-picker__item">
            <input
                type="radio"
                class="sitio-cero-icon-picker__input"
                name="menu-item-sitio-cero-icon-google[<?php echo esc_attr((string) $item_id); ?>]"
                value=""
                <?php checked('', $selected_google_icon); ?>
            >
            <span class="sitio-cero-icon-picker__content">
                <span class="sitio-cero-icon-picker__preview sitio-cero-icon-picker__preview--none" aria-hidden="true">x</span>
                <span class="sitio-cero-icon-picker__label"><?php esc_html_e('Sin icono', 'sitio-cero'); ?></span>
            </span>
        </label>
        <?php foreach ($icon_options as $icon_name => $icon_label) : ?>
            <label class="sitio-cero-icon-picker__item">
                <input
                    type="radio"
                    class="sitio-cero-icon-picker__input"
                    name="menu-item-sitio-cero-icon-google[<?php echo esc_attr((string) $item_id); ?>]"
                    value="<?php echo esc_attr($icon_name); ?>"
                    <?php checked($selected_google_icon, $icon_name); ?>
                >
                <span class="sitio-cero-icon-picker__content">
                    <span class="sitio-cero-icon-picker__preview material-symbols-rounded" aria-hidden="true"><?php echo esc_html($icon_name); ?></span>
                    <span class="sitio-cero-icon-picker__label"><?php echo esc_html($icon_label); ?></span>
                </span>
            </label>
        <?php endforeach; ?>
    </div>
    <p class="description description-wide sitio-cero-menu-icon-field">
        <em><?php esc_html_e('Si eliges un icono Google y tambien escribes uno personalizado, se usara el icono personalizado.', 'sitio-cero'); ?></em>
    </p>
    <p class="description description-wide sitio-cero-menu-icon-field">
        <label for="edit-menu-item-sitio-cero-icon-custom-<?php echo esc_attr((string) $item_id); ?>">
            <?php esc_html_e('Icono personalizado (opcional, clase CSS o URL)', 'sitio-cero'); ?><br>
            <input
                type="text"
                id="edit-menu-item-sitio-cero-icon-custom-<?php echo esc_attr((string) $item_id); ?>"
                class="widefat code edit-menu-item-sitio-cero-icon-custom"
                name="menu-item-sitio-cero-icon-custom[<?php echo esc_attr((string) $item_id); ?>]"
                value="<?php echo esc_attr($custom_icon_value); ?>"
                placeholder="<?php echo esc_attr__('Ej: dashicons dashicons-admin-site o https://dominio.cl/icono.svg', 'sitio-cero'); ?>"
            >
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'sitio_cero_render_menu_item_icon_field', 10, 2);

function sitio_cero_save_menu_item_icon_field($menu_id, $menu_item_db_id)
{
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    $custom_icon_value = '';
    if (isset($_POST['menu-item-sitio-cero-icon-custom'])) {
        $raw_custom_icons = wp_unslash($_POST['menu-item-sitio-cero-icon-custom']);
        if (is_array($raw_custom_icons) && isset($raw_custom_icons[$menu_item_db_id])) {
            $custom_icon_value = sanitize_text_field($raw_custom_icons[$menu_item_db_id]);
            $custom_icon_value = trim($custom_icon_value);
        }
    }

    if ('' !== $custom_icon_value) {
        update_post_meta($menu_item_db_id, '_sitio_cero_menu_icon', $custom_icon_value);
        return;
    }

    $google_icon_value = '';
    if (isset($_POST['menu-item-sitio-cero-icon-google'])) {
        $raw_google_icons = wp_unslash($_POST['menu-item-sitio-cero-icon-google']);
        if (is_array($raw_google_icons) && isset($raw_google_icons[$menu_item_db_id])) {
            $google_icon_value = sitio_cero_sanitize_google_menu_icon_name($raw_google_icons[$menu_item_db_id]);
        }
    }

    if ('' !== $google_icon_value) {
        update_post_meta($menu_item_db_id, '_sitio_cero_menu_icon', 'google:' . $google_icon_value);
        return;
    }

    if (!isset($_POST['menu-item-sitio-cero-icon-google']) && !isset($_POST['menu-item-sitio-cero-icon-custom'])) {
        delete_post_meta($menu_item_db_id, '_sitio_cero_menu_icon');
        return;
    }

    delete_post_meta($menu_item_db_id, '_sitio_cero_menu_icon');
}
add_action('wp_update_nav_menu_item', 'sitio_cero_save_menu_item_icon_field', 10, 2);

function sitio_cero_register_lamina_post_type()
{
    $labels = array(
        'name'               => __('Laminas Hero', 'sitio-cero'),
        'singular_name'      => __('Lamina Hero', 'sitio-cero'),
        'menu_name'          => __('Laminas Hero', 'sitio-cero'),
        'name_admin_bar'     => __('Lamina Hero', 'sitio-cero'),
        'add_new'            => __('Agregar nueva', 'sitio-cero'),
        'add_new_item'       => __('Agregar nueva lamina', 'sitio-cero'),
        'new_item'           => __('Nueva lamina', 'sitio-cero'),
        'edit_item'          => __('Editar lamina', 'sitio-cero'),
        'view_item'          => __('Ver lamina', 'sitio-cero'),
        'all_items'          => __('Todas las laminas', 'sitio-cero'),
        'search_items'       => __('Buscar laminas', 'sitio-cero'),
        'not_found'          => __('No se encontraron laminas.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay laminas en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'lamina_hero',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => false,
            'show_in_admin_bar'  => true,
            'show_in_rest'       => true,
            'publicly_queryable' => false,
            'exclude_from_search'=> true,
            'has_archive'        => false,
            'rewrite'            => false,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-images-alt2',
            'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        )
    );
}
add_action('init', 'sitio_cero_register_lamina_post_type');

function sitio_cero_add_lamina_metaboxes()
{
    add_meta_box(
        'sitio_cero_lamina_options',
        __('Opciones de la lamina', 'sitio-cero'),
        'sitio_cero_render_lamina_metabox',
        'lamina_hero',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_lamina_metaboxes');

function sitio_cero_render_lamina_metabox($post)
{
    wp_nonce_field('sitio_cero_save_lamina_meta', 'sitio_cero_lamina_meta_nonce');

    $cta_text = get_post_meta($post->ID, 'sitio_cero_hero_cta_text', true);
    $cta_url = get_post_meta($post->ID, 'sitio_cero_hero_cta_url', true);

    if (!is_string($cta_text)) {
        $cta_text = '';
    }

    if (!is_string($cta_url)) {
        $cta_url = '';
    }
    ?>
    <p>
        <label for="sitio_cero_hero_cta_text"><strong><?php esc_html_e('Texto del boton principal', 'sitio-cero'); ?></strong></label><br>
        <input
            id="sitio_cero_hero_cta_text"
            name="sitio_cero_hero_cta_text"
            type="text"
            class="widefat"
            value="<?php echo esc_attr($cta_text); ?>"
            placeholder="<?php esc_attr_e('Ej: Ver detalle', 'sitio-cero'); ?>"
        >
    </p>

    <p>
        <label for="sitio_cero_hero_cta_url"><strong><?php esc_html_e('URL del boton principal', 'sitio-cero'); ?></strong></label><br>
        <input
            id="sitio_cero_hero_cta_url"
            name="sitio_cero_hero_cta_url"
            type="url"
            class="widefat"
            value="<?php echo esc_attr($cta_url); ?>"
            placeholder="https://..."
        >
    </p>
    <?php
}

function sitio_cero_save_lamina_meta($post_id)
{
    if (!isset($_POST['sitio_cero_lamina_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_lamina_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_lamina_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $cta_text = '';
    if (isset($_POST['sitio_cero_hero_cta_text'])) {
        $cta_text = sanitize_text_field(wp_unslash($_POST['sitio_cero_hero_cta_text']));
    }

    $cta_url = '';
    if (isset($_POST['sitio_cero_hero_cta_url'])) {
        $cta_url = esc_url_raw(wp_unslash($_POST['sitio_cero_hero_cta_url']));
    }

    if ('' !== $cta_text) {
        update_post_meta($post_id, 'sitio_cero_hero_cta_text', $cta_text);
    } else {
        delete_post_meta($post_id, 'sitio_cero_hero_cta_text');
    }

    if ('' !== $cta_url) {
        update_post_meta($post_id, 'sitio_cero_hero_cta_url', $cta_url);
    } else {
        delete_post_meta($post_id, 'sitio_cero_hero_cta_url');
    }
    
    delete_post_meta($post_id, 'sitio_cero_show_quick_box');
    delete_post_meta($post_id, 'sitio_cero_quick_box_title');
    delete_post_meta($post_id, 'sitio_cero_quick_box_items');
}
add_action('save_post_lamina_hero', 'sitio_cero_save_lamina_meta');

function sitio_cero_seed_default_lamina()
{
    if (!post_type_exists('lamina_hero')) {
        return;
    }

    $already_seeded = get_option('sitio_cero_default_lamina_seeded', '0');
    if ('1' === (string) $already_seeded) {
        return;
    }

    $existing_lamina = get_posts(
        array(
            'post_type'      => 'lamina_hero',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if (!empty($existing_lamina)) {
        update_option('sitio_cero_default_lamina_seeded', '1');
        return;
    }

    $post_id = wp_insert_post(
        array(
            'post_type'    => 'lamina_hero',
            'post_status'  => 'publish',
            'post_title'   => __('Lamina de ejemplo: Programa de limpieza comunal', 'sitio-cero'),
            'post_excerpt' => __('Revisa operativos de retiro de residuos, puntos limpios y calendario de intervencion por sectores de la comuna.', 'sitio-cero'),
            'post_content' => __('Esta es una lamina de ejemplo para que veas como funciona el slider del hero. Puedes editar este contenido o crear nuevas laminas desde el menu Laminas Hero.', 'sitio-cero'),
            'menu_order'   => 1,
        ),
        true
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_post_meta($post_id, 'sitio_cero_hero_cta_text', __('Conocer operativos', 'sitio-cero'));
    update_post_meta($post_id, 'sitio_cero_hero_cta_url', '#tramites');

    update_option('sitio_cero_default_lamina_seeded', '1');
}
add_action('init', 'sitio_cero_seed_default_lamina', 30);

function sitio_cero_add_clone_lamina_action($actions, $post)
{
    if (!$post instanceof WP_Post) {
        return $actions;
    }

    if ('lamina_hero' !== $post->post_type) {
        return $actions;
    }

    if (!current_user_can('edit_post', $post->ID)) {
        return $actions;
    }

    $clone_url = add_query_arg(
        array(
            'action' => 'sitio_cero_clone_lamina',
            'post'   => $post->ID,
        ),
        admin_url('admin.php')
    );

    $clone_url = wp_nonce_url($clone_url, 'sitio_cero_clone_lamina_' . $post->ID);
    $actions['sitio_cero_clone'] = '<a href="' . esc_url($clone_url) . '">' . esc_html__('Clonar', 'sitio-cero') . '</a>';

    return $actions;
}
add_filter('post_row_actions', 'sitio_cero_add_clone_lamina_action', 10, 2);

function sitio_cero_handle_clone_lamina_action()
{
    if (!isset($_GET['post'])) {
        wp_die(esc_html__('No se recibio una lamina para clonar.', 'sitio-cero'));
    }

    $source_post_id = absint(wp_unslash($_GET['post']));
    if (!$source_post_id) {
        wp_die(esc_html__('ID de lamina invalido.', 'sitio-cero'));
    }

    if (!current_user_can('edit_post', $source_post_id)) {
        wp_die(esc_html__('No tienes permisos para clonar esta lamina.', 'sitio-cero'));
    }

    check_admin_referer('sitio_cero_clone_lamina_' . $source_post_id);

    $source_post = get_post($source_post_id);
    if (!$source_post instanceof WP_Post || 'lamina_hero' !== $source_post->post_type) {
        wp_die(esc_html__('La lamina origen no existe.', 'sitio-cero'));
    }

    $clone_post_id = wp_insert_post(
        array(
            'post_type'    => 'lamina_hero',
            'post_status'  => 'draft',
            'post_title'   => sprintf(__('Copia de %s', 'sitio-cero'), $source_post->post_title),
            'post_content' => $source_post->post_content,
            'post_excerpt' => $source_post->post_excerpt,
            'menu_order'   => $source_post->menu_order,
            'post_author'  => get_current_user_id(),
        ),
        true
    );

    if (is_wp_error($clone_post_id) || !$clone_post_id) {
        wp_die(esc_html__('No se pudo clonar la lamina.', 'sitio-cero'));
    }

    $source_meta = get_post_meta($source_post_id);
    $excluded_meta_keys = array('_edit_lock', '_edit_last');

    foreach ($source_meta as $meta_key => $meta_values) {
        if (in_array($meta_key, $excluded_meta_keys, true)) {
            continue;
        }

        if (!is_array($meta_values)) {
            continue;
        }

        foreach ($meta_values as $meta_value) {
            add_post_meta($clone_post_id, $meta_key, maybe_unserialize($meta_value));
        }
    }

    $taxonomies = get_object_taxonomies('lamina_hero');
    foreach ($taxonomies as $taxonomy) {
        $term_ids = wp_get_object_terms($source_post_id, $taxonomy, array('fields' => 'ids'));
        if (is_wp_error($term_ids)) {
            continue;
        }

        wp_set_object_terms($clone_post_id, $term_ids, $taxonomy);
    }

    $redirect_url = add_query_arg(
        array(
            'post'   => $clone_post_id,
            'action' => 'edit',
            'cloned' => '1',
        ),
        admin_url('post.php')
    );

    wp_safe_redirect($redirect_url);
    exit;
}
add_action('admin_action_sitio_cero_clone_lamina', 'sitio_cero_handle_clone_lamina_action');

function sitio_cero_show_clone_lamina_notice()
{
    if (!is_admin()) {
        return;
    }

    if (!isset($_GET['cloned']) || '1' !== (string) wp_unslash($_GET['cloned'])) {
        return;
    }

    if (!isset($_GET['post'])) {
        return;
    }

    $post_id = absint(wp_unslash($_GET['post']));
    if (!$post_id || 'lamina_hero' !== get_post_type($post_id)) {
        return;
    }

    echo '<div class="notice notice-success is-dismissible"><p>';
    echo esc_html__('Lamina clonada correctamente. Ya puedes editar la copia.', 'sitio-cero');
    echo '</p></div>';
}
add_action('admin_notices', 'sitio_cero_show_clone_lamina_notice');
