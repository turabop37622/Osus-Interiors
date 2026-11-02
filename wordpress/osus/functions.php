<?php
/**
 * OSUS Interiors theme setup, assets and Customizer options.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OSUS_VERSION', '1.0.0' );

function osus_asset( $path ) {
	return get_theme_file_uri( 'assets/' . $path );
}

/**
 * Default content: matches the original design, used until changed in the Customizer.
 */
function osus_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array(
		'hero_eyebrow'     => 'Interior architecture & design',
		'hero_title'       => 'Not just spaces. A way of living.',
		'hero_text'        => 'We create soulful, enduring interiors where natural materials, quiet details and everyday life come together.',
		'hero_image'       => osus_asset( 'images/hero-dining.webp' ),
		'hero_caption'     => 'Residential interiors · Pakistan',

		'studio_heading'   => 'We believe a beautiful home should feel deeply personal, never prescribed.',
		'studio_text'      => 'OSUS Interiors is a multidisciplinary studio creating warm, timeless spaces. Our approach is intuitive and collaborative—layering texture, light and honest materials to build homes that grow more beautiful with time.',

		'projects_heading' => 'A study in warmth, restraint and detail.',

		'contact_email'    => 'info@osusinteriors.com',
		'contact_phone'    => '+92 323 9871753',
		'contact_instagram'=> 'https://www.instagram.com/osusinteriors/',
		'contact_location' => 'Lahore · Pakistan · Worldwide',
	);

	$projects = array(
		1 => array( 'project-aurelle.webp', 'The Aurelle Lounge', 'Residential · Lahore' ),
		2 => array( 'project-sage-bedroom.webp', 'Sage Suite', 'Master Bedroom · Lahore' ),
		3 => array( 'project-dining.webp', 'Gather House', 'Dining & Living · Lahore' ),
	);
	foreach ( $projects as $i => $project ) {
		$defaults[ "project_{$i}_image" ] = osus_asset( 'images/' . $project[0] );
		$defaults[ "project_{$i}_title" ] = $project[1];
		$defaults[ "project_{$i}_meta" ]  = $project[2];
	}

	$reels = array(
		1 => array( 'site-to-sanctuary', 'From Site to Sanctuary', 'Concept · Build · Interior' ),
		2 => array( 'gilded-lounge', 'The Gilded Lounge', 'Formal living · Lahore' ),
		3 => array( 'through-the-house', 'Through the House', 'Open-plan walkthrough' ),
		4 => array( 'night-day-suite', 'Night & Day Suite', 'Bedroom & bath' ),
		5 => array( 'soft-edges', 'Soft Edges', 'Living & dining' ),
		6 => array( 'glass-house', 'The Glass House', 'Workspace · Plan to reality' ),
	);
	foreach ( $reels as $i => $reel ) {
		$defaults[ "reel_{$i}_video" ]  = osus_asset( 'videos/' . $reel[0] . '.mp4' );
		$defaults[ "reel_{$i}_poster" ] = osus_asset( 'videos/' . $reel[0] . '.webp' );
		$defaults[ "reel_{$i}_title" ]  = $reel[1];
		$defaults[ "reel_{$i}_meta" ]   = $reel[2];
	}

	return $defaults;
}

function osus_mod( $key ) {
	$defaults = osus_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/* ---------- Setup ---------- */

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'osus', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 300, 'flex-height' => true, 'flex-width' => true ) );
	register_nav_menus( array( 'primary' => __( 'Main navigation', 'osus' ) ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'osus-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'osus-site', osus_asset( 'css/site.css' ), array( 'osus-fonts' ), OSUS_VERSION );
	wp_enqueue_script( 'osus-main', osus_asset( 'js/main.js' ), array(), OSUS_VERSION, true );
} );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

// Fallback favicon when no Site Icon is set in the Customizer
add_action( 'wp_head', function () {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/png" href="' . esc_url( osus_asset( 'logo/osus-logo.png' ) ) . '">' . "\n";
	}
} );

/* ---------- Template helpers ---------- */

function osus_logo_url() {
	$logo_id = get_theme_mod( 'custom_logo' );
	$url     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
	return $url ? $url : osus_asset( 'logo/osus-logo.png' );
}

/**
 * Section link that works from the front page and from inner pages.
 */
function osus_section_url( $id ) {
	return is_front_page() ? '#' . $id : home_url( '/#' . $id );
}

/**
 * Nav links as plain anchors (the design styles `.nav-links a`).
 * Uses the "Main navigation" menu when assigned, otherwise the one-page sections.
 */
