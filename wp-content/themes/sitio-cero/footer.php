<?php
$footer_columns = function_exists('sitio_cero_get_footer_columns')
    ? sitio_cero_get_footer_columns(4)
    : array();
?>

<footer class="site-footer">
    <div class="container site-footer__grid">
        <?php foreach ($footer_columns as $column) : ?>
            <?php
            if (!is_array($column)) {
                continue;
            }

            $title = isset($column['title']) ? sanitize_text_field((string) $column['title']) : '';
            $content = isset($column['content']) ? (string) $column['content'] : '';
            ?>
            <section class="footer-col">
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <div class="footer-col__content">
                    <?php echo $content; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <div class="site-footer__bar">
        <div class="container site-footer__bar-inner">
            <p>
                <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>.
                <?php esc_html_e('Portal institucional.', 'sitio-cero'); ?>
            </p>
            <a href="#content">Volver arriba</a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
