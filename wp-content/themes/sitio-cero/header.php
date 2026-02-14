<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e('Saltar al contenido', 'sitio-cero'); ?></a>

<header class="site-topbar">
    <div class="container topbar__inner">
        <p class="topbar__item"><strong>Fono central:</strong> +56 2 3386 8000</p>
        <p class="topbar__item"><strong>Emergencias:</strong> 1414</p>
        <a class="topbar__link" href="<?php echo esc_url(home_url('/#canales')); ?>">Canales de atencion</a>
    </div>
</header>

<div class="site-brandbar">
    <div class="container site-brandbar__inner">
        <?php $brand_logo = sitio_cero_get_brand_logo_data(); ?>
        <a class="brand-logo-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Inicio', 'sitio-cero'); ?>">
            <img
                class="brand-logo-image"
                src="<?php echo esc_url($brand_logo['url']); ?>"
                alt="<?php esc_attr_e('Municipalidad de Concepcion', 'sitio-cero'); ?>"
                width="<?php echo esc_attr((string) $brand_logo['width']); ?>"
                height="<?php echo esc_attr((string) $brand_logo['height']); ?>"
                loading="eager"
                decoding="async"
            >
        </a>
    </div>
</div>

<header class="site-header">
    <div class="container site-header__inner">
        <button
            class="nav-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="menu-principal"
        >
            Menu
        </button>

        <nav id="menu-principal" class="site-nav" aria-label="<?php esc_attr_e('Menu principal', 'sitio-cero'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'site-nav__list',
                    'fallback_cb'    => 'sitio_cero_menu_fallback',
                )
            );
            ?>
        </nav>

        <a class="header-cta" href="<?php echo esc_url(home_url('/#tramites')); ?>">Tramites en linea</a>
    </div>
</header>
