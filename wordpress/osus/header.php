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
<main>
  <header class="site-header">
    <a class="logo-link" href="<?php echo esc_url( is_front_page() ? '#home' : home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' ' . __( 'home', 'osus' ) ); ?>"><img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( osus_logo_url() ); ?>"></a>
    <nav class="nav-links" id="nav-links" aria-label="<?php esc_attr_e( 'Main navigation', 'osus' ); ?>">
      <?php osus_nav_links(); ?>
    </nav>
    <button class="menu-button" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'osus' ); ?>" aria-expanded="false" aria-controls="nav-links"><?php esc_html_e( 'Menu', 'osus' ); ?></button>
  </header>

  <div class="page-content">