function osus_nav_links() {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( $item->menu_item_parent ) {
					continue;
				}
				printf( '<a href="%s">%s</a>', esc_url( $item->url ), esc_html( $item->title ) );
			}
			return;
		}
	}

	$links = array(
		'home'     => __( 'Home', 'osus' ),
		'studio'   => __( 'Studio', 'osus' ),
		'services' => __( 'Services', 'osus' ),
		'projects' => __( 'Projects', 'osus' ),
		'contact'  => __( 'Contact', 'osus' ),
	);
	foreach ( $links as $id => $label ) {
		printf( '<a href="%s">%s</a>', esc_url( osus_section_url( $id ) ), esc_html( $label ) );
	}
}

function osus_phone_href( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/* ---------- Customizer ---------- */

add_action( 'customize_register', function ( $wp_customize ) {
	$defaults = osus_defaults();

	$add = function ( $section, $key, $label, $type = 'text' ) use ( $wp_customize, $defaults ) {
		$sanitize = array(
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea_field',
			'url'      => 'esc_url_raw',
			'email'    => 'sanitize_email',
			'image'    => 'esc_url_raw',
			'video'    => 'esc_url_raw',
		);
		$wp_customize->add_setting( $key, array(
			'default'           => $defaults[ $key ],
			'sanitize_callback' => $sanitize[ $type ],
		) );

		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, array( 'label' => $label, 'section' => $section ) ) );
		} elseif ( 'video' === $type ) {
			$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, $key, array( 'label' => $label, 'section' => $section, 'mime_type' => 'video' ) ) );
		} else {
			$wp_customize->add_control( $key, array( 'label' => $label, 'section' => $section, 'type' => $type ) );
		}
	};

	$wp_customize->add_panel( 'osus', array( 'title' => __( 'OSUS homepage', 'osus' ), 'priority' => 25 ) );

	$wp_customize->add_section( 'osus_hero', array( 'title' => __( 'Hero', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_hero', 'hero_eyebrow', __( 'Small label', 'osus' ) );
	$add( 'osus_hero', 'hero_title', __( 'Headline', 'osus' ) );
	$add( 'osus_hero', 'hero_text', __( 'Intro text', 'osus' ), 'textarea' );
	$add( 'osus_hero', 'hero_image', __( 'Hero image', 'osus' ), 'image' );
	$add( 'osus_hero', 'hero_caption', __( 'Image caption', 'osus' ) );

	$wp_customize->add_section( 'osus_studio', array( 'title' => __( 'Studio', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_studio', 'studio_heading', __( 'Heading (fills in on scroll)', 'osus' ), 'textarea' );
	$add( 'osus_studio', 'studio_text', __( 'Text', 'osus' ), 'textarea' );

	$wp_customize->add_section( 'osus_projects', array( 'title' => __( 'Selected projects', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_projects', 'projects_heading', __( 'Heading', 'osus' ) );
	for ( $i = 1; $i <= 3; $i++ ) {
		/* translators: %d: project number */
		$add( 'osus_projects', "project_{$i}_image", sprintf( __( 'Project %d image', 'osus' ), $i ), 'image' );
		$add( 'osus_projects', "project_{$i}_title", sprintf( __( 'Project %d name', 'osus' ), $i ) );
		$add( 'osus_projects', "project_{$i}_meta", sprintf( __( 'Project %d type · city', 'osus' ), $i ) );
	}

	$wp_customize->add_section( 'osus_reel', array(
		'title'       => __( 'Video carousel', 'osus' ),
		'panel'       => 'osus',
		'description' => __( 'Upload short, muted vertical (9:16) MP4 clips. Leave a video empty to hide that slide.', 'osus' ),
	) );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: video number */
		$add( 'osus_reel', "reel_{$i}_video", sprintf( __( 'Video %d (MP4)', 'osus' ), $i ), 'video' );
		$add( 'osus_reel', "reel_{$i}_poster", sprintf( __( 'Video %d cover image', 'osus' ), $i ), 'image' );
		$add( 'osus_reel', "reel_{$i}_title", sprintf( __( 'Video %d title', 'osus' ), $i ) );
		$add( 'osus_reel', "reel_{$i}_meta", sprintf( __( 'Video %d subtitle', 'osus' ), $i ) );
	}

	$wp_customize->add_section( 'osus_contact', array( 'title' => __( 'Contact details', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_contact', 'contact_email', __( 'Email', 'osus' ), 'email' );
	$add( 'osus_contact', 'contact_phone', __( 'Phone', 'osus' ) );
	$add( 'osus_contact', 'contact_instagram', __( 'Instagram URL', 'osus' ), 'url' );
	$add( 'osus_contact', 'contact_location', __( 'Location line', 'osus' ) );
} );
