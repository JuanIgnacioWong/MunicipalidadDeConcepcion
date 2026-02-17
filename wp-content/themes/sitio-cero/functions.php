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

function sitio_cero_enqueue_aviso_admin_assets($hook_suffix)
{
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'aviso' !== $screen->post_type) {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_media();

    wp_enqueue_style(
        'sitio-cero-admin-aviso-files',
        get_template_directory_uri() . '/assets/css/admin-aviso-files.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'sitio-cero-admin-aviso-files',
        get_template_directory_uri() . '/assets/js/admin-aviso-files.js',
        array('jquery'),
        $version,
        true
    );

    wp_localize_script(
        'sitio-cero-admin-aviso-files',
        'sitioCeroAvisoFiles',
        array(
            'frameTitle'  => __('Selecciona archivos', 'sitio-cero'),
            'frameButton' => __('Agregar archivos', 'sitio-cero'),
            'addItemText' => __('Agregar item manual', 'sitio-cero'),
            'removeText'  => __('Quitar', 'sitio-cero'),
        )
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_enqueue_aviso_admin_assets');

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

function sitio_cero_enqueue_direccion_municipal_admin_assets($hook_suffix)
{
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'direccion_municipal' !== $screen->post_type) {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_media();

    wp_enqueue_style(
        'sitio-cero-admin-aviso-files',
        get_template_directory_uri() . '/assets/css/admin-aviso-files.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'sitio-cero-admin-aviso-files',
        get_template_directory_uri() . '/assets/js/admin-aviso-files.js',
        array('jquery'),
        $version,
        true
    );

    wp_localize_script(
        'sitio-cero-admin-aviso-files',
        'sitioCeroAvisoFiles',
        array(
            'frameTitle'  => __('Selecciona archivos', 'sitio-cero'),
            'frameButton' => __('Agregar archivos', 'sitio-cero'),
            'addItemText' => __('Agregar item manual', 'sitio-cero'),
            'removeText'  => __('Quitar', 'sitio-cero'),
        )
    );

    wp_enqueue_style(
        'sitio-cero-admin-direccion-municipal',
        get_template_directory_uri() . '/assets/css/admin-direccion-municipal.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'sitio-cero-admin-direccion-municipal',
        get_template_directory_uri() . '/assets/js/admin-direccion-municipal.js',
        array('jquery'),
        $version,
        true
    );

    wp_localize_script(
        'sitio-cero-admin-direccion-municipal',
        'sitioCeroDireccionMunicipal',
        array(
            'phoneLabel'             => __('Telefono', 'sitio-cero'),
            'resourceBlockLabel'     => __('Bloque', 'sitio-cero'),
            'removeText'             => __('Quitar', 'sitio-cero'),
            'selectAccordionMessage' => __('Selecciona un acordeon para insertarlo.', 'sitio-cero'),
        )
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_enqueue_direccion_municipal_admin_assets');

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
    echo '<li><a href="' . $home . '#avisos">' . esc_html__('Avisos', 'sitio-cero') . '</a></li>';
    echo '<li><a href="' . $home . '#noticias">' . esc_html__('Noticias', 'sitio-cero') . '</a></li>';
    echo '<li><a href="' . $home . '#agenda">' . esc_html__('Agenda', 'sitio-cero') . '</a></li>';
    echo '</ul>';
}

function sitio_cero_register_footer_columna_post_type()
{
    $labels = array(
        'name'               => __('Columnas footer', 'sitio-cero'),
        'singular_name'      => __('Columna footer', 'sitio-cero'),
        'menu_name'          => __('Footer', 'sitio-cero'),
        'name_admin_bar'     => __('Columna footer', 'sitio-cero'),
        'add_new'            => __('Agregar nueva', 'sitio-cero'),
        'add_new_item'       => __('Agregar columna footer', 'sitio-cero'),
        'new_item'           => __('Nueva columna footer', 'sitio-cero'),
        'edit_item'          => __('Editar columna footer', 'sitio-cero'),
        'view_item'          => __('Ver columna footer', 'sitio-cero'),
        'all_items'          => __('Todas las columnas', 'sitio-cero'),
        'search_items'       => __('Buscar columnas', 'sitio-cero'),
        'not_found'          => __('No se encontraron columnas.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay columnas en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'footer_columna',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_admin_bar'  => true,
            'show_in_nav_menus'  => false,
            'show_in_rest'       => true,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'has_archive'        => false,
            'rewrite'            => false,
            'menu_position'      => 27,
            'menu_icon'          => 'dashicons-columns',
            'supports'           => array('title', 'editor', 'page-attributes'),
        )
    );
}
add_action('init', 'sitio_cero_register_footer_columna_post_type');

function sitio_cero_get_default_footer_columns()
{
    return array(
        array(
            'title'   => __('La municipalidad', 'sitio-cero'),
            'content' => '<ul><li><a href="#">Alcaldia</a></li><li><a href="#">Concejo municipal</a></li><li><a href="#">Direcciones</a></li><li><a href="#">Cuenta publica</a></li></ul>',
        ),
        array(
            'title'   => __('Servicios', 'sitio-cero'),
            'content' => '<ul><li><a href="#avisos">Avisos</a></li><li><a href="#">Patentes</a></li><li><a href="#">Permisos de circulacion</a></li><li><a href="#">Pagos municipales</a></li></ul>',
        ),
        array(
            'title'   => __('Transparencia', 'sitio-cero'),
            'content' => '<ul><li><a href="#">Ley de transparencia</a></li><li><a href="#">Compras publicas</a></li><li><a href="#">Datos abiertos</a></li><li><a href="#">Solicitudes de informacion</a></li></ul>',
        ),
        array(
            'title'   => __('Contacto', 'sitio-cero'),
            'content' => '<ul><li>Av. Principal 100, Concepcion</li><li>+56 2 3386 8000</li><li>contacto@municipio.cl</li><li>Lun a Vie: 08:30 - 14:00</li></ul>',
        ),
    );
}

function sitio_cero_get_footer_columns($limit = 4)
{
    $limit = absint($limit);
    if ($limit <= 0) {
        return array();
    }

    $posts = get_posts(
        array(
            'post_type'      => 'footer_columna',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'ASC',
                'ID'         => 'ASC',
            ),
            'no_found_rows'  => true,
        )
    );

    $columns = array();

    if (is_array($posts)) {
        foreach ($posts as $post) {
            if (!$post instanceof WP_Post) {
                continue;
            }

            $title = sanitize_text_field((string) $post->post_title);
            $raw_content = is_string($post->post_content) ? $post->post_content : '';

            $columns[] = array(
                'title'   => $title,
                'content' => apply_filters('the_content', $raw_content),
            );
        }
    }

    if (empty($columns)) {
        $defaults = sitio_cero_get_default_footer_columns();
        foreach ($defaults as $item) {
            if (count($columns) >= $limit) {
                break;
            }

            $columns[] = array(
                'title'   => isset($item['title']) ? sanitize_text_field((string) $item['title']) : '',
                'content' => isset($item['content']) ? (string) $item['content'] : '',
            );
        }
    }

    return array_slice($columns, 0, $limit);
}

function sitio_cero_seed_default_footer_columns()
{
    if (!post_type_exists('footer_columna')) {
        return;
    }

    $seed_version = '3';
    $already_seeded_version = (string) get_option('sitio_cero_default_footer_columns_seeded_version', '');
    if ($seed_version === $already_seeded_version) {
        return;
    }

    $existing_items = get_posts(
        array(
            'post_type'      => 'footer_columna',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if (!empty($existing_items)) {
        update_option('sitio_cero_default_footer_columns_seeded_version', $seed_version);
        return;
    }

    $defaults = sitio_cero_get_default_footer_columns();

    foreach ($defaults as $index => $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
        $content = isset($item['content']) ? (string) $item['content'] : '';
        if ('' === $title && '' === trim(wp_strip_all_tags($content))) {
            continue;
        }

        $post_id = wp_insert_post(
            array(
                'post_type'    => 'footer_columna',
                'post_status'  => 'publish',
                'post_title'   => $title,
                'post_content' => $content,
                'menu_order'   => (int) $index,
            ),
            true
        );

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, '_sitio_cero_footer_columna_default', '1');
    }

    update_option('sitio_cero_default_footer_columns_seeded_version', $seed_version);
}
add_action('init', 'sitio_cero_seed_default_footer_columns', 45);

function sitio_cero_register_direccion_municipal_post_type()
{
    $labels = array(
        'name'               => __('Direcciones municipales', 'sitio-cero'),
        'singular_name'      => __('Direccion municipal', 'sitio-cero'),
        'menu_name'          => __('Direcciones', 'sitio-cero'),
        'name_admin_bar'     => __('Direccion municipal', 'sitio-cero'),
        'add_new'            => __('Agregar nueva', 'sitio-cero'),
        'add_new_item'       => __('Agregar direccion municipal', 'sitio-cero'),
        'new_item'           => __('Nueva direccion municipal', 'sitio-cero'),
        'edit_item'          => __('Editar direccion municipal', 'sitio-cero'),
        'view_item'          => __('Ver direccion municipal', 'sitio-cero'),
        'all_items'          => __('Todas las direcciones', 'sitio-cero'),
        'search_items'       => __('Buscar direcciones', 'sitio-cero'),
        'not_found'          => __('No se encontraron direcciones.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay direcciones en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'direccion_municipal',
        array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'show_in_rest'      => true,
            'hierarchical'      => true,
            'has_archive'       => true,
            'rewrite'           => array('slug' => 'direcciones-municipales'),
            'menu_position'     => 24,
            'menu_icon'         => 'dashicons-building',
            'supports'          => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions'),
        )
    );
}
add_action('init', 'sitio_cero_register_direccion_municipal_post_type');

function sitio_cero_add_direccion_municipal_metaboxes()
{
    add_meta_box(
        'sitio_cero_direccion_municipal_datos',
        __('Estructura de la direccion municipal', 'sitio-cero'),
        'sitio_cero_render_direccion_municipal_metabox',
        'direccion_municipal',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_direccion_municipal_metaboxes');

function sitio_cero_get_direccion_allowed_html()
{
    $allowed = wp_kses_allowed_html('post');

    $allowed['iframe'] = array(
        'class'           => true,
        'id'              => true,
        'src'             => true,
        'title'           => true,
        'width'           => true,
        'height'          => true,
        'frameborder'     => true,
        'allow'           => true,
        'allowfullscreen' => true,
        'loading'         => true,
        'referrerpolicy'  => true,
        'style'           => true,
    );

    $allowed['video'] = array(
        'class'      => true,
        'id'         => true,
        'src'        => true,
        'controls'   => true,
        'autoplay'   => true,
        'loop'       => true,
        'muted'      => true,
        'playsinline'=> true,
        'poster'     => true,
        'preload'    => true,
        'width'      => true,
        'height'     => true,
        'style'      => true,
    );

    $allowed['source'] = array(
        'src'  => true,
        'type' => true,
    );

    if (!isset($allowed['div'])) {
        $allowed['div'] = array();
    }
    $allowed['div']['class'] = true;
    $allowed['div']['id'] = true;
    $allowed['div']['style'] = true;

    if (!isset($allowed['span'])) {
        $allowed['span'] = array();
    }
    $allowed['span']['class'] = true;
    $allowed['span']['id'] = true;
    $allowed['span']['style'] = true;

    return $allowed;
}

function sitio_cero_sanitize_direccion_html($value)
{
    $value = (string) $value;
    $value = preg_replace('/<\/?script[^>]*>/i', '', $value);
    $value = preg_replace('/\s+on[a-z]+\s*=\s*([\'"]).*?\1/i', '', $value);

    return trim((string) wp_kses($value, sitio_cero_get_direccion_allowed_html()));
}

function sitio_cero_sanitize_css_shorthand($value, $max_length = 120)
{
    $value = trim((string) $value);
    if ('' === $value) {
        return '';
    }

    $value = preg_replace('/[^#,%.\-a-zA-Z0-9\s()\/]/', '', $value);
    $value = preg_replace('/\s+/', ' ', (string) $value);
    $value = trim((string) $value);

    $max_length = absint($max_length);
    if ($max_length <= 0) {
        $max_length = 120;
    }

    return substr($value, 0, $max_length);
}

function sitio_cero_sanitize_direccion_subtabs($value)
{
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        $value = is_array($decoded) ? $decoded : array();
    }

    if (!is_array($value)) {
        return array();
    }

    $items = array();

    foreach ($value as $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
        $content = isset($item['content']) ? sitio_cero_sanitize_direccion_html((string) $item['content']) : '';

        if ('' === $title && '' === trim(wp_strip_all_tags($content))) {
            continue;
        }

        $items[] = array(
            'title'   => $title,
            'content' => $content,
        );
    }

    return $items;
}

function sitio_cero_get_direccion_accordion_items($post_id)
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return array();
    }

    $raw_items = get_post_meta($post_id, 'sitio_cero_direccion_acordeon_items', true);
    if (!is_array($raw_items)) {
        return array();
    }

    $items = array();
    foreach ($raw_items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
        $content = isset($item['content']) ? sitio_cero_sanitize_direccion_html((string) $item['content']) : '';
        $border = isset($item['border']) ? sitio_cero_sanitize_css_shorthand((string) $item['border'], 80) : '';
        $margin = isset($item['margin']) ? sitio_cero_sanitize_css_shorthand((string) $item['margin'], 60) : '';
        $padding = isset($item['padding']) ? sitio_cero_sanitize_css_shorthand((string) $item['padding'], 60) : '';
        $subtabs = isset($item['subtabs']) ? sitio_cero_sanitize_direccion_subtabs($item['subtabs']) : array();

        if (empty($subtabs) && '' !== trim(wp_strip_all_tags($content))) {
            // Backward compatibility: migrate legacy item content into one subtab.
            $subtabs[] = array(
                'title'   => __('Detalle', 'sitio-cero'),
                'content' => $content,
            );
        }

        if ('' === $title) {
            continue;
        }

        $items[] = array(
            'title'   => $title,
            'border'  => $border,
            'margin'  => $margin,
            'padding' => $padding,
            'subtabs' => $subtabs,
        );
    }

    return $items;
}

function sitio_cero_sanitize_direccion_resource_blocks($value)
{
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        $value = is_array($decoded) ? $decoded : array();
    }

    if (!is_array($value)) {
        return array();
    }

    $blocks = array();
    foreach ($value as $block) {
        if (!is_array($block)) {
            continue;
        }

        $title = isset($block['title']) ? sanitize_text_field((string) $block['title']) : '';
        $type = isset($block['type']) ? sanitize_key((string) $block['type']) : 'documentos';
        if (!in_array($type, array('documentos', 'archivos'), true)) {
            $type = 'documentos';
        }

        $html = isset($block['html']) ? sitio_cero_sanitize_direccion_html((string) $block['html']) : '';
        $links = isset($block['links']) ? sitio_cero_sanitize_aviso_links_textarea((string) $block['links']) : '';

        if (
            '' === $title
            && '' === trim((string) wp_strip_all_tags($html))
            && '' === trim($links)
        ) {
            continue;
        }

        $blocks[] = array(
            'title' => $title,
            'type'  => $type,
            'html'  => $html,
            'links' => $links,
        );
    }

    return $blocks;
}

function sitio_cero_get_direccion_resource_blocks($post_id)
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return array();
    }

    $raw_blocks = get_post_meta($post_id, 'sitio_cero_direccion_resource_blocks', true);
    if (!is_array($raw_blocks)) {
        return array();
    }

    return sitio_cero_sanitize_direccion_resource_blocks($raw_blocks);
}

