<?php
/**
 * One-page homepage. Text, images, videos and contact details come from
 * Appearance → Customize → OSUS homepage.
 */

get_header();

$osus_reels = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$video = osus_mod( "reel_{$i}_video" );
	if ( $video ) {
		$osus_reels[] = array(
			'video'  => $video,
			'poster' => osus_mod( "reel_{$i}_poster" ),
			'title'  => osus_mod( "reel_{$i}_title" ),
			'meta'   => osus_mod( "reel_{$i}_meta" ),
		);
	}
}
$osus_email = osus_mod( 'contact_email' );
?>
    <section class="hero" id="home">
      <div class="hero-copy">
        <p class="eyebrow"><?php echo esc_html( osus_mod( 'hero_eyebrow' ) ); ?></p>
        <h1 data-word-reveal><?php echo esc_html( osus_mod( 'hero_title' ) ); ?></h1>
        <p class="hero-text"><?php echo esc_html( osus_mod( 'hero_text' ) ); ?></p>
        <a class="text-link" href="#projects"><?php esc_html_e( 'Explore our work', 'osus' ); ?> <span>↗</span></a>
      </div>
      <div class="hero-image">
        <img alt="<?php echo esc_attr( osus_mod( 'hero_caption' ) ); ?>" src="<?php echo esc_url( osus_mod( 'hero_image' ) ); ?>">
        <p><?php echo esc_html( osus_mod( 'hero_caption' ) ); ?></p>
      </div>
      <span class="hero-number">01</span>
    </section>

    <div class="marquee" aria-hidden="true">
      <div>
        <?php for ( $i = 0; $i < 4; $i++ ) : ?>
        <span>CONSIDERED SPACES <b>↗</b> EXTRAORDINARY LIVING <b>↗</b> </span>
        <?php endfor; ?>
      </div>
    </div>

    <section class="studio section" id="studio">
      <p class="section-label"><?php esc_html_e( 'Our studio', 'osus' ); ?></p>
      <div class="studio-content scroll-reveal">
        <h2 data-scroll-fill><?php echo esc_html( osus_mod( 'studio_heading' ) ); ?></h2>
        <div>
          <p><?php echo esc_html( osus_mod( 'studio_text' ) ); ?></p>
          <a class="text-link" href="#process"><?php esc_html_e( 'Our approach', 'osus' ); ?> <span>↗</span></a>
        </div>
      </div>
    </section>

    <section class="material-story section scroll-reveal">
      <div class="project-image"><img loading="lazy" alt="Double-height lounge with curved sofa and sculptural pendant" src="<?php echo esc_url( osus_asset( 'images/living-lounge.webp' ) ); ?>"></div>
      <div class="material-copy">
        <p class="section-label"><?php esc_html_e( 'Space, thoughtfully composed', 'osus' ); ?></p>
        <h2><?php esc_html_e( 'Less noise.', 'osus' ); ?><br><?php esc_html_e( 'More feeling.', 'osus' ); ?></h2>
        <p><?php esc_html_e( 'Architecture gives a room its shape. Light, texture and the things we choose to live with give it a soul. We consider both, creating a natural dialogue between the built environment and the people within it.', 'osus' ); ?></p>
        <a class="text-link" href="#projects"><?php esc_html_e( 'Step inside', 'osus' ); ?> <span>↗</span></a>
      </div>
    </section>

    <section class="projects section" id="projects">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'Selected projects', 'osus' ); ?></p>
          <h2><?php echo esc_html( osus_mod( 'projects_heading' ) ); ?></h2>
        </div>
        <a class="text-link" href="#contact"><?php esc_html_e( 'Discuss your project', 'osus' ); ?> <span>↗</span></a>
      </div>
      <div class="project-grid">
        <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
        <article class="project-card<?php echo 1 === $i ? ' featured' : ''; ?> scroll-reveal">
          <div class="project-image"><img loading="lazy" alt="<?php echo esc_attr( osus_mod( "project_{$i}_title" ) ); ?>" src="<?php echo esc_url( osus_mod( "project_{$i}_image" ) ); ?>"></div>
          <div class="project-meta"><h3><?php echo esc_html( osus_mod( "project_{$i}_title" ) ); ?></h3><p><?php echo esc_html( osus_mod( "project_{$i}_meta" ) ); ?></p></div>
        </article>
        <?php endfor; ?>
      </div>
    </section>

    <section class="services section" id="services">
      <p class="section-label"><?php esc_html_e( 'What we do', 'osus' ); ?></p>
      <div class="service-list">
        <?php
        $osus_services = array( 'Interior Designing', 'Architectural Planning', 'Project Management', 'Turnkey Solution', 'Furniture & Space Planning' );
        foreach ( $osus_services as $i => $service ) :
        ?>
        <article class="service-row scroll-reveal"><p><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></p><h3><?php echo esc_html( $service ); ?></h3><span>↗</span></article>
        <?php endforeach; ?>
      </div>
    </section>

    <?php if ( $osus_reels ) : ?>
    <section class="reel section" id="reel" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Our signature spaces', 'osus' ); ?>">
      <div class="reel-ambient" aria-hidden="true"></div>
      <div class="reel-head scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'In motion', 'osus' ); ?></p>
          <h2><?php esc_html_e( 'Our signature', 'osus' ); ?><br><em><?php esc_html_e( 'spaces.', 'osus' ); ?></em></h2>
        </div>
        <div class="reel-controls">
          <p class="reel-count"><span data-reel-current>01</span><i></i><span data-reel-total><?php echo esc_html( str_pad( count( $osus_reels ), 2, '0', STR_PAD_LEFT ) ); ?></span></p>
          <div class="reel-arrows">
            <button type="button" class="reel-arrow" data-reel-prev aria-label="<?php esc_attr_e( 'Previous video', 'osus' ); ?>">←</button>
            <button type="button" class="reel-arrow" data-reel-next aria-label="<?php esc_attr_e( 'Next video', 'osus' ); ?>">→</button>
          </div>
        </div>
      </div>

      <div class="reel-stage" data-reel-stage>
        <?php foreach ( $osus_reels as $i => $reel ) : ?>
        <article class="reel-card" data-title="<?php echo esc_attr( $reel['title'] ); ?>" data-meta="<?php echo esc_attr( $reel['meta'] ); ?>">
          <video muted playsinline loop preload="<?php echo 0 === $i ? 'metadata' : 'none'; ?>"<?php echo $reel['poster'] ? ' poster="' . esc_url( $reel['poster'] ) . '"' : ''; ?> src="<?php echo esc_url( $reel['video'] ); ?>"></video>
        </article>
        <?php endforeach; ?>
        <span class="reel-cursor" aria-hidden="true"><?php esc_html_e( 'Drag', 'osus' ); ?></span>
      </div>

      <div class="reel-foot">
        <div class="reel-caption" aria-live="polite">
          <h3 data-reel-title><?php echo esc_html( $osus_reels[0]['title'] ); ?></h3>
          <p data-reel-meta><?php echo esc_html( $osus_reels[0]['meta'] ); ?></p>
        </div>
        <div class="reel-progress" data-reel-progress></div>
      </div>
    </section>
    <?php endif; ?>

    <section class="details-section section">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'Room by room', 'osus' ); ?></p>
          <h2><?php esc_html_e( 'Every corner.', 'osus' ); ?><br><?php esc_html_e( 'Every consideration.', 'osus' ); ?></h2>
        </div>
        <p class="details-intro"><?php esc_html_e( 'From the first light in your bedroom to the last conversation around the kitchen island—beautiful design belongs in the everyday.', 'osus' ); ?></p>
      </div>
      <div class="detail-grid">
        <article class="detail-card scroll-reveal">
          <div class="project-image"><img loading="lazy" alt="Sage green kitchen with marble island" src="<?php echo esc_url( osus_asset( 'images/room-kitchen.webp' ) ); ?>"></div>
          <p class="section-label"><?php esc_html_e( 'Kitchens / Natural finishes', 'osus' ); ?></p>
          <h3><?php esc_html_e( 'The heart of the home', 'osus' ); ?></h3>
        </article>
        <article class="detail-card scroll-reveal">
          <div class="project-image"><img loading="lazy" alt="Soft-toned bedroom with sculpted headboard" src="<?php echo esc_url( osus_asset( 'images/room-bedroom.webp' ) ); ?>"></div>
          <p class="section-label"><?php esc_html_e( 'Bedrooms / Soft textures', 'osus' ); ?></p>
          <h3><?php esc_html_e( 'A quieter kind of luxury', 'osus' ); ?></h3>
        </article>
        <article class="detail-card scroll-reveal">
          <div class="project-image"><img loading="lazy" alt="Living room with blue accent wall and natural light" src="<?php echo esc_url( osus_asset( 'images/room-living.webp' ) ); ?>"></div>
          <p class="section-label"><?php esc_html_e( 'Living / Light & landscape', 'osus' ); ?></p>
          <h3><?php esc_html_e( 'Inside meets outside', 'osus' ); ?></h3>
        </article>
      </div>
    </section>

    <section class="process-section section" id="process">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'The journey', 'osus' ); ?></p>
          <h2><?php esc_html_e( 'Great spaces start', 'osus' ); ?><br><?php esc_html_e( 'with a conversation.', 'osus' ); ?></h2>
        </div>
        <a class="text-link" href="#contact"><?php esc_html_e( "Let's begin", 'osus' ); ?> <span>↗</span></a>
      </div>
    </section>

    <section class="quote scroll-reveal">
      <p class="section-label"><?php esc_html_e( 'Our philosophy', 'osus' ); ?></p>
      <blockquote data-scroll-fill>“<?php esc_html_e( 'A room should feel collected, not decorated.', 'osus' ); ?>”</blockquote>
      <p><?php esc_html_e( 'We design for real life—calm, tactile and quietly expressive.', 'osus' ); ?></p>
    </section>

    <section class="contact-details section scroll-reveal">
      <div>
        <p class="section-label"><?php esc_html_e( 'New possibilities', 'osus' ); ?></p>
        <h2><?php esc_html_e( 'Your next chapter starts here.', 'osus' ); ?></h2>
      </div>
      <div>
        <p><?php esc_html_e( 'Tell us about your space, your ideas and what you have in mind. We would love to explore the possibilities with you.', 'osus' ); ?></p>
        <?php if ( $osus_email ) : ?>
        <a class="text-link" href="mailto:<?php echo esc_attr( antispambot( $osus_email ) ); ?>"><?php esc_html_e( 'Start an email', 'osus' ); ?> <span>↗</span></a>
        <?php endif; ?>
        <p class="contact-note"><?php echo esc_html( osus_mod( 'contact_location' ) ); ?></p>
      </div>
    </section>
<?php
get_footer();
