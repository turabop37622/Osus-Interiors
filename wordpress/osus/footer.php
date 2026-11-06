<?php
$osus_email     = osus_mod( 'contact_email' );
$osus_phone     = osus_mod( 'contact_phone' );
$osus_instagram = osus_mod( 'contact_instagram' );
?>
    <footer id="contact">
      <div class="footer-top scroll-reveal">
        <p class="section-label"><?php esc_html_e( 'Start a conversation', 'osus' ); ?></p>
        <h2><?php esc_html_e( 'Have a space in mind?', 'osus' ); ?></h2>
        <?php if ( $osus_email ) : ?>
        <a href="mailto:<?php echo esc_attr( antispambot( $osus_email ) ); ?>"><?php echo esc_html( antispambot( $osus_email ) ); ?> <span>↗</span></a>
        <?php endif; ?>
        <div class="footer-links">
          <?php if ( $osus_phone ) : ?>
          <a href="<?php echo esc_url( osus_phone_href( $osus_phone ) ); ?>"><?php echo esc_html( $osus_phone ); ?></a>
          <?php endif; ?>
          <?php if ( $osus_instagram ) : ?>
          <a href="<?php echo esc_url( $osus_instagram ); ?>" target="_blank" rel="noopener">Instagram</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-bottom">
        <img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( osus_logo_url() ); ?>">
        <p><?php echo esc_html( osus_mod( 'contact_location' ) ); ?></p>
        <div><a href="<?php echo esc_url( osus_section_url( 'studio' ) ); ?>"><?php esc_html_e( 'Our studio', 'osus' ); ?></a><a href="<?php echo esc_url( osus_section_url( 'projects' ) ); ?>"><?php esc_html_e( 'Our work', 'osus' ); ?></a></div>
        <p>© <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php bloginfo( 'name' ); ?></p>
      </div>
    </footer>
  </div>
</main>
<?php $osus_whatsapp = preg_replace( '/\D/', '', (string) osus_mod( 'contact_whatsapp' ) ); ?>
<?php if ( $osus_whatsapp ) : ?>
<a class="whatsapp" href="<?php echo esc_url( 'https://wa.me/' . $osus_whatsapp ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat with OSUS Interiors on WhatsApp', 'osus' ); ?>">
  <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.14-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.3 1.27.49 1.7.63.71.23 1.36.2 1.88.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.05 21.5h-.01a9.43 9.43 0 0 1-4.8-1.31l-.35-.2-3.57.93.95-3.48-.22-.36a9.4 9.4 0 0 1-1.44-5.02c0-5.2 4.24-9.44 9.45-9.44 2.52 0 4.89.98 6.68 2.77a9.38 9.38 0 0 1 2.76 6.68c0 5.21-4.24 9.44-9.45 9.44zm8.04-17.48A11.3 11.3 0 0 0 12.05.69C5.78.69.68 5.79.68 12.06c0 2 .52 3.96 1.52 5.68L.6 23.6l6-1.57a11.34 11.34 0 0 0 5.44 1.39h.01c6.27 0 11.37-5.1 11.37-11.37 0-3.04-1.18-5.9-3.33-8.03z"/></svg>
</a>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
