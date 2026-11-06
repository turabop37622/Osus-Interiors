<?php
/**
 * OSUS Interiors theme setup, assets and Customizer options.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OSUS_VERSION', '2.0.0' );

function osus_asset( $path ) {
	return get_theme_file_uri( 'assets/' . $path );
}

/**
 * Default content: matches the static site, used until changed in the Customizer.
 */
function osus_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array(
		'hero_eyebrow'      => 'Interior architecture & design',
		'hero_title'        => "Not just spaces.\nA way of living.",
		'hero_text'         => 'We create soulful, enduring interiors where natural materials, quiet details and everyday life come together.',

		'studio_heading'    => 'We believe a beautiful home should feel deeply personal, never prescribed.',
		'studio_text'       => 'OSUS Interiors is a multidisciplinary studio creating warm, timeless spaces. Our approach is intuitive and collaborative—layering texture, light and honest materials to build homes that grow more beautiful with time.',

		'projects_heading'  => 'A study in warmth, restraint and detail.',

		'web3forms_key'     => '',

		'contact_email'     => 'info@osusinteriors.com',
		'contact_phone'     => '+92 323 9871753',
		'contact_whatsapp'  => '923239871753',
		'contact_instagram' => 'https://www.instagram.com/osusinteriors/',
		'contact_location'  => 'Lahore · Pakistan · Worldwide',
	);

	foreach ( osus_hero_defaults() as $i => $slide ) {
		$defaults[ "hero_{$i}_image" ]   = osus_asset( 'images/' . $slide[0] . '.webp' );
		$defaults[ "hero_{$i}_caption" ] = $slide[2];
	}

	$projects = array(
		1 => array( 'The Aurelle', 'Lobby & lounge · Lahore', 'aurelle', 5 ),
		2 => array( 'CC-2 Apartment', 'Apartment · Phase 2, Islamabad', 'islamabad', 5 ),
		3 => array( 'Sage Suite', 'Master bedroom · Lahore', 'sage', 3 ),
		4 => array( 'The Residence', 'Living & dining · Lahore', 'residence', 4 ),
	);
	for ( $i = 1; $i <= 6; $i++ ) {
		$project = isset( $projects[ $i ] ) ? $projects[ $i ] : array( '', '', '', 0 );
		$urls    = array();
		for ( $n = 1; $n <= $project[3]; $n++ ) {
			$urls[] = osus_asset( "images/projects/{$project[2]}-{$n}.webp" );
		}
		$defaults[ "project_{$i}_title" ]  = $project[0];
		$defaults[ "project_{$i}_meta" ]   = $project[1];
		$defaults[ "project_{$i}_images" ] = implode( "\n", $urls );
	}

	// Draft copy: replace with the client's own descriptions
	$services = array(
		1 => array( 'Interior Designing', 'Complete interior concepts—layouts, materials, lighting and furnishings—shaped around the way you live.', 'images/projects/residence-1-800.webp' ),
		2 => array( 'Architectural Planning', 'Plans and elevations that balance light, flow and structure, so the building and its interiors work as one.', 'videos/site-to-sanctuary.webp' ),
		3 => array( 'Project Management', 'One team coordinating contractors, timelines and budgets, so the design is delivered exactly as drawn.', 'videos/glass-house.webp' ),
		4 => array( 'Turnkey Solution', 'From concept to the final cushion—we design, build and furnish, then hand you the keys to a finished space.', 'images/projects/aurelle-2-800.webp' ),
		5 => array( 'Furniture & Space Planning', 'Custom furniture and considered layouts that make every square foot look, feel and function right.', 'images/projects/islamabad-2-800.webp' ),
	);
	foreach ( $services as $i => $service ) {
		$defaults[ "service_{$i}_title" ] = $service[0];
		$defaults[ "service_{$i}_text" ]  = $service[1];
		$defaults[ "service_{$i}_image" ] = osus_asset( $service[2] );
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

	// Layout preview only: sample text, not real client reviews
	$reviews = array(
		1 => array( 'From the first sketch to the final cushion, the team understood exactly how our family lives. The house finally feels like ours.', 'Sample review', 'Residence · Lahore' ),
		2 => array( 'Clear timelines, honest budgets and a finish that genuinely surprised us. Our apartment feels twice the size.', 'Sample review', 'Apartment · Islamabad' ),
		3 => array( 'They took a plain office floor and gave it calm, warmth and character. Visitors notice it the moment they walk in.', 'Sample review', 'Workspace · Lahore' ),
	);
	for ( $i = 1; $i <= 6; $i++ ) {
		$review = isset( $reviews[ $i ] ) ? $reviews[ $i ] : array( '', '', '' );
		$defaults[ "review_{$i}_text" ] = $review[0];
		$defaults[ "review_{$i}_name" ] = $review[1];
		$defaults[ "review_{$i}_meta" ] = $review[2];
	}

	return $defaults;
}