function sitio_cero_render_direccion_municipal_metabox($post)
{
    wp_nonce_field('sitio_cero_save_direccion_municipal_meta', 'sitio_cero_direccion_municipal_meta_nonce');

    $director = get_post_meta($post->ID, 'sitio_cero_direccion_director', true);
    $profesion = get_post_meta($post->ID, 'sitio_cero_direccion_profesion', true);
    $telefonos = get_post_meta($post->ID, 'sitio_cero_direccion_telefonos', true);
    $email = get_post_meta($post->ID, 'sitio_cero_direccion_email', true);
    $direccion = get_post_meta($post->ID, 'sitio_cero_direccion_direccion', true);
    $mapa_url = get_post_meta($post->ID, 'sitio_cero_direccion_mapa_url', true);
    $custom_html = get_post_meta($post->ID, 'sitio_cero_direccion_custom_html', true);
    $custom_css = get_post_meta($post->ID, 'sitio_cero_direccion_custom_css', true);

    if (!is_string($director)) {
        $director = '';
    }
    if (!is_string($profesion)) {
        $profesion = '';
    }
    if (!is_array($telefonos)) {
        $telefonos = array();
    }
    if (empty($telefonos)) {
        $telefonos = array('');
    }
    if (!is_string($email)) {
        $email = '';
    }
    if (!is_string($direccion)) {
        $direccion = '';
    }
    if (!is_string($mapa_url)) {
        $mapa_url = '';
    }
    if (!is_string($custom_html)) {
        $custom_html = '';
    }
    if (!is_string($custom_css)) {
        $custom_css = '';
    }

    $documentos = get_post_meta($post->ID, 'sitio_cero_direccion_documentos', true);
    if (!is_string($documentos)) {
        $documentos = '';
    }
    $archivos = get_post_meta($post->ID, 'sitio_cero_direccion_archivos', true);
    if (!is_string($archivos)) {
        $archivos = '';
    }
    $recursos_titulo = get_post_meta($post->ID, 'sitio_cero_direccion_recursos_titulo', true);
    if (!is_string($recursos_titulo)) {
        $recursos_titulo = '';
    }
    $documentos_titulo = get_post_meta($post->ID, 'sitio_cero_direccion_documentos_titulo', true);
    if (!is_string($documentos_titulo)) {
        $documentos_titulo = '';
    }
    $archivos_titulo = get_post_meta($post->ID, 'sitio_cero_direccion_archivos_titulo', true);
    if (!is_string($archivos_titulo)) {
        $archivos_titulo = '';
    }
    $documentos_html = get_post_meta($post->ID, 'sitio_cero_direccion_documentos_html', true);
    if (!is_string($documentos_html)) {
        $documentos_html = '';
    }
    $archivos_html = get_post_meta($post->ID, 'sitio_cero_direccion_archivos_html', true);
    if (!is_string($archivos_html)) {
        $archivos_html = '';
    }
    $icon_options = sitio_cero_get_aviso_file_icon_options();
    $resource_type_options = array(
        'documentos' => __('Documentos', 'sitio-cero'),
        'archivos'   => __('Archivos', 'sitio-cero'),
    );
    $acordeon_embed_options = array();
    if (post_type_exists('acordeon_embed')) {
        $acordeon_posts = get_posts(
            array(
                'post_type'      => 'acordeon_embed',
                'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'no_found_rows'  => true,
            )
        );

        foreach ($acordeon_posts as $acordeon_post) {
            if (!$acordeon_post instanceof WP_Post) {
                continue;
            }

            $post_title = trim((string) $acordeon_post->post_title);
            if ('' === $post_title) {
                $post_title = sprintf(__('Acordeon #%d', 'sitio-cero'), (int) $acordeon_post->ID);
            }

            $acordeon_embed_options[] = array(
                'id'    => (int) $acordeon_post->ID,
                'title' => sanitize_text_field($post_title),
            );
        }
    }

    $resource_blocks = sitio_cero_get_direccion_resource_blocks($post->ID);

    if (empty($resource_blocks)) {
        if ('' !== trim($documentos) || '' !== trim((string) wp_strip_all_tags($documentos_html)) || '' !== trim($documentos_titulo)) {
            $resource_blocks[] = array(
                'title' => '' !== trim($documentos_titulo) ? $documentos_titulo : __('Documentos', 'sitio-cero'),
                'type'  => 'documentos',
                'html'  => $documentos_html,
                'links' => $documentos,
            );
        }

        if ('' !== trim($archivos) || '' !== trim((string) wp_strip_all_tags($archivos_html)) || '' !== trim($archivos_titulo)) {
            $resource_blocks[] = array(
                'title' => '' !== trim($archivos_titulo) ? $archivos_titulo : __('Archivos', 'sitio-cero'),
                'type'  => 'archivos',
                'html'  => $archivos_html,
                'links' => $archivos,
            );
        }
    }

    if (empty($resource_blocks)) {
        $resource_blocks[] = array(
            'title' => __('Documentos', 'sitio-cero'),
            'type'  => 'documentos',
            'html'  => '',
            'links' => '',
        );
    }

    ?>
    <div class="sitio-cero-dm-metabox">
        <h3><?php esc_html_e('Columna 1: Organizacion', 'sitio-cero'); ?></h3>
        <div class="sitio-cero-dm-grid">
            <p>
                <label for="sitio_cero_direccion_director"><strong><?php esc_html_e('Director', 'sitio-cero'); ?></strong></label>
                <input id="sitio_cero_direccion_director" name="sitio_cero_direccion_director" type="text" class="widefat" value="<?php echo esc_attr($director); ?>" placeholder="<?php esc_attr_e('Nombre del director o directora', 'sitio-cero'); ?>">
            </p>
            <p>
                <label for="sitio_cero_direccion_profesion"><strong><?php esc_html_e('Profesion (opcional)', 'sitio-cero'); ?></strong></label>
                <input id="sitio_cero_direccion_profesion" name="sitio_cero_direccion_profesion" type="text" class="widefat" value="<?php echo esc_attr($profesion); ?>" placeholder="<?php esc_attr_e('Ejemplo: Administrador publico', 'sitio-cero'); ?>">
            </p>
            <p>
                <label for="sitio_cero_direccion_email"><strong><?php esc_html_e('Email', 'sitio-cero'); ?></strong></label>
                <input id="sitio_cero_direccion_email" name="sitio_cero_direccion_email" type="email" class="widefat" value="<?php echo esc_attr($email); ?>" placeholder="direccion@municipio.cl">
            </p>
            <p>
                <label for="sitio_cero_direccion_direccion"><strong><?php esc_html_e('Direccion', 'sitio-cero'); ?></strong></label>
                <input id="sitio_cero_direccion_direccion" name="sitio_cero_direccion_direccion" type="text" class="widefat" value="<?php echo esc_attr($direccion); ?>" placeholder="<?php esc_attr_e('Calle, numero, comuna', 'sitio-cero'); ?>">
            </p>
        </div>

        <p><strong><?php esc_html_e('Telefonos (puedes agregar mas de uno)', 'sitio-cero'); ?></strong></p>
        <div class="sitio-cero-dm-phones">
            <div class="sitio-cero-dm-phones__list" data-phones-list>
                <?php foreach ($telefonos as $telefono) : ?>
                    <div class="sitio-cero-dm-phones__row" data-phone-row>
                        <input type="text" class="widefat" name="sitio_cero_direccion_telefonos[]" value="<?php echo esc_attr(sanitize_text_field((string) $telefono)); ?>" placeholder="+56 41 220 0000">
                        <button type="button" class="button-link-delete" data-phone-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button button-secondary" data-phone-add><?php esc_html_e('Agregar telefono', 'sitio-cero'); ?></button>
            <template data-phone-template>
                <div class="sitio-cero-dm-phones__row" data-phone-row>
                    <input type="text" class="widefat" name="sitio_cero_direccion_telefonos[]" value="" placeholder="+56 41 220 0000">
                    <button type="button" class="button-link-delete" data-phone-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                </div>
            </template>
        </div>

        <hr>
        <h3><?php esc_html_e('Columna 2: Mapa (Google Maps)', 'sitio-cero'); ?></h3>
        <p>
            <label for="sitio_cero_direccion_mapa_url"><strong><?php esc_html_e('URL embed de Google Maps (opcional)', 'sitio-cero'); ?></strong></label>
            <input id="sitio_cero_direccion_mapa_url" name="sitio_cero_direccion_mapa_url" type="url" class="widefat" value="<?php echo esc_attr(esc_url_raw($mapa_url)); ?>" placeholder="https://www.google.com/maps/embed?...">
            <small><?php esc_html_e('Si lo dejas vacio, el mapa se generara desde el campo Direccion.', 'sitio-cero'); ?></small>
        </p>

        <hr>
        <h3><?php esc_html_e('Documentos y archivos', 'sitio-cero'); ?></h3>

        <p>
            <label for="sitio_cero_direccion_recursos_titulo"><strong><?php esc_html_e('Titulo principal de la seccion (opcional)', 'sitio-cero'); ?></strong></label>
            <input id="sitio_cero_direccion_recursos_titulo" name="sitio_cero_direccion_recursos_titulo" type="text" class="widefat" value="<?php echo esc_attr($recursos_titulo); ?>" placeholder="<?php esc_attr_e('Ejemplo: Documentos y archivos', 'sitio-cero'); ?>">
        </p>
        <p class="description"><?php esc_html_e('Puedes crear bloques ilimitados. Cada bloque permite definir tipo (documentos o archivos), titulo, texto/HTML embebido e items con enlace e icono.', 'sitio-cero'); ?></p>

        <div class="sitio-cero-dm-resource-blocks" data-dm-resource-blocks>
            <div class="sitio-cero-dm-resource-blocks__list" data-dm-resource-blocks-list>
                <?php foreach ($resource_blocks as $block_index => $resource_block) : ?>
                    <?php
                    $block_type = isset($resource_block['type']) ? sanitize_key((string) $resource_block['type']) : 'documentos';
                    if (!in_array($block_type, array('documentos', 'archivos'), true)) {
                        $block_type = 'documentos';
                    }
                    $block_title = isset($resource_block['title']) ? sanitize_text_field((string) $resource_block['title']) : '';
                    $block_html = isset($resource_block['html']) ? (string) $resource_block['html'] : '';
                    $block_links = isset($resource_block['links']) ? (string) $resource_block['links'] : '';
                    $block_links_items = sitio_cero_parse_aviso_links_textarea($block_links);
                    $block_key = 'existing-' . $block_index;
                    $block_html_id = 'sitio_cero_direccion_resource_block_html_' . $block_key;
                    $block_links_id = 'sitio_cero_direccion_resource_block_links_' . $block_key;
                    ?>
                    <div class="sitio-cero-dm-resource-block" data-dm-resource-block-row>
                        <div class="sitio-cero-dm-resource-block__head">
                            <strong><?php echo esc_html(sprintf(__('Bloque %d', 'sitio-cero'), $block_index + 1)); ?></strong>
                            <button type="button" class="button-link-delete" data-dm-resource-block-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                        </div>
                        <div class="sitio-cero-dm-grid">
                            <p>
                                <label><strong><?php esc_html_e('Titulo del bloque', 'sitio-cero'); ?></strong></label>
                                <input type="text" class="widefat" name="sitio_cero_direccion_resource_block_title[]" value="<?php echo esc_attr($block_title); ?>" placeholder="<?php esc_attr_e('Ejemplo: Documentos tributarios', 'sitio-cero'); ?>">
                            </p>
                            <p>
                                <label><strong><?php esc_html_e('Tipo de bloque', 'sitio-cero'); ?></strong></label>
                                <select class="widefat" name="sitio_cero_direccion_resource_block_type[]">
                                    <?php foreach ($resource_type_options as $type_key => $type_label) : ?>
                                        <option value="<?php echo esc_attr($type_key); ?>"<?php selected($block_type, $type_key); ?>><?php echo esc_html($type_label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>
                        </div>

                        <p>
                            <label><strong><?php esc_html_e('Texto / HTML / embebido del bloque (opcional)', 'sitio-cero'); ?></strong></label>
                            <textarea id="<?php echo esc_attr($block_html_id); ?>" class="widefat" rows="5" name="sitio_cero_direccion_resource_block_html[]" placeholder="<?php esc_attr_e('Ejemplo: <p>Informacion del bloque...</p><iframe ...></iframe>', 'sitio-cero'); ?>"><?php echo esc_textarea($block_html); ?></textarea>
                        </p>
                        <div class="sitio-cero-dm-embed-picker">
                            <label><strong><?php esc_html_e('Insertar acordeon embebido (opcional)', 'sitio-cero'); ?></strong></label>
                            <div class="sitio-cero-dm-embed-picker__controls">
                                <select class="widefat" data-embed-shortcode-select data-target="#<?php echo esc_attr($block_html_id); ?>"<?php disabled(empty($acordeon_embed_options)); ?>>
                                    <option value=""><?php esc_html_e('Selecciona un acordeon...', 'sitio-cero'); ?></option>
                                    <?php foreach ($acordeon_embed_options as $acordeon_option) : ?>
                                        <option value="<?php echo esc_attr((string) $acordeon_option['id']); ?>"><?php echo esc_html($acordeon_option['title']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button button-secondary" data-embed-shortcode-insert data-target="#<?php echo esc_attr($block_html_id); ?>"<?php disabled(empty($acordeon_embed_options)); ?>>
                                    <?php esc_html_e('Insertar acordeon', 'sitio-cero'); ?>
                                </button>
                            </div>
                            <p class="description">
                                <?php if (empty($acordeon_embed_options)) : ?>
                                    <?php esc_html_e('Aun no hay acordeones creados. Crea uno en Acordeones para poder insertarlo.', 'sitio-cero'); ?>
                                <?php else : ?>
                                    <?php esc_html_e('Se insertara el shortcode del acordeon en este campo.', 'sitio-cero'); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <p><strong><?php esc_html_e('Items del bloque (titulo + enlace + icono)', 'sitio-cero'); ?></strong></p>
                        <div class="sitio-cero-aviso-files" data-target="#<?php echo esc_attr($block_links_id); ?>">
                            <div class="sitio-cero-aviso-files__actions">
                                <button type="button" class="button button-secondary sitio-cero-aviso-files__library">
                                    <?php esc_html_e('Seleccionar desde biblioteca', 'sitio-cero'); ?>
                                </button>
                                <button type="button" class="button button-secondary sitio-cero-aviso-files__add">
                                    <?php esc_html_e('Agregar item manual', 'sitio-cero'); ?>
                                </button>
                            </div>
                            <div class="sitio-cero-aviso-files__list" data-file-list>
                                <?php foreach ($block_links_items as $item) : ?>
                                    <?php
                                    $item_label = isset($item['label']) ? (string) $item['label'] : '';
                                    $item_url = isset($item['url']) ? (string) $item['url'] : '';
                                    $item_icon = isset($item['icon']) ? (string) $item['icon'] : '';
                                    ?>
                                    <div class="sitio-cero-aviso-files__row" data-file-row>
                                        <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="<?php echo esc_attr($item_label); ?>">
                                        <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="<?php echo esc_attr($item_url); ?>">
                                        <select class="sitio-cero-aviso-files__select" data-key="icon">
                                            <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                                                <option value="<?php echo esc_attr($icon_key); ?>"<?php selected($item_icon, $icon_key); ?>><?php echo esc_html($icon_label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <template class="sitio-cero-aviso-files__template">
                                <div class="sitio-cero-aviso-files__row" data-file-row>
                                    <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="">
                                    <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="">
                                    <select class="sitio-cero-aviso-files__select" data-key="icon">
                                        <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                                            <option value="<?php echo esc_attr($icon_key); ?>"><?php echo esc_html($icon_label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                                </div>
                            </template>
                        </div>
                        <textarea id="<?php echo esc_attr($block_links_id); ?>" name="sitio_cero_direccion_resource_block_links[]" class="widefat" rows="4" hidden><?php echo esc_textarea($block_links); ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button button-primary" data-dm-resource-block-add><?php esc_html_e('Agregar bloque', 'sitio-cero'); ?></button>

            <template data-dm-resource-block-template>
                <div class="sitio-cero-dm-resource-block" data-dm-resource-block-row>
                    <div class="sitio-cero-dm-resource-block__head">
                        <strong><?php esc_html_e('Bloque', 'sitio-cero'); ?></strong>
                        <button type="button" class="button-link-delete" data-dm-resource-block-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                    </div>
                    <div class="sitio-cero-dm-grid">
                        <p>
                            <label><strong><?php esc_html_e('Titulo del bloque', 'sitio-cero'); ?></strong></label>
                            <input type="text" class="widefat" name="sitio_cero_direccion_resource_block_title[]" value="" placeholder="<?php esc_attr_e('Ejemplo: Documentos tributarios', 'sitio-cero'); ?>">
                        </p>
                        <p>
                            <label><strong><?php esc_html_e('Tipo de bloque', 'sitio-cero'); ?></strong></label>
                            <select class="widefat" name="sitio_cero_direccion_resource_block_type[]">
                                <?php foreach ($resource_type_options as $type_key => $type_label) : ?>
                                    <option value="<?php echo esc_attr($type_key); ?>"<?php selected('documentos', $type_key); ?>><?php echo esc_html($type_label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                    </div>

                    <p>
                        <label><strong><?php esc_html_e('Texto / HTML / embebido del bloque (opcional)', 'sitio-cero'); ?></strong></label>
                        <textarea id="sitio_cero_direccion_resource_block_html___KEY__" class="widefat" rows="5" name="sitio_cero_direccion_resource_block_html[]" placeholder="<?php esc_attr_e('Ejemplo: <p>Informacion del bloque...</p><iframe ...></iframe>', 'sitio-cero'); ?>"></textarea>
                    </p>
                    <div class="sitio-cero-dm-embed-picker">
                        <label><strong><?php esc_html_e('Insertar acordeon embebido (opcional)', 'sitio-cero'); ?></strong></label>
                        <div class="sitio-cero-dm-embed-picker__controls">
                            <select class="widefat" data-embed-shortcode-select data-target="#sitio_cero_direccion_resource_block_html___KEY__"<?php disabled(empty($acordeon_embed_options)); ?>>
                                <option value=""><?php esc_html_e('Selecciona un acordeon...', 'sitio-cero'); ?></option>
                                <?php foreach ($acordeon_embed_options as $acordeon_option) : ?>
                                    <option value="<?php echo esc_attr((string) $acordeon_option['id']); ?>"><?php echo esc_html($acordeon_option['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="button button-secondary" data-embed-shortcode-insert data-target="#sitio_cero_direccion_resource_block_html___KEY__"<?php disabled(empty($acordeon_embed_options)); ?>>
                                <?php esc_html_e('Insertar acordeon', 'sitio-cero'); ?>
                            </button>
                        </div>
                        <p class="description">
                            <?php if (empty($acordeon_embed_options)) : ?>
                                <?php esc_html_e('Aun no hay acordeones creados. Crea uno en Acordeones para poder insertarlo.', 'sitio-cero'); ?>
                            <?php else : ?>
                                <?php esc_html_e('Se insertara el shortcode del acordeon en este campo.', 'sitio-cero'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <p><strong><?php esc_html_e('Items del bloque (titulo + enlace + icono)', 'sitio-cero'); ?></strong></p>
                    <div class="sitio-cero-aviso-files" data-target="#sitio_cero_direccion_resource_block_links___KEY__">
                        <div class="sitio-cero-aviso-files__actions">
                            <button type="button" class="button button-secondary sitio-cero-aviso-files__library">
                                <?php esc_html_e('Seleccionar desde biblioteca', 'sitio-cero'); ?>
                            </button>
                            <button type="button" class="button button-secondary sitio-cero-aviso-files__add">
                                <?php esc_html_e('Agregar item manual', 'sitio-cero'); ?>
                            </button>
                        </div>
                        <div class="sitio-cero-aviso-files__list" data-file-list></div>
                        <template class="sitio-cero-aviso-files__template">
                            <div class="sitio-cero-aviso-files__row" data-file-row>
                                <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="">
                                <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="">
                                <select class="sitio-cero-aviso-files__select" data-key="icon">
                                    <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                                        <option value="<?php echo esc_attr($icon_key); ?>"><?php echo esc_html($icon_label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                            </div>
                        </template>
                    </div>
                    <textarea id="sitio_cero_direccion_resource_block_links___KEY__" name="sitio_cero_direccion_resource_block_links[]" class="widefat" rows="4" hidden></textarea>
                </div>
            </template>
        </div>

        <hr>
        <h3><?php esc_html_e('Bloque libre adicional', 'sitio-cero'); ?></h3>
        <p>
            <label for="sitio_cero_direccion_custom_html"><strong><?php esc_html_e('HTML libre (video, embebidos, estructura)', 'sitio-cero'); ?></strong></label>
            <textarea id="sitio_cero_direccion_custom_html" name="sitio_cero_direccion_custom_html" class="widefat" rows="7" placeholder="<?php esc_attr_e('Ejemplo: <h3>Atencion ciudadana</h3><p>Texto...</p><iframe ...></iframe>', 'sitio-cero'); ?>"><?php echo esc_textarea($custom_html); ?></textarea>
        </p>
        <div class="sitio-cero-dm-embed-picker">
            <label><strong><?php esc_html_e('Insertar acordeon embebido (opcional)', 'sitio-cero'); ?></strong></label>
            <div class="sitio-cero-dm-embed-picker__controls">
                <select class="widefat" data-embed-shortcode-select data-target="#sitio_cero_direccion_custom_html"<?php disabled(empty($acordeon_embed_options)); ?>>
                    <option value=""><?php esc_html_e('Selecciona un acordeon...', 'sitio-cero'); ?></option>
                    <?php foreach ($acordeon_embed_options as $acordeon_option) : ?>
                        <option value="<?php echo esc_attr((string) $acordeon_option['id']); ?>"><?php echo esc_html($acordeon_option['title']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="button button-secondary" data-embed-shortcode-insert data-target="#sitio_cero_direccion_custom_html"<?php disabled(empty($acordeon_embed_options)); ?>>
                    <?php esc_html_e('Insertar acordeon', 'sitio-cero'); ?>
                </button>
            </div>
            <p class="description">
                <?php if (empty($acordeon_embed_options)) : ?>
                    <?php esc_html_e('Aun no hay acordeones creados. Crea uno en Acordeones para poder insertarlo.', 'sitio-cero'); ?>
                <?php else : ?>
                    <?php esc_html_e('Se insertara el shortcode del acordeon en este campo.', 'sitio-cero'); ?>
                <?php endif; ?>
            </p>
        </div>
        <p>
            <label for="sitio_cero_direccion_custom_css"><strong><?php esc_html_e('CSS libre (opcional)', 'sitio-cero'); ?></strong></label>
            <textarea id="sitio_cero_direccion_custom_css" name="sitio_cero_direccion_custom_css" class="widefat" rows="6" placeholder="<?php esc_attr_e('Usa {{selector}} para apuntar este contenido.', 'sitio-cero'); ?>"><?php echo esc_textarea($custom_css); ?></textarea>
        </p>
    </div>
    <?php
}

function sitio_cero_save_direccion_municipal_meta($post_id)
{
    if (!isset($_POST['sitio_cero_direccion_municipal_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_direccion_municipal_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_direccion_municipal_meta')) {
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

    $director = isset($_POST['sitio_cero_direccion_director']) ? sanitize_text_field(wp_unslash($_POST['sitio_cero_direccion_director'])) : '';
    $profesion = isset($_POST['sitio_cero_direccion_profesion']) ? sanitize_text_field(wp_unslash($_POST['sitio_cero_direccion_profesion'])) : '';
    $email = isset($_POST['sitio_cero_direccion_email']) ? sanitize_email(wp_unslash($_POST['sitio_cero_direccion_email'])) : '';
    $direccion = isset($_POST['sitio_cero_direccion_direccion']) ? sanitize_text_field(wp_unslash($_POST['sitio_cero_direccion_direccion'])) : '';
    $mapa_url = isset($_POST['sitio_cero_direccion_mapa_url']) ? esc_url_raw(wp_unslash($_POST['sitio_cero_direccion_mapa_url'])) : '';

    if ('' !== $director) {
        update_post_meta($post_id, 'sitio_cero_direccion_director', $director);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_director');
    }

    if ('' !== $profesion) {
        update_post_meta($post_id, 'sitio_cero_direccion_profesion', $profesion);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_profesion');
    }

    if ('' !== $email) {
        update_post_meta($post_id, 'sitio_cero_direccion_email', $email);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_email');
    }

    if ('' !== $direccion) {
        update_post_meta($post_id, 'sitio_cero_direccion_direccion', $direccion);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_direccion');
    }

    if ('' !== $mapa_url) {
        update_post_meta($post_id, 'sitio_cero_direccion_mapa_url', $mapa_url);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_mapa_url');
    }

    $telefonos = array();
    if (isset($_POST['sitio_cero_direccion_telefonos']) && is_array($_POST['sitio_cero_direccion_telefonos'])) {
        foreach (wp_unslash($_POST['sitio_cero_direccion_telefonos']) as $raw_phone) {
            $phone = sanitize_text_field((string) $raw_phone);
            $phone = preg_replace('/\s+/', ' ', $phone);
            $phone = trim((string) $phone);
            if ('' !== $phone && !in_array($phone, $telefonos, true)) {
                $telefonos[] = $phone;
            }
        }
    }

    if (!empty($telefonos)) {
        update_post_meta($post_id, 'sitio_cero_direccion_telefonos', $telefonos);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_telefonos');
    }

    $recursos_titulo = isset($_POST['sitio_cero_direccion_recursos_titulo'])
        ? sanitize_text_field(wp_unslash($_POST['sitio_cero_direccion_recursos_titulo']))
        : '';
    if ('' !== $recursos_titulo) {
        update_post_meta($post_id, 'sitio_cero_direccion_recursos_titulo', $recursos_titulo);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_recursos_titulo');
    }

    $resource_titles = isset($_POST['sitio_cero_direccion_resource_block_title']) && is_array($_POST['sitio_cero_direccion_resource_block_title'])
        ? wp_unslash($_POST['sitio_cero_direccion_resource_block_title'])
        : array();
    $resource_types = isset($_POST['sitio_cero_direccion_resource_block_type']) && is_array($_POST['sitio_cero_direccion_resource_block_type'])
        ? wp_unslash($_POST['sitio_cero_direccion_resource_block_type'])
        : array();
    $resource_htmls = isset($_POST['sitio_cero_direccion_resource_block_html']) && is_array($_POST['sitio_cero_direccion_resource_block_html'])
        ? wp_unslash($_POST['sitio_cero_direccion_resource_block_html'])
        : array();
    $resource_links = isset($_POST['sitio_cero_direccion_resource_block_links']) && is_array($_POST['sitio_cero_direccion_resource_block_links'])
        ? wp_unslash($_POST['sitio_cero_direccion_resource_block_links'])
        : array();

    $resource_total = max(count($resource_titles), count($resource_types), count($resource_htmls), count($resource_links));
    $resource_blocks = array();

    for ($index = 0; $index < $resource_total; $index++) {
        $block_title = isset($resource_titles[$index]) ? sanitize_text_field((string) $resource_titles[$index]) : '';
        $block_type = isset($resource_types[$index]) ? sanitize_key((string) $resource_types[$index]) : 'documentos';
        if (!in_array($block_type, array('documentos', 'archivos'), true)) {
            $block_type = 'documentos';
        }
        $block_html = isset($resource_htmls[$index]) ? sitio_cero_sanitize_direccion_html((string) $resource_htmls[$index]) : '';
        $block_links = isset($resource_links[$index]) ? sitio_cero_sanitize_aviso_links_textarea((string) $resource_links[$index]) : '';

        if (
            '' === $block_title
            && '' === trim((string) wp_strip_all_tags($block_html))
            && '' === trim($block_links)
        ) {
            continue;
        }

        $resource_blocks[] = array(
            'title' => $block_title,
            'type'  => $block_type,
            'html'  => $block_html,
            'links' => $block_links,
        );
    }

    if (!empty($resource_blocks)) {
        update_post_meta($post_id, 'sitio_cero_direccion_resource_blocks', $resource_blocks);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_resource_blocks');
    }

    $first_documentos = null;
    $first_archivos = null;
    foreach ($resource_blocks as $block) {
        if (!is_array($block)) {
            continue;
        }
        $current_type = isset($block['type']) ? sanitize_key((string) $block['type']) : 'documentos';
        if ('documentos' === $current_type && null === $first_documentos) {
            $first_documentos = $block;
        }
        if ('archivos' === $current_type && null === $first_archivos) {
            $first_archivos = $block;
        }
    }

    if (is_array($first_documentos)) {
        $legacy_title = isset($first_documentos['title']) ? sanitize_text_field((string) $first_documentos['title']) : '';
        $legacy_html = isset($first_documentos['html']) ? sitio_cero_sanitize_direccion_html((string) $first_documentos['html']) : '';
        $legacy_links = isset($first_documentos['links']) ? sitio_cero_sanitize_aviso_links_textarea((string) $first_documentos['links']) : '';

        if ('' !== $legacy_title) {
            update_post_meta($post_id, 'sitio_cero_direccion_documentos_titulo', $legacy_title);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_documentos_titulo');
        }

        if ('' !== $legacy_html) {
            update_post_meta($post_id, 'sitio_cero_direccion_documentos_html', $legacy_html);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_documentos_html');
        }

        if ('' !== $legacy_links) {
            update_post_meta($post_id, 'sitio_cero_direccion_documentos', $legacy_links);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_documentos');
        }
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_documentos_titulo');
        delete_post_meta($post_id, 'sitio_cero_direccion_documentos_html');
        delete_post_meta($post_id, 'sitio_cero_direccion_documentos');
    }

    if (is_array($first_archivos)) {
        $legacy_title = isset($first_archivos['title']) ? sanitize_text_field((string) $first_archivos['title']) : '';
        $legacy_html = isset($first_archivos['html']) ? sitio_cero_sanitize_direccion_html((string) $first_archivos['html']) : '';
        $legacy_links = isset($first_archivos['links']) ? sitio_cero_sanitize_aviso_links_textarea((string) $first_archivos['links']) : '';

        if ('' !== $legacy_title) {
            update_post_meta($post_id, 'sitio_cero_direccion_archivos_titulo', $legacy_title);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_archivos_titulo');
        }

        if ('' !== $legacy_html) {
            update_post_meta($post_id, 'sitio_cero_direccion_archivos_html', $legacy_html);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_archivos_html');
        }

        if ('' !== $legacy_links) {
            update_post_meta($post_id, 'sitio_cero_direccion_archivos', $legacy_links);
        } else {
            delete_post_meta($post_id, 'sitio_cero_direccion_archivos');
        }
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_archivos_titulo');
        delete_post_meta($post_id, 'sitio_cero_direccion_archivos_html');
        delete_post_meta($post_id, 'sitio_cero_direccion_archivos');
    }

    // Remove legacy accordion meta when saving with the new documents/files model.
    delete_post_meta($post_id, 'sitio_cero_direccion_acordeon_items');

    $custom_html = '';
    if (isset($_POST['sitio_cero_direccion_custom_html'])) {
        $custom_html = sitio_cero_sanitize_direccion_html(wp_unslash($_POST['sitio_cero_direccion_custom_html']));
    }

    if ('' !== $custom_html) {
        update_post_meta($post_id, 'sitio_cero_direccion_custom_html', $custom_html);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_custom_html');
    }

    $custom_css = '';
    if (isset($_POST['sitio_cero_direccion_custom_css'])) {
        $custom_css = wp_unslash($_POST['sitio_cero_direccion_custom_css']);
        if (!current_user_can('unfiltered_html')) {
            $custom_css = sanitize_textarea_field($custom_css);
        }

        if (function_exists('sitio_cero_sanitize_tramite_custom_css')) {
            $custom_css = sitio_cero_sanitize_tramite_custom_css($custom_css);
        } else {
            $custom_css = trim((string) wp_kses((string) $custom_css, array()));
        }
    }

    if ('' !== $custom_css) {
        update_post_meta($post_id, 'sitio_cero_direccion_custom_css', $custom_css);
    } else {
        delete_post_meta($post_id, 'sitio_cero_direccion_custom_css');
    }
}
add_action('save_post_direccion_municipal', 'sitio_cero_save_direccion_municipal_meta');

function sitio_cero_get_default_direcciones_municipales_examples()
{
    return array(
        array(
            'title'      => __('Direccion de Desarrollo Comunitario', 'sitio-cero'),
            'content'    => '<p>Unidad encargada de programas sociales, apoyo territorial y articulacion con organizaciones comunitarias.</p><p>Atiende consultas de beneficios municipales, derivaciones y acompanamiento social.</p>',
            'director'   => 'Carolina Mendez Rojas',
            'profesion'  => 'Trabajadora social',
            'telefonos'  => array('+56 41 220 3101', '+56 41 220 3102'),
            'email'      => 'dideco@municipio.cl',
            'direccion'  => 'Orompello 570, Concepcion',
            'recursos_titulo'   => 'Documentos y archivos de apoyo',
            'documentos_titulo' => 'Documentos oficiales',
            'documentos_html'   => '<p>En este bloque puedes publicar documentos normativos y formularios principales.</p>',
            'archivos_titulo'   => 'Archivos complementarios',
            'archivos_html'     => '<p>Aqui puedes agregar anexos, material de apoyo y recursos embebidos.</p>',
            'documentos' => array(
                array(
                    'label' => 'Guia de programas sociales',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'pdf',
                ),
            ),
            'archivos'   => array(
                array(
                    'label' => 'Ficha de contacto DIDECO',
                    'url'   => 'https://concepcion.cl/contacto/',
                    'icon'  => 'doc',
                ),
            ),
            'custom_html' => '<h3>Canales digitales</h3><p>Tambien puedes gestionar solicitudes por formulario web y mesa de ayuda municipal.</p>',
            'custom_css'  => '{{selector}} h3{color:#0f2343;} {{selector}} p{line-height:1.65;}',
        ),
        array(
            'title'      => __('Direccion de Obras Municipales', 'sitio-cero'),
            'content'    => '<p>Area tecnica responsable de permisos de edificacion, recepciones finales y control normativo urbano.</p>',
            'director'   => 'Mauricio Riquelme Soto',
            'profesion'  => 'Arquitecto',
            'telefonos'  => array('+56 41 220 4201'),
            'email'      => 'dom@municipio.cl',
            'direccion'  => 'Caupolican 385, Concepcion',
            'documentos' => array(
                array(
                    'label' => 'Requisitos permisos DOM',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'pdf',
                ),
            ),
            'archivos'   => array(
                array(
                    'label' => 'Formulario solicitud DOM',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'doc',
                ),
            ),
            'custom_html' => '<div class="dm-note"><strong>Nota:</strong> Revisa requisitos actualizados antes de ingresar tu solicitud.</div>',
            'custom_css'  => '{{selector}} .dm-note{padding:12px 14px;border-radius:10px;background:#edf4ff;border:1px solid #c9d7ee;}',
        ),
        array(
            'title'      => __('Direccion de Transito y Transporte Publico', 'sitio-cero'),
            'content'    => '<p>Gestiona licencias de conducir, senalizacion vial, permisos y seguridad del transito comunal.</p>',
            'director'   => 'Andrea Fuentes Herrera',
            'profesion'  => 'Ingeniera en transporte',
            'telefonos'  => array('+56 41 220 5100', '+56 41 220 5103'),
            'email'      => 'transito@municipio.cl',
            'direccion'  => 'Anibal Pinto 210, Concepcion',
            'documentos' => array(
                array(
                    'label' => 'Checklist licencia de conducir',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'pdf',
                ),
            ),
            'archivos'   => array(
                array(
                    'label' => 'Formulario renovacion licencia',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'doc',
                ),
            ),
            'custom_html' => '<p><a href="#">Ver calendario de atencion</a></p>',
            'custom_css'  => '{{selector}} a{font-weight:700;text-decoration:underline;}',
        ),
        array(
            'title'      => __('Direccion de Medio Ambiente', 'sitio-cero'),
            'content'    => '<p>Coordina iniciativas de reciclaje, educacion ambiental y gestion sustentable en barrios y establecimientos.</p>',
            'director'   => 'Pablo Araya Saavedra',
            'profesion'  => 'Ingeniero ambiental',
            'telefonos'  => array('+56 41 220 6201'),
            'email'      => 'medioambiente@municipio.cl',
            'direccion'  => 'Freire 880, Concepcion',
            'documentos' => array(
                array(
                    'label' => 'Calendario reciclaje comunal',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'pdf',
                ),
            ),
            'archivos'   => array(
                array(
                    'label' => 'Material educativo ambiental',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'img',
                ),
            ),
            'custom_html' => '<p>Integra material audiovisual y recursos descargables para actividades comunitarias.</p>',
            'custom_css'  => '{{selector}} p{margin-bottom:.6rem;}',
        ),
        array(
            'title'      => __('Direccion de Seguridad Publica', 'sitio-cero'),
            'content'    => '<p>Implementa planes de prevencion situacional, coordinacion territorial y respuesta comunitaria.</p>',
            'director'   => 'Ricardo Valenzuela Cardenas',
            'profesion'  => 'Administrador publico',
            'telefonos'  => array('+56 41 220 7300'),
            'email'      => 'seguridadpublica@municipio.cl',
            'direccion'  => 'Barros Arana 1200, Concepcion',
            'documentos' => array(
                array(
                    'label' => 'Protocolo seguridad barrial',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'pdf',
                ),
            ),
            'archivos'   => array(
                array(
                    'label' => 'Formato reporte vecinal',
                    'url'   => 'https://concepcion.cl/',
                    'icon'  => 'xls',
                ),
            ),
            'custom_html' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video informativo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
            'custom_css'  => '{{selector}} iframe{max-width:100%;width:100%;border-radius:12px;}',
        ),
    );
}

function sitio_cero_seed_default_direcciones_municipales()
{
    if (!post_type_exists('direccion_municipal')) {
        return;
    }

    $seed_version = '3';
    $already_seeded_version = (string) get_option('sitio_cero_default_direcciones_seeded_version', '');
    if ($seed_version === $already_seeded_version) {
        return;
    }

    $defaults = sitio_cero_get_default_direcciones_municipales_examples();

    foreach ($defaults as $index => $item) {
        if (!is_array($item) || !isset($item['title'])) {
            continue;
        }

        $title = sanitize_text_field((string) $item['title']);
        if ('' === $title) {
            continue;
        }

        $slug = sanitize_title($title);
        $existing = get_posts(
            array(
                'post_type'      => 'direccion_municipal',
                'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
                'name'           => $slug,
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            )
        );

        if (!empty($existing)) {
            $post_id = (int) $existing[0];
        } else {
            $content = isset($item['content']) ? (string) $item['content'] : '';
            $post_id = wp_insert_post(
                array(
                    'post_type'    => 'direccion_municipal',
                    'post_status'  => 'publish',
                    'post_title'   => $title,
                    'post_name'    => $slug,
                    'post_content' => $content,
                    'menu_order'   => (int) $index + 1,
                ),
                true
            );
        }

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, '_sitio_cero_demo_direccion', '1');

        if (isset($item['director']) && is_string($item['director'])) {
            update_post_meta($post_id, 'sitio_cero_direccion_director', sanitize_text_field($item['director']));
        }
        if (isset($item['profesion']) && is_string($item['profesion'])) {
            update_post_meta($post_id, 'sitio_cero_direccion_profesion', sanitize_text_field($item['profesion']));
        }
        if (isset($item['telefonos']) && is_array($item['telefonos'])) {
            $phones = array();
            foreach ($item['telefonos'] as $phone) {
                $clean_phone = sanitize_text_field((string) $phone);
                if ('' !== $clean_phone) {
                    $phones[] = $clean_phone;
                }
            }
            if (!empty($phones)) {
                update_post_meta($post_id, 'sitio_cero_direccion_telefonos', $phones);
            }
        }
        if (isset($item['email']) && is_string($item['email'])) {
            $clean_email = sanitize_email($item['email']);
            if ('' !== $clean_email) {
                update_post_meta($post_id, 'sitio_cero_direccion_email', $clean_email);
            }
        }
        if (isset($item['direccion']) && is_string($item['direccion'])) {
            update_post_meta($post_id, 'sitio_cero_direccion_direccion', sanitize_text_field($item['direccion']));
        }

        $recursos_titulo = isset($item['recursos_titulo']) && is_string($item['recursos_titulo'])
            ? sanitize_text_field($item['recursos_titulo'])
            : __('Documentos y archivos', 'sitio-cero');
        if ('' !== $recursos_titulo) {
            update_post_meta($post_id, 'sitio_cero_direccion_recursos_titulo', $recursos_titulo);
        }

        $documentos_titulo = isset($item['documentos_titulo']) && is_string($item['documentos_titulo'])
            ? sanitize_text_field($item['documentos_titulo'])
            : __('Documentos', 'sitio-cero');
        if ('' !== $documentos_titulo) {
            update_post_meta($post_id, 'sitio_cero_direccion_documentos_titulo', $documentos_titulo);
        }

        $archivos_titulo = isset($item['archivos_titulo']) && is_string($item['archivos_titulo'])
            ? sanitize_text_field($item['archivos_titulo'])
            : __('Archivos', 'sitio-cero');
        if ('' !== $archivos_titulo) {
            update_post_meta($post_id, 'sitio_cero_direccion_archivos_titulo', $archivos_titulo);
        }

        if (isset($item['documentos_html']) && is_string($item['documentos_html'])) {
            $documentos_html = sitio_cero_sanitize_direccion_html($item['documentos_html']);
            if ('' !== $documentos_html) {
                update_post_meta($post_id, 'sitio_cero_direccion_documentos_html', $documentos_html);
            }
        }

        if (isset($item['archivos_html']) && is_string($item['archivos_html'])) {
            $archivos_html = sitio_cero_sanitize_direccion_html($item['archivos_html']);
            if ('' !== $archivos_html) {
                update_post_meta($post_id, 'sitio_cero_direccion_archivos_html', $archivos_html);
            }
        }

        if (isset($item['documentos']) && is_array($item['documentos']) && !empty($item['documentos'])) {
            $documentos_lines = array();
            foreach ($item['documentos'] as $doc_item) {
                if (!is_array($doc_item)) {
                    continue;
                }
                $label = isset($doc_item['label']) ? sanitize_text_field((string) $doc_item['label']) : '';
                $url = isset($doc_item['url']) ? esc_url_raw((string) $doc_item['url']) : '';
                $icon = isset($doc_item['icon']) ? sitio_cero_sanitize_aviso_file_icon((string) $doc_item['icon']) : '';
                if ('' === $url) {
                    continue;
                }
                $line = '' !== $label ? ($label . '|' . $url) : $url;
                if ('' !== $icon) {
                    $line .= '|' . $icon;
                }
                $documentos_lines[] = $line;
            }
            $documentos_value = sitio_cero_sanitize_aviso_links_textarea(implode("\n", $documentos_lines));
            if ('' !== $documentos_value) {
                update_post_meta($post_id, 'sitio_cero_direccion_documentos', $documentos_value);
            }
        }

        if (isset($item['archivos']) && is_array($item['archivos']) && !empty($item['archivos'])) {
            $archivos_lines = array();
            foreach ($item['archivos'] as $file_item) {
                if (!is_array($file_item)) {
                    continue;
                }
                $label = isset($file_item['label']) ? sanitize_text_field((string) $file_item['label']) : '';
                $url = isset($file_item['url']) ? esc_url_raw((string) $file_item['url']) : '';
                $icon = isset($file_item['icon']) ? sitio_cero_sanitize_aviso_file_icon((string) $file_item['icon']) : '';
                if ('' === $url) {
                    continue;
                }
                $line = '' !== $label ? ($label . '|' . $url) : $url;
                if ('' !== $icon) {
                    $line .= '|' . $icon;
                }
                $archivos_lines[] = $line;
            }
            $archivos_value = sitio_cero_sanitize_aviso_links_textarea(implode("\n", $archivos_lines));
            if ('' !== $archivos_value) {
                update_post_meta($post_id, 'sitio_cero_direccion_archivos', $archivos_value);
            }
        }

        delete_post_meta($post_id, 'sitio_cero_direccion_acordeon_items');
        if (isset($item['custom_html']) && is_string($item['custom_html'])) {
            $clean_html = sitio_cero_sanitize_direccion_html($item['custom_html']);
            if ('' !== $clean_html) {
                update_post_meta($post_id, 'sitio_cero_direccion_custom_html', $clean_html);
            }
        }
        if (isset($item['custom_css']) && is_string($item['custom_css'])) {
            $clean_css = function_exists('sitio_cero_sanitize_tramite_custom_css')
                ? sitio_cero_sanitize_tramite_custom_css($item['custom_css'])
                : trim((string) wp_kses((string) $item['custom_css'], array()));
            if ('' !== $clean_css) {
                update_post_meta($post_id, 'sitio_cero_direccion_custom_css', $clean_css);
            }
        }
    }

    update_option('sitio_cero_default_direcciones_seeded_version', $seed_version);
}
add_action('init', 'sitio_cero_seed_default_direcciones_municipales', 46);

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

function sitio_cero_register_aviso_post_type()
{
    $labels = array(
        'name'               => __('Avisos', 'sitio-cero'),
        'singular_name'      => __('Aviso', 'sitio-cero'),
        'menu_name'          => __('Avisos', 'sitio-cero'),
        'name_admin_bar'     => __('Aviso', 'sitio-cero'),
        'add_new'            => __('Agregar nuevo', 'sitio-cero'),
        'add_new_item'       => __('Agregar aviso', 'sitio-cero'),
        'new_item'           => __('Nuevo aviso', 'sitio-cero'),
        'edit_item'          => __('Editar aviso', 'sitio-cero'),
        'view_item'          => __('Ver aviso', 'sitio-cero'),
        'all_items'          => __('Todos los avisos', 'sitio-cero'),
        'search_items'       => __('Buscar avisos', 'sitio-cero'),
        'not_found'          => __('No se encontraron avisos.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay avisos en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'aviso',
        array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'show_in_admin_bar'  => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'exclude_from_search'=> false,
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'avisos'),
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-format-gallery',
            'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        )
    );
}
add_action('init', 'sitio_cero_register_aviso_post_type');

function sitio_cero_add_aviso_metaboxes()
{
    add_meta_box(
        'sitio_cero_aviso_options',
        __('Opciones del aviso', 'sitio-cero'),
        'sitio_cero_render_aviso_metabox',
        'aviso',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_aviso_metaboxes');

function sitio_cero_render_aviso_metabox($post)
{
    wp_nonce_field('sitio_cero_save_aviso_meta', 'sitio_cero_aviso_meta_nonce');

    $image_url = get_post_meta($post->ID, 'sitio_cero_aviso_image_url', true);
    if (!is_string($image_url)) {
        $image_url = '';
    }

    $parrafos = get_post_meta($post->ID, 'sitio_cero_aviso_parrafos', true);
    if (!is_string($parrafos)) {
        $parrafos = '';
    }

    $documentos = get_post_meta($post->ID, 'sitio_cero_aviso_documentos', true);
    if (!is_string($documentos)) {
        $documentos = '';
    }

    $archivos = get_post_meta($post->ID, 'sitio_cero_aviso_archivos', true);
    if (!is_string($archivos)) {
        $archivos = '';
    }

    $documentos_items = sitio_cero_parse_aviso_links_textarea($documentos);
    $archivos_items = sitio_cero_parse_aviso_links_textarea($archivos);
    $icon_options = sitio_cero_get_aviso_file_icon_options();
    ?>
    <p>
        <label for="sitio_cero_aviso_image_url"><strong><?php esc_html_e('URL de imagen externa (opcional)', 'sitio-cero'); ?></strong></label><br>
        <input
            id="sitio_cero_aviso_image_url"
            name="sitio_cero_aviso_image_url"
            type="url"
            class="widefat"
            value="<?php echo esc_attr($image_url); ?>"
            placeholder="https://..."
        >
    </p>
    <p class="description">
        <?php esc_html_e('Si no hay imagen destacada, se usara esta URL para mostrar el aviso en la portada.', 'sitio-cero'); ?>
    </p>

    <hr>

    <p>
        <label for="sitio_cero_aviso_parrafos"><strong><?php esc_html_e('Parrafos de texto (opcional)', 'sitio-cero'); ?></strong></label><br>
        <textarea
            id="sitio_cero_aviso_parrafos"
            name="sitio_cero_aviso_parrafos"
            class="widefat"
            rows="6"
            placeholder="<?php esc_attr_e('Escribe texto libre para la columna izquierda.', 'sitio-cero'); ?>"
        ><?php echo esc_textarea($parrafos); ?></textarea>
    </p>

    <p>
        <label><strong><?php esc_html_e('Documentos (opcional)', 'sitio-cero'); ?></strong></label>
        <div class="sitio-cero-aviso-files" data-target="#sitio_cero_aviso_documentos">
            <div class="sitio-cero-aviso-files__actions">
                <button type="button" class="button button-secondary sitio-cero-aviso-files__library">
                    <?php esc_html_e('Seleccionar desde biblioteca', 'sitio-cero'); ?>
                </button>
                <button type="button" class="button button-secondary sitio-cero-aviso-files__add">
                    <?php esc_html_e('Agregar item manual', 'sitio-cero'); ?>
                </button>
            </div>
            <div class="sitio-cero-aviso-files__list" data-file-list>
                <?php foreach ($documentos_items as $item) : ?>
                    <?php
                    $item_label = isset($item['label']) ? (string) $item['label'] : '';
                    $item_url = isset($item['url']) ? (string) $item['url'] : '';
                    $item_icon = isset($item['icon']) ? (string) $item['icon'] : '';
                    ?>
                    <div class="sitio-cero-aviso-files__row" data-file-row>
                        <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="<?php echo esc_attr($item_label); ?>">
                        <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="<?php echo esc_attr($item_url); ?>">
                        <select class="sitio-cero-aviso-files__select" data-key="icon">
                            <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                                <option value="<?php echo esc_attr($icon_key); ?>"<?php selected($item_icon, $icon_key); ?>><?php echo esc_html($icon_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <template class="sitio-cero-aviso-files__template">
                <div class="sitio-cero-aviso-files__row" data-file-row>
                    <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="">
                    <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="">
                    <select class="sitio-cero-aviso-files__select" data-key="icon">
                        <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                            <option value="<?php echo esc_attr($icon_key); ?>"><?php echo esc_html($icon_label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                </div>
            </template>
        </div>
        <textarea id="sitio_cero_aviso_documentos" name="sitio_cero_aviso_documentos" class="widefat" rows="4" hidden><?php echo esc_textarea($documentos); ?></textarea>
    </p>

    <p>
        <label><strong><?php esc_html_e('Archivos (opcional)', 'sitio-cero'); ?></strong></label>
        <div class="sitio-cero-aviso-files" data-target="#sitio_cero_aviso_archivos">
            <div class="sitio-cero-aviso-files__actions">
                <button type="button" class="button button-secondary sitio-cero-aviso-files__library">
                    <?php esc_html_e('Seleccionar desde biblioteca', 'sitio-cero'); ?>
                </button>
                <button type="button" class="button button-secondary sitio-cero-aviso-files__add">
                    <?php esc_html_e('Agregar item manual', 'sitio-cero'); ?>
                </button>
            </div>
            <div class="sitio-cero-aviso-files__list" data-file-list>
                <?php foreach ($archivos_items as $item) : ?>
                    <?php
                    $item_label = isset($item['label']) ? (string) $item['label'] : '';
                    $item_url = isset($item['url']) ? (string) $item['url'] : '';
                    $item_icon = isset($item['icon']) ? (string) $item['icon'] : '';
                    ?>
                    <div class="sitio-cero-aviso-files__row" data-file-row>
                        <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="<?php echo esc_attr($item_label); ?>">
                        <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="<?php echo esc_attr($item_url); ?>">
                        <select class="sitio-cero-aviso-files__select" data-key="icon">
                            <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                                <option value="<?php echo esc_attr($icon_key); ?>"<?php selected($item_icon, $icon_key); ?>><?php echo esc_html($icon_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <template class="sitio-cero-aviso-files__template">
                <div class="sitio-cero-aviso-files__row" data-file-row>
                    <input type="text" class="widefat sitio-cero-aviso-files__input" data-key="label" placeholder="<?php esc_attr_e('Nombre', 'sitio-cero'); ?>" value="">
                    <input type="url" class="widefat sitio-cero-aviso-files__input" data-key="url" placeholder="https://..." value="">
                    <select class="sitio-cero-aviso-files__select" data-key="icon">
                        <?php foreach ($icon_options as $icon_key => $icon_label) : ?>
                            <option value="<?php echo esc_attr($icon_key); ?>"><?php echo esc_html($icon_label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="button-link-delete sitio-cero-aviso-files__remove"><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                </div>
            </template>
        </div>
        <textarea id="sitio_cero_aviso_archivos" name="sitio_cero_aviso_archivos" class="widefat" rows="4" hidden><?php echo esc_textarea($archivos); ?></textarea>
    </p>
    <?php
}

function sitio_cero_get_aviso_file_icon_options()
{
    return array(
        ''     => __('Icono automatico', 'sitio-cero'),
        'doc'  => __('DOC', 'sitio-cero'),
        'pdf'  => __('PDF', 'sitio-cero'),
        'img'  => __('IMG', 'sitio-cero'),
        'xls'  => __('XLS', 'sitio-cero'),
        'file' => __('Archivo', 'sitio-cero'),
    );
}

function sitio_cero_sanitize_aviso_file_icon($value)
{
    $value = strtolower(trim((string) $value));
    $options = sitio_cero_get_aviso_file_icon_options();
    return isset($options[$value]) ? $value : '';
}

function sitio_cero_detect_aviso_file_icon_by_url($url)
{
    $url = is_string($url) ? trim($url) : '';
    if ('' === $url) {
        return 'file';
    }

    $path = wp_parse_url($url, PHP_URL_PATH);
    $extension = is_string($path) ? strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) : '';

    if (in_array($extension, array('pdf'), true)) {
        return 'pdf';
    }

    if (in_array($extension, array('doc', 'docx', 'odt', 'rtf', 'txt'), true)) {
        return 'doc';
    }

    if (in_array($extension, array('xls', 'xlsx', 'csv', 'ods'), true)) {
        return 'xls';
    }

    if (in_array($extension, array('jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'heic', 'bmp', 'tif', 'tiff'), true)) {
        return 'img';
    }

    return 'file';
}

function sitio_cero_get_aviso_file_icon_symbol($icon_key)
{
    $icon_key = sitio_cero_sanitize_aviso_file_icon($icon_key);
    if ('' === $icon_key) {
        $icon_key = 'file';
    }

    $map = array(
        'doc'  => 'description',
        'pdf'  => 'picture_as_pdf',
        'img'  => 'image',
        'xls'  => 'table_view',
        'file' => 'attach_file',
    );

    return isset($map[$icon_key]) ? $map[$icon_key] : 'attach_file';
}

function sitio_cero_sanitize_aviso_links_textarea($value)
{
    $value = is_string($value) ? $value : '';
    if ('' === trim($value)) {
        return '';
    }

    $lines = preg_split('/\r\n|\r|\n/', $value);
    if (!is_array($lines)) {
        return '';
    }

    $normalized = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ('' === $line) {
            continue;
        }

        $parts = explode('|', $line, 3);
        $label = '';
        $url = $line;
        $icon = '';

        if (2 === count($parts)) {
            $first = trim((string) $parts[0]);
            $second = trim((string) $parts[1]);
            $second_icon = sitio_cero_sanitize_aviso_file_icon($second);

            if ('' !== esc_url_raw($first) && '' !== $second_icon && '' === esc_url_raw($second)) {
                // Legacy shorthand when label is empty: url|icon
                $url = $first;
                $icon = $second_icon;
            } else {
                $label = sanitize_text_field($first);
                $url = $second;
            }
        } elseif (3 <= count($parts)) {
            $label = sanitize_text_field(trim((string) $parts[0]));
            $url = trim((string) $parts[1]);
            $icon = sitio_cero_sanitize_aviso_file_icon((string) $parts[2]);
        }

        $url = esc_url_raw($url);
        if ('' === $url) {
            continue;
        }

        if ('' === $label) {
            $normalized_line = $url;
        } else {
            $normalized_line = $label . '|' . $url;
        }

        if ('' !== $icon) {
            $normalized_line .= '|' . $icon;
        }

        $normalized[] = $normalized_line;
    }

    return implode("\n", $normalized);
}

function sitio_cero_parse_aviso_links_textarea($value)
{
    $value = is_string($value) ? $value : '';
    if ('' === trim($value)) {
        return array();
    }

    $lines = preg_split('/\r\n|\r|\n/', $value);
    if (!is_array($lines)) {
        return array();
    }

    $items = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ('' === $line) {
            continue;
        }

        $parts = explode('|', $line, 3);
        $label = '';
        $url = $line;
        $icon = '';

        if (2 === count($parts)) {
            $first = trim((string) $parts[0]);
            $second = trim((string) $parts[1]);
            $second_icon = sitio_cero_sanitize_aviso_file_icon($second);

            if ('' !== esc_url_raw($first) && '' !== $second_icon && '' === esc_url_raw($second)) {
                // Legacy shorthand when label is empty: url|icon
                $url = $first;
                $icon = $second_icon;
            } else {
                $label = sanitize_text_field($first);
                $url = $second;
            }
        } elseif (3 <= count($parts)) {
            $label = sanitize_text_field(trim((string) $parts[0]));
            $url = trim((string) $parts[1]);
            $icon = sitio_cero_sanitize_aviso_file_icon((string) $parts[2]);
        }

        $url = esc_url_raw($url);
        if ('' === $url) {
            continue;
        }

        if ('' === $label) {
            $path = wp_parse_url($url, PHP_URL_PATH);
            if (is_string($path) && '' !== trim($path)) {
                $basename = basename($path);
                if (is_string($basename) && '' !== trim($basename)) {
                    $label = sanitize_text_field(urldecode($basename));
                }
            }
        }

        if ('' === $label) {
            $label = __('Descargar archivo', 'sitio-cero');
        }

        if ('' === $icon) {
            $icon = sitio_cero_detect_aviso_file_icon_by_url($url);
        }

        $items[] = array(
            'label' => $label,
            'url'   => $url,
            'icon'  => $icon,
        );
    }

    return $items;
}

function sitio_cero_get_aviso_links($post_id, $meta_key)
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return array();
    }

    $raw_value = get_post_meta($post_id, $meta_key, true);
    return sitio_cero_parse_aviso_links_textarea(is_string($raw_value) ? $raw_value : '');
}

function sitio_cero_save_aviso_meta($post_id)
{
    if (!isset($_POST['sitio_cero_aviso_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_aviso_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_aviso_meta')) {
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

    $image_url = '';
    if (isset($_POST['sitio_cero_aviso_image_url'])) {
        $image_url = esc_url_raw(wp_unslash($_POST['sitio_cero_aviso_image_url']));
    }

    if ('' !== $image_url) {
        update_post_meta($post_id, 'sitio_cero_aviso_image_url', $image_url);
    } else {
        delete_post_meta($post_id, 'sitio_cero_aviso_image_url');
    }

    $parrafos = '';
    if (isset($_POST['sitio_cero_aviso_parrafos'])) {
        $parrafos = wp_unslash($_POST['sitio_cero_aviso_parrafos']);
        if (current_user_can('unfiltered_html')) {
            $parrafos = preg_replace('/<\/?script[^>]*>/i', '', (string) $parrafos);
            $parrafos = preg_replace('/\s+on[a-z]+\s*=\s*([\'"]).*?\1/i', '', (string) $parrafos);
            $parrafos = trim((string) $parrafos);
        } else {
            $parrafos = sanitize_textarea_field((string) $parrafos);
        }
    }

    if ('' !== $parrafos) {
        update_post_meta($post_id, 'sitio_cero_aviso_parrafos', $parrafos);
    } else {
        delete_post_meta($post_id, 'sitio_cero_aviso_parrafos');
    }

    $documentos = '';
    if (isset($_POST['sitio_cero_aviso_documentos'])) {
        $documentos = sitio_cero_sanitize_aviso_links_textarea(wp_unslash($_POST['sitio_cero_aviso_documentos']));
    }

    if ('' !== $documentos) {
        update_post_meta($post_id, 'sitio_cero_aviso_documentos', $documentos);
    } else {
        delete_post_meta($post_id, 'sitio_cero_aviso_documentos');
    }

    $archivos = '';
    if (isset($_POST['sitio_cero_aviso_archivos'])) {
        $archivos = sitio_cero_sanitize_aviso_links_textarea(wp_unslash($_POST['sitio_cero_aviso_archivos']));
    }

    if ('' !== $archivos) {
        update_post_meta($post_id, 'sitio_cero_aviso_archivos', $archivos);
    } else {
        delete_post_meta($post_id, 'sitio_cero_aviso_archivos');
    }
}
add_action('save_post_aviso', 'sitio_cero_save_aviso_meta');

function sitio_cero_get_aviso_image_url($post_id, $size = 'large')
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return '';
    }

    if (has_post_thumbnail($post_id)) {
        $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        if (is_array($image_src) && !empty($image_src[0])) {
            return (string) $image_src[0];
        }
    }

    $image_url = get_post_meta($post_id, 'sitio_cero_aviso_image_url', true);
    if (!is_string($image_url)) {
        return '';
    }

    return esc_url_raw($image_url);
}

function sitio_cero_get_default_aviso_examples()
{
    return array(
        array(
            'title'     => __('Aviso: mantenimiento nocturno en avenida principal', 'sitio-cero'),
            'excerpt'   => __('Habra ajustes operativos entre las 22:00 y 05:00 hrs durante esta semana.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-01/1400/760',
        ),
        array(
            'title'     => __('Aviso: operativo barrial de retiro de enseres', 'sitio-cero'),
            'excerpt'   => __('Revisa puntos habilitados y horarios para retiro de residuos voluminosos.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-02/1400/760',
        ),
        array(
            'title'     => __('Aviso: campaña comunal de reciclaje puerta a puerta', 'sitio-cero'),
            'excerpt'   => __('Nueva ruta semanal para plastico, carton y vidrio en sectores priorizados.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-03/1400/760',
        ),
        array(
            'title'     => __('Aviso: agenda de servicios municipales en terreno', 'sitio-cero'),
            'excerpt'   => __('Atencion ciudadana y orientacion de tramites en distintos barrios.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-04/1400/760',
        ),
        array(
            'title'     => __('Aviso: trabajos de conservacion en plazas y areas verdes', 'sitio-cero'),
            'excerpt'   => __('Intervenciones programadas para mejorar espacios de recreacion comunitaria.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-05/1400/760',
        ),
        array(
            'title'     => __('Aviso: jornada especial de salud preventiva comunal', 'sitio-cero'),
            'excerpt'   => __('Controles y orientacion gratuita en puntos territoriales definidos.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-06/1400/760',
        ),
        array(
            'title'     => __('Aviso: actualizacion de rutas de transporte local', 'sitio-cero'),
            'excerpt'   => __('Cambios temporales por mejoras viales en tramos estrategicos.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-07/1400/760',
        ),
        array(
            'title'     => __('Aviso: convocatoria cultural abierta para organizaciones', 'sitio-cero'),
            'excerpt'   => __('Postula actividades para la cartelera comunal del proximo mes.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-08/1400/760',
        ),
        array(
            'title'     => __('Aviso: plan de seguridad vial en entornos escolares', 'sitio-cero'),
            'excerpt'   => __('Refuerzo de cruces, senaletica y apoyo preventivo en horarios punta.', 'sitio-cero'),
            'image_url' => 'https://picsum.photos/seed/concepcion-aviso-09/1400/760',
        ),
    );
}

function sitio_cero_seed_default_avisos()
{
    if (!post_type_exists('aviso')) {
        return;
    }

    $seed_version = '2';
    $already_seeded_version = (string) get_option('sitio_cero_default_avisos_seeded_version', '');
    if ($seed_version === $already_seeded_version) {
        return;
    }

    $items = sitio_cero_get_default_aviso_examples();

    $existing_demo_posts = get_posts(
        array(
            'post_type'      => 'aviso',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'ASC',
            ),
            'meta_key'       => '_sitio_cero_demo_aviso',
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
            $post_id = wp_insert_post(
                array(
                    'post_type'    => 'aviso',
                    'post_status'  => 'publish',
                    'post_title'   => $item['title'],
                    'post_excerpt' => $item['excerpt'],
                    'menu_order'   => $index + 1,
                ),
                true
            );
        } else {
            wp_update_post(
                array(
                    'ID'           => $post_id,
                    'post_status'  => 'publish',
                    'post_excerpt' => $item['excerpt'],
                    'menu_order'   => $index + 1,
                )
            );
        }

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        $keep_ids[] = (int) $post_id;
        update_post_meta($post_id, '_sitio_cero_demo_aviso', '1');

        if (isset($item['image_url']) && is_string($item['image_url'])) {
            $image_url = esc_url_raw($item['image_url']);
            if ('' !== $image_url) {
                update_post_meta($post_id, 'sitio_cero_aviso_image_url', $image_url);
            }
        }
    }

    foreach ($existing_demo_posts as $candidate_post) {
        $candidate_id = (int) $candidate_post->ID;
        if (!in_array($candidate_id, $keep_ids, true)) {
            wp_trash_post($candidate_id);
        }
    }

    update_option('sitio_cero_default_avisos_seeded_version', $seed_version);
    update_option('sitio_cero_default_avisos_seeded', '1');
}
add_action('init', 'sitio_cero_seed_default_avisos', 34);

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
            'menu_position'      => 23,
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
    update_post_meta($post_id, 'sitio_cero_hero_cta_url', '#avisos');

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

function sitio_cero_register_acordeon_embed_post_type()
{
    $labels = array(
        'name'               => __('Acordeones', 'sitio-cero'),
        'singular_name'      => __('Acordeon', 'sitio-cero'),
        'menu_name'          => __('Acordeones', 'sitio-cero'),
        'name_admin_bar'     => __('Acordeon', 'sitio-cero'),
        'add_new'            => __('Agregar nuevo', 'sitio-cero'),
        'add_new_item'       => __('Agregar acordeon', 'sitio-cero'),
        'new_item'           => __('Nuevo acordeon', 'sitio-cero'),
        'edit_item'          => __('Editar acordeon', 'sitio-cero'),
        'view_item'          => __('Ver acordeon', 'sitio-cero'),
        'all_items'          => __('Todos los acordeones', 'sitio-cero'),
        'search_items'       => __('Buscar acordeones', 'sitio-cero'),
        'not_found'          => __('No se encontraron acordeones.', 'sitio-cero'),
        'not_found_in_trash' => __('No hay acordeones en la papelera.', 'sitio-cero'),
    );

    register_post_type(
        'acordeon_embed',
        array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_admin_bar'  => true,
            'show_in_rest'       => true,
            'publicly_queryable' => false,
            'exclude_from_search'=> true,
            'has_archive'        => false,
            'menu_position'      => 24,
            'menu_icon'          => 'dashicons-editor-ol',
            'supports'           => array('title', 'revisions'),
        )
    );
}
add_action('init', 'sitio_cero_register_acordeon_embed_post_type');

function sitio_cero_enqueue_acordeon_embed_admin_assets($hook_suffix)
{
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'acordeon_embed' !== $screen->post_type) {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'sitio-cero-admin-acordeon-embed',
        get_template_directory_uri() . '/assets/css/admin-acordeon-embed.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'sitio-cero-admin-acordeon-embed',
        get_template_directory_uri() . '/assets/js/admin-acordeon-embed.js',
        array('jquery'),
        $version,
        true
    );
}
add_action('admin_enqueue_scripts', 'sitio_cero_enqueue_acordeon_embed_admin_assets');

function sitio_cero_get_acordeon_embed_items($post_id)
{
    $post_id = absint($post_id);
    if ($post_id <= 0) {
        return array();
    }

    $raw_items = get_post_meta($post_id, 'sitio_cero_acordeon_embed_items', true);
    if (!is_array($raw_items)) {
        return array();
    }

    $items = array();
    foreach ($raw_items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
        $content = isset($item['content']) ? wp_kses_post((string) $item['content']) : '';
        if ('' === $title && '' === trim((string) wp_strip_all_tags($content))) {
            continue;
        }

        $items[] = array(
            'title'   => $title,
            'content' => $content,
        );
    }

    return $items;
}

function sitio_cero_add_acordeon_embed_metaboxes()
{
    add_meta_box(
        'sitio_cero_acordeon_embed_items',
        __('Items del acordeon', 'sitio-cero'),
        'sitio_cero_render_acordeon_embed_metabox',
        'acordeon_embed',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sitio_cero_add_acordeon_embed_metaboxes');

function sitio_cero_render_acordeon_embed_metabox($post)
{
    wp_nonce_field('sitio_cero_save_acordeon_embed_meta', 'sitio_cero_acordeon_embed_meta_nonce');

    $items = sitio_cero_get_acordeon_embed_items($post->ID);
    if (empty($items)) {
        $items = array(
            array(
                'title'   => __('Informacion general', 'sitio-cero'),
                'content' => '<p>' . __('Aqui puedes agregar informacion para este item del acordeon.', 'sitio-cero') . '</p>',
            ),
        );
    }
    ?>
    <div class="sitio-cero-acordeon-embed-admin" data-acordeon-admin-root>
        <p class="description">
            <?php esc_html_e('Inserta este acordeon donde quieras usando el shortcode:', 'sitio-cero'); ?>
            <code>[acordeon id="<?php echo esc_html((string) $post->ID); ?>"]</code>
        </p>

        <div class="sitio-cero-acordeon-embed-admin__list" data-acordeon-admin-list>
            <?php foreach ($items as $index => $item) : ?>
                <?php
                $item_title = isset($item['title']) ? sanitize_text_field((string) $item['title']) : '';
                $item_content = isset($item['content']) ? (string) $item['content'] : '';
                ?>
                <div class="sitio-cero-acordeon-embed-admin__row" data-acordeon-admin-row>
                    <div class="sitio-cero-acordeon-embed-admin__row-head">
                        <strong><?php echo esc_html(sprintf(__('Item %d', 'sitio-cero'), $index + 1)); ?></strong>
                        <button type="button" class="button-link-delete" data-acordeon-admin-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                    </div>
                    <p>
                        <label><strong><?php esc_html_e('Titulo', 'sitio-cero'); ?></strong></label>
                        <input type="text" class="widefat" name="sitio_cero_acordeon_embed_item_title[]" value="<?php echo esc_attr($item_title); ?>" placeholder="<?php esc_attr_e('Ejemplo: Requisitos', 'sitio-cero'); ?>">
                    </p>
                    <p>
                        <label><strong><?php esc_html_e('Contenido (HTML permitido)', 'sitio-cero'); ?></strong></label>
                        <textarea class="widefat" rows="5" name="sitio_cero_acordeon_embed_item_content[]" placeholder="<?php esc_attr_e('Texto del item...', 'sitio-cero'); ?>"><?php echo esc_textarea($item_content); ?></textarea>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" class="button button-primary" data-acordeon-admin-add><?php esc_html_e('Agregar item', 'sitio-cero'); ?></button>

        <template data-acordeon-admin-template>
            <div class="sitio-cero-acordeon-embed-admin__row" data-acordeon-admin-row>
                <div class="sitio-cero-acordeon-embed-admin__row-head">
                    <strong><?php esc_html_e('Item', 'sitio-cero'); ?></strong>
                    <button type="button" class="button-link-delete" data-acordeon-admin-remove><?php esc_html_e('Quitar', 'sitio-cero'); ?></button>
                </div>
                <p>
                    <label><strong><?php esc_html_e('Titulo', 'sitio-cero'); ?></strong></label>
                    <input type="text" class="widefat" name="sitio_cero_acordeon_embed_item_title[]" value="" placeholder="<?php esc_attr_e('Ejemplo: Requisitos', 'sitio-cero'); ?>">
                </p>
                <p>
                    <label><strong><?php esc_html_e('Contenido (HTML permitido)', 'sitio-cero'); ?></strong></label>
                    <textarea class="widefat" rows="5" name="sitio_cero_acordeon_embed_item_content[]" placeholder="<?php esc_attr_e('Texto del item...', 'sitio-cero'); ?>"></textarea>
                </p>
            </div>
        </template>
    </div>
    <?php
}

function sitio_cero_save_acordeon_embed_meta($post_id)
{
    if (!isset($_POST['sitio_cero_acordeon_embed_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['sitio_cero_acordeon_embed_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'sitio_cero_save_acordeon_embed_meta')) {
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

    $titles = isset($_POST['sitio_cero_acordeon_embed_item_title']) && is_array($_POST['sitio_cero_acordeon_embed_item_title'])
        ? wp_unslash($_POST['sitio_cero_acordeon_embed_item_title'])
        : array();
    $contents = isset($_POST['sitio_cero_acordeon_embed_item_content']) && is_array($_POST['sitio_cero_acordeon_embed_item_content'])
        ? wp_unslash($_POST['sitio_cero_acordeon_embed_item_content'])
        : array();

    $total = max(count($titles), count($contents));
    $items = array();

    for ($index = 0; $index < $total; $index++) {
        $title = isset($titles[$index]) ? sanitize_text_field((string) $titles[$index]) : '';
        $content = isset($contents[$index]) ? wp_kses_post((string) $contents[$index]) : '';

        if ('' === $title && '' === trim((string) wp_strip_all_tags($content))) {
            continue;
        }

        $items[] = array(
            'title'   => $title,
            'content' => $content,
        );
    }

    if (!empty($items)) {
        update_post_meta($post_id, 'sitio_cero_acordeon_embed_items', $items);
    } else {
        delete_post_meta($post_id, 'sitio_cero_acordeon_embed_items');
    }
}
add_action('save_post_acordeon_embed', 'sitio_cero_save_acordeon_embed_meta');

function sitio_cero_shortcode_acordeon($atts = array())
{
    $atts = shortcode_atts(
        array(
            'id' => 0,
        ),
        $atts,
        'acordeon'
    );

    $post_id = absint($atts['id']);
    if ($post_id <= 0) {
        return '';
    }

    $post = get_post($post_id);
    if (!$post instanceof WP_Post || 'acordeon_embed' !== $post->post_type) {
        return '';
    }

    if ('publish' !== get_post_status($post_id) && !current_user_can('read_post', $post_id)) {
        return '';
    }

    $items = sitio_cero_get_acordeon_embed_items($post_id);
    if (empty($items)) {
        return '';
    }

    $uid = wp_unique_id('sc-accordion-');

    ob_start();
    ?>
    <div class="sc-accordion-container" data-sc-accordion id="<?php echo esc_attr($uid); ?>">
        <?php foreach ($items as $index => $item) : ?>
            <?php
            $item_title = isset($item['title']) ? (string) $item['title'] : '';
            $item_content = isset($item['content']) ? (string) $item['content'] : '';
            $content_id = $uid . '-content-' . $index;
            ?>
            <div class="sc-accordion-item">
                <button class="sc-accordion-header" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr($content_id); ?>">
                    <span><?php echo esc_html($item_title); ?></span>
                    <span class="sc-accordion-icon" aria-hidden="true">+</span>
                </button>
                <div class="sc-accordion-content" id="<?php echo esc_attr($content_id); ?>" hidden>
                    <div class="sc-accordion-body">
                        <?php echo wpautop(wp_kses_post($item_content)); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php

    return (string) ob_get_clean();
}
add_shortcode('acordeon', 'sitio_cero_shortcode_acordeon');

function sitio_cero_get_default_acordeon_embed_items()
{
    return array(
        array('title' => __('Informacion general', 'sitio-cero'), 'content' => '<p>Resumen inicial del servicio y su alcance para la comunidad.</p>'),
        array('title' => __('Requisitos', 'sitio-cero'), 'content' => '<ul><li>Documento de identidad vigente</li><li>Comprobante de domicilio</li><li>Formulario completo</li></ul>'),
        array('title' => __('Documentos necesarios', 'sitio-cero'), 'content' => '<p>Adjunta archivos en formato PDF, DOC o imagen cuando corresponda.</p>'),
        array('title' => __('Horarios de atencion', 'sitio-cero'), 'content' => '<p>Lunes a viernes de 08:30 a 14:00 hrs.</p>'),
        array('title' => __('Canales de atencion', 'sitio-cero'), 'content' => '<p>Presencial, telefono y formulario web municipal.</p>'),
        array('title' => __('Plazos estimados', 'sitio-cero'), 'content' => '<p>El tiempo de respuesta puede variar entre 5 y 15 dias habiles.</p>'),
        array('title' => __('Costos y pagos', 'sitio-cero'), 'content' => '<p>Indica aqui si el tramite es gratuito o si requiere pago de derechos.</p>'),
        array('title' => __('Preguntas frecuentes', 'sitio-cero'), 'content' => '<p>Incluye respuestas breves a dudas recurrentes de los vecinos.</p>'),
        array('title' => __('Normativa aplicable', 'sitio-cero'), 'content' => '<p>Referencia leyes, ordenanzas o reglamentos que respaldan el proceso.</p>'),
        array('title' => __('Contacto', 'sitio-cero'), 'content' => '<p>Email: contacto@municipio.cl<br>Telefono: +56 41 220 0000</p>'),
    );
}

function sitio_cero_seed_default_acordeon_embed()
{
    if (!post_type_exists('acordeon_embed')) {
        return;
    }

    $seed_version = '1';
    $already_seeded_version = (string) get_option('sitio_cero_default_acordeon_embed_seeded_version', '');
    if ($seed_version === $already_seeded_version) {
        return;
    }

    $title = __('Acordeon de ejemplo (10 items)', 'sitio-cero');
    $slug = sanitize_title($title);

    $existing = get_posts(
        array(
            'post_type'      => 'acordeon_embed',
            'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
            'name'           => $slug,
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if (!empty($existing)) {
        $post_id = (int) $existing[0];
        wp_update_post(
            array(
                'ID'          => $post_id,
                'post_title'  => $title,
                'post_status' => 'publish',
            )
        );
    } else {
        $post_id = wp_insert_post(
            array(
                'post_type'   => 'acordeon_embed',
                'post_status' => 'publish',
                'post_title'  => $title,
                'post_name'   => $slug,
            ),
            true
        );
    }

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    $items = sitio_cero_get_default_acordeon_embed_items();
    update_post_meta((int) $post_id, 'sitio_cero_acordeon_embed_items', $items);
    update_post_meta((int) $post_id, '_sitio_cero_demo_acordeon_embed', '1');

    update_option('sitio_cero_default_acordeon_embed_seeded_version', $seed_version);
}
add_action('init', 'sitio_cero_seed_default_acordeon_embed', 56);
