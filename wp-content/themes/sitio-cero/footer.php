<footer class="site-footer">
    <div class="container site-footer__grid">
        <section class="footer-col">
            <h3>La municipalidad</h3>
            <ul>
                <li><a href="#">Alcaldia</a></li>
                <li><a href="#">Concejo municipal</a></li>
                <li><a href="#">Direcciones</a></li>
                <li><a href="#">Cuenta publica</a></li>
            </ul>
        </section>

        <section class="footer-col">
            <h3>Servicios</h3>
            <ul>
                <li><a href="#tramites">Tramites en linea</a></li>
                <li><a href="#">Patentes</a></li>
                <li><a href="#">Permisos de circulacion</a></li>
                <li><a href="#">Pagos municipales</a></li>
            </ul>
        </section>

        <section class="footer-col">
            <h3>Transparencia</h3>
            <ul>
                <li><a href="#">Ley de transparencia</a></li>
                <li><a href="#">Compras publicas</a></li>
                <li><a href="#">Datos abiertos</a></li>
                <li><a href="#">Solicitudes de informacion</a></li>
            </ul>
        </section>

        <section class="footer-col">
            <h3>Contacto</h3>
            <ul>
                <li>Av. Principal 100, Santiago</li>
                <li>+56 2 3386 8000</li>
                <li>contacto@municipio.cl</li>
                <li>Lun a Vie: 08:30 - 14:00</li>
            </ul>
        </section>
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