/**
 * Hero slides shipped with the theme: file base name, widest size, caption.
 */
function osus_hero_defaults() {
	return array(
		1 => array( 'hero-dining', 1800, 'Dining · Residential interiors' ),
		2 => array( 'hero-living', 1800, 'Living · Residential interiors' ),
		3 => array( 'hero-stairs', 1800, 'Entrance & stair · Residential interiors' ),
		4 => array( 'hero-kitchen', 1600, 'Kitchen · Residential interiors' ),
	);
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
	wp_enqueue_style( 'osus-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@1,500&family=Manrope:wght@400;500;600;700&display=swap', array(), null );
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

// Intro flag is set before first paint so the page animations wait for the curtain (first visit per session)
add_action( 'wp_head', function () {
	echo "<script>try{if(!sessionStorage.getItem('osus-intro')&&!matchMedia('(prefers-reduced-motion: reduce)').matches){document.documentElement.classList.add('has-intro');sessionStorage.setItem('osus-intro','1');setTimeout(function(){document.documentElement.classList.add('intro-done')},4000)}}catch(e){}</script>\n";
}, 1 );

add_action( 'wp_head', function () {
	echo '<meta name="theme-color" content="#34151e">' . "\n";
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/png" href="' . esc_url( osus_asset( 'logo/osus-logo.png' ) ) . '">' . "\n";
	}
	if ( ! is_front_page() ) {
		return;
	}

	$slides = osus_hero_slides();
	if ( $slides ) {
		printf( '<link rel="preload" as="image" href="%s"%s imagesizes="95vw" fetchpriority="high">' . "\n", esc_url( $slides[0]['url'] ), $slides[0]['srcset'] ? ' imagesrcset="' . esc_attr( $slides[0]['srcset'] ) . '"' : '' );
	}

	// Social sharing preview and local-business details for search engines
	$name        = get_bloginfo( 'name' );
	$description = get_bloginfo( 'description' );
	$image       = osus_asset( 'images/og-image.jpg' );
	printf( '<meta property="og:type" content="website">' . "\n" . '<meta property="og:site_name" content="%1$s">' . "\n" . '<meta property="og:title" content="%1$s">' . "\n" . '<meta property="og:description" content="%2$s">' . "\n" . '<meta property="og:url" content="%3$s">' . "\n" . '<meta property="og:image" content="%4$s">' . "\n" . '<meta name="twitter:card" content="summary_large_image">' . "\n",
		esc_attr( $name ), esc_attr( $description ), esc_url( home_url( '/' ) ), esc_url( $image ) );
	$business = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'HomeAndConstructionBusiness',
		'name'      => $name,
		'url'       => home_url( '/' ),
		'logo'      => osus_logo_url(),
		'image'     => $image,
		'email'     => osus_mod( 'contact_email' ),
		'telephone' => osus_mod( 'contact_phone' ),
		'address'   => array( '@type' => 'PostalAddress', 'addressLocality' => 'Lahore', 'addressCountry' => 'PK' ),
		'sameAs'    => array_filter( array( osus_mod( 'contact_instagram' ) ) ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $business ) . "</script>\n";
} );

