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
<?php wp_footer(); ?>
</body>
</html>
