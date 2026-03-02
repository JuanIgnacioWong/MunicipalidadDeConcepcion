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
                    'depth'          => 2,
                    'fallback_cb'    => 'sitio_cero_menu_fallback',
                )
            );
            ?>
        </nav>

        <div
            class="site-header-edge-tools"
            data-header-search
            data-search-endpoint="<?php echo esc_url(rest_url('wp/v2/search')); ?>"
            data-search-types="post,page,noticia,aviso,direccion_municipal,evento_municipal"
        >
            <div class="site-header-search-shell" data-header-search-shell>
                <button
                    class="site-header-search-btn"
                    type="button"
                    aria-expanded="false"
                    aria-controls="site-header-search-panel"
                    aria-label="<?php esc_attr_e('Abrir buscador', 'sitio-cero'); ?>"
                    data-header-search-trigger
                >
                    <span class="material-symbols-rounded site-header-search-btn__icon" aria-hidden="true">search</span>
                    <span class="site-header-search-btn__label"><?php esc_html_e('Buscar', 'sitio-cero'); ?></span>
                </button>

                <div class="site-header-search-panel" id="site-header-search-panel" aria-hidden="true" data-header-search-panel>
                    <form class="site-header-search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get" data-header-search-form>
                        <label class="site-header-search-form__label" for="site-header-search-input"><?php esc_html_e('Buscar en el sitio', 'sitio-cero'); ?></label>
                        <div class="site-header-search-form__row">
                            <input
                                id="site-header-search-input"
                                class="site-header-search-form__input"
                                type="search"
                                name="s"
                                autocomplete="off"
                                placeholder="<?php esc_attr_e('Buscar noticias, direcciones, avisos...', 'sitio-cero'); ?>"
                                data-header-search-input
                            >
                            <input type="hidden" name="mostrar_google" value="1">
                            <button class="site-header-search-form__submit" type="submit"><?php esc_html_e('Buscar', 'sitio-cero'); ?></button>
                        </div>
                    </form>

                    <div class="site-header-search-suggest">
                        <p class="site-header-search-suggest__title"><?php esc_html_e('Busquedas recomendadas', 'sitio-cero'); ?></p>
                        <ul class="site-header-search-suggest__list" data-header-search-suggestions></ul>
                    </div>
                </div>
            </div>
            <a class="site-header-edge-btn" href="tel:*4110">
                <span class="site-header-edge-btn__phone">*4110</span>
                <span class="site-header-edge-btn__text"><?php esc_html_e('Emergencias', 'sitio-cero'); ?></span>
            </a>
        </div>
    </div>
</header>