/* ---------- Template helpers ---------- */

function osus_logo_url() {
	$logo_id = get_theme_mod( 'custom_logo' );
	$url     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
	return $url ? $url : osus_asset( 'logo/osus-logo.png' );
}

/**
 * Responsive srcset for an image URL: Media Library uploads use WordPress sizes,
 * the theme's own photos use their bundled smaller copies.
 */
function osus_srcset( $url, $fallback = '' ) {
	$id = attachment_url_to_postid( $url );
	if ( $id ) {
		$srcset = wp_get_attachment_image_srcset( $id, 'full' );
		return $srcset ? $srcset : '';
	}
	if ( $fallback ) {
		return $fallback;
	}
	// theme project photos ship with a -800 copy next to the 1080px original
	$projects = osus_asset( 'images/projects/' );
	if ( 0 === strpos( $url, $projects ) && preg_match( '/^(.+)\.webp$/', $url, $m ) && false === strpos( $url, '-800.webp' ) ) {
		return $m[1] . '-800.webp 800w, ' . $url . ' 1080w';
	}
	return '';
}

function osus_hero_default_srcset( $i ) {
	$slide = osus_hero_defaults()[ $i ];
	$base  = osus_asset( 'images/' . $slide[0] );
	return "{$base}-900.webp 900w, {$base}-1400.webp 1400w, {$base}.webp {$slide[1]}w";
}

/**
 * Hero slides with an image set, in order.
 */
function osus_hero_slides() {
	$defaults = osus_defaults();
	$slides   = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$url = osus_mod( "hero_{$i}_image" );
		if ( ! $url ) {
			continue;
		}
		$slides[] = array(
			'url'     => $url,
			'srcset'  => osus_srcset( $url, $url === $defaults[ "hero_{$i}_image" ] ? osus_hero_default_srcset( $i ) : '' ),
			'caption' => osus_mod( "hero_{$i}_caption" ),
		);
	}
	return $slides;
}

/**
 * Projects with a name and at least one photo URL (one per line in the Customizer).
 */
function osus_projects() {
	$projects = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$title  = osus_mod( "project_{$i}_title" );
		$images = array_values( array_filter( array_map( 'trim', explode( "\n", (string) osus_mod( "project_{$i}_images" ) ) ) ) );
		if ( $title && $images ) {
			$projects[] = array( 'title' => $title, 'meta' => osus_mod( "project_{$i}_meta" ), 'images' => $images );
		}
	}
	return $projects;
}

function osus_reviews() {
	$reviews = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$text = osus_mod( "review_{$i}_text" );
		if ( $text ) {
			$reviews[] = array( 'text' => $text, 'name' => osus_mod( "review_{$i}_name" ), 'meta' => osus_mod( "review_{$i}_meta" ) );
		}
	}
	return $reviews;
}

/**
 * Web3Forms key; the placeholder makes the forms say "not connected yet" instead of sending.
 */
