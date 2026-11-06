<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if ( is_front_page() ) : ?>
  <meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
  <?php endif; ?>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="intro" aria-hidden="true"><img src="<?php echo esc_url( osus_logo_url() ); ?>" alt=""></div>
<main>
  <header class="site-header">
    <a class="logo-link" href="<?php echo esc_url( is_front_page() ? '#home' : home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' ' . __( 'home', 'osus' ) ); ?>"><img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( osus_logo_url() ); ?>"></a>
    <nav class="nav-links" id="nav-links" aria-label="<?php esc_attr_e( 'Main navigation', 'osus' ); ?>">
      <?php osus_nav_links(); ?>
    </nav>
    <a class="header-cta" href="<?php echo esc_url( osus_section_url( 'enquiry' ) ); ?>" aria-label="<?php esc_attr_e( 'Book a consultation', 'osus' ); ?>"><span class="cta-long"><?php esc_html_e( 'Book a consultation', 'osus' ); ?></span><span class="cta-short" aria-hidden="true"><?php esc_html_e( 'Book now', 'osus' ); ?></span></a>
    <button class="menu-button" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'osus' ); ?>" aria-expanded="false" aria-controls="nav-links"><?php esc_html_e( 'Menu', 'osus' ); ?></button>
  </header>

  <div class="page-content">