function osus_form_key() {
	$key = trim( (string) osus_mod( 'web3forms_key' ) );
	return $key ? $key : 'YOUR_WEB3FORMS_ACCESS_KEY';
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

	$add = function ( $section, $key, $label, $type = 'text', $description = '' ) use ( $wp_customize, $defaults ) {
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

		$args = array( 'label' => $label, 'section' => $section, 'description' => $description );
		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, $args ) );
		} elseif ( 'video' === $type ) {
			$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, $key, $args + array( 'mime_type' => 'video' ) ) );
		} else {
			$wp_customize->add_control( $key, $args + array( 'type' => $type ) );
		}
	};

	$wp_customize->add_panel( 'osus', array( 'title' => __( 'OSUS homepage', 'osus' ), 'priority' => 25 ) );

	$wp_customize->add_section( 'osus_hero', array( 'title' => __( 'Hero', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_hero', 'hero_eyebrow', __( 'Small label', 'osus' ) );
	$add( 'osus_hero', 'hero_title', __( 'Headline', 'osus' ), 'textarea', __( 'Each line of text becomes a line of the headline.', 'osus' ) );
	$add( 'osus_hero', 'hero_text', __( 'Intro text', 'osus' ), 'textarea' );
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: slide number */
		$add( 'osus_hero', "hero_{$i}_image", sprintf( __( 'Slide %d photo (landscape)', 'osus' ), $i ), 'image' );
		$add( 'osus_hero', "hero_{$i}_caption", sprintf( __( 'Slide %d caption', 'osus' ), $i ) );
	}

	$wp_customize->add_section( 'osus_studio', array( 'title' => __( 'Studio', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_studio', 'studio_heading', __( 'Heading (fills in on scroll)', 'osus' ), 'textarea' );
	$add( 'osus_studio', 'studio_text', __( 'Text', 'osus' ), 'textarea' );

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

	$wp_customize->add_section( 'osus_projects', array(
		'title'       => __( 'Selected projects', 'osus' ),
		'panel'       => 'osus',
		'description' => __( 'Each project shows its photos in turn. Upload photos in Media → Add New, copy each file URL and paste one per line. Leave the name empty to hide a project.', 'osus' ),
	) );
	$add( 'osus_projects', 'projects_heading', __( 'Heading', 'osus' ) );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: project number */
		$add( 'osus_projects', "project_{$i}_title", sprintf( __( 'Project %d name', 'osus' ), $i ) );
		$add( 'osus_projects', "project_{$i}_meta", sprintf( __( 'Project %d type · city', 'osus' ), $i ) );
		$add( 'osus_projects', "project_{$i}_images", sprintf( __( 'Project %d photo URLs (one per line)', 'osus' ), $i ), 'textarea' );
	}

	$wp_customize->add_section( 'osus_services', array( 'title' => __( 'What we do', 'osus' ), 'panel' => 'osus' ) );
	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: service number */
		$add( 'osus_services', "service_{$i}_title", sprintf( __( 'Service %d name', 'osus' ), $i ) );
		$add( 'osus_services', "service_{$i}_text", sprintf( __( 'Service %d detail (two lines)', 'osus' ), $i ), 'textarea' );
		$add( 'osus_services', "service_{$i}_image", sprintf( __( 'Service %d photo', 'osus' ), $i ), 'image' );
	}

	$wp_customize->add_section( 'osus_reviews', array(
		'title'       => __( 'Client reviews', 'osus' ),
		'panel'       => 'osus',
		'description' => __( 'Only publish genuine reviews. Leave a review text empty to hide it.', 'osus' ),
	) );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: review number */
		$add( 'osus_reviews', "review_{$i}_text", sprintf( __( 'Review %d text', 'osus' ), $i ), 'textarea' );
		$add( 'osus_reviews', "review_{$i}_name", sprintf( __( 'Review %d client name', 'osus' ), $i ) );
		$add( 'osus_reviews', "review_{$i}_meta", sprintf( __( 'Review %d project · city', 'osus' ), $i ) );
	}

	$wp_customize->add_section( 'osus_contact', array( 'title' => __( 'Contact & forms', 'osus' ), 'panel' => 'osus' ) );
	$add( 'osus_contact', 'web3forms_key', __( 'Web3Forms access key', 'osus' ), 'text', __( 'Create a free key at web3forms.com with the Gmail address that should receive enquiries and reviews.', 'osus' ) );
	$add( 'osus_contact', 'contact_email', __( 'Email', 'osus' ), 'email' );
	$add( 'osus_contact', 'contact_phone', __( 'Phone', 'osus' ) );
	$add( 'osus_contact', 'contact_whatsapp', __( 'WhatsApp number (digits with country code)', 'osus' ) );
	$add( 'osus_contact', 'contact_instagram', __( 'Instagram URL', 'osus' ), 'url' );
	$add( 'osus_contact', 'contact_location', __( 'Location line', 'osus' ) );
} );
