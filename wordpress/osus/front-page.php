<?php
/**
 * One-page homepage. Text, photos, videos, reviews and contact details come from
 * Appearance → Customize → OSUS homepage.
 */

get_header();

$osus_slides = osus_hero_slides();

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

$osus_projects = osus_projects();
$osus_reviews  = osus_reviews();
$osus_form_key = osus_form_key();
$osus_title    = array_filter( array_map( 'trim', explode( "\n", (string) osus_mod( 'hero_title' ) ) ) );
?>
    <section class="hero" id="home">
      <div class="hero-copy">
        <p class="eyebrow"><?php echo esc_html( osus_mod( 'hero_eyebrow' ) ); ?></p>
        <h1 data-word-reveal><?php echo implode( '<br>', array_map( 'esc_html', $osus_title ) ); ?></h1>
        <p class="hero-text"><?php echo esc_html( osus_mod( 'hero_text' ) ); ?></p>
        <a class="text-link" href="#projects"><?php esc_html_e( 'Explore our work', 'osus' ); ?> <span>↗</span></a>
      </div>
      <?php if ( $osus_slides ) : ?>
      <div class="hero-image" data-hero-slides>
        <?php foreach ( $osus_slides as $i => $slide ) : ?>
          <?php if ( 0 === $i ) : ?>
        <img class="is-active" alt="<?php echo esc_attr( $slide['caption'] ); ?>" data-caption="<?php echo esc_attr( $slide['caption'] ); ?>" src="<?php echo esc_url( $slide['url'] ); ?>"<?php echo $slide['srcset'] ? ' srcset="' . esc_attr( $slide['srcset'] ) . '"' : ''; ?> sizes="95vw" fetchpriority="high">
          <?php else : ?>
        <img alt="<?php echo esc_attr( $slide['caption'] ); ?>" data-caption="<?php echo esc_attr( $slide['caption'] ); ?>" data-src="<?php echo esc_url( $slide['url'] ); ?>"<?php echo $slide['srcset'] ? ' data-srcset="' . esc_attr( $slide['srcset'] ) . '"' : ''; ?> sizes="95vw" decoding="async">
          <?php endif; ?>
        <?php endforeach; ?>
        <p data-hero-caption><?php echo esc_html( $osus_slides[0]['caption'] ); ?></p>
        <div class="hero-dots" aria-hidden="true"><?php echo str_repeat( '<i></i>', count( $osus_slides ) ); ?></div>
      </div>
      <?php endif; ?>
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
          <a class="text-link" href="#enquiry"><?php esc_html_e( 'Book a consultation', 'osus' ); ?> <span>↗</span></a>
        </div>
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

    <section class="projects section" id="projects">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'Selected projects', 'osus' ); ?></p>
          <h2><?php echo esc_html( osus_mod( 'projects_heading' ) ); ?></h2>
        </div>
        <a class="text-link" href="#enquiry"><?php esc_html_e( 'Discuss your project', 'osus' ); ?> <span>↗</span></a>
      </div>
      <?php if ( $osus_projects ) : ?>
      <div class="pcar scroll-reveal" data-pcar aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Selected projects', 'osus' ); ?>">
        <div class="pcar-stage" data-pcar-stage>
          <?php foreach ( $osus_projects as $p => $project ) : ?>
            <?php foreach ( $project['images'] as $url ) : ?>
              <?php $osus_srcset = osus_srcset( $url ); ?>
          <img alt="<?php echo esc_attr( $project['title'] ); ?>" data-project="<?php echo esc_attr( $p ); ?>" data-src="<?php echo esc_url( $url ); ?>"<?php echo $osus_srcset ? ' data-srcset="' . esc_attr( $osus_srcset ) . '"' : ''; ?> sizes="(max-width: 800px) 100vw, 60vw" decoding="async">
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
        <div class="pcar-side">
          <ol class="pcar-list">
            <?php foreach ( $osus_projects as $p => $project ) : ?>
            <li><button type="button" data-pcar-project="<?php echo esc_attr( $p ); ?>"><span><?php echo esc_html( str_pad( $p + 1, 2, '0', STR_PAD_LEFT ) ); ?></span><strong><?php echo esc_html( $project['title'] ); ?></strong><em><?php echo esc_html( $project['meta'] ); ?></em><i><b></b></i></button></li>
            <?php endforeach; ?>
          </ol>
        </div>
      </div>
      <?php endif; ?>
    </section>

    <section class="services section" id="services">
      <p class="section-label"><?php esc_html_e( 'What we do', 'osus' ); ?></p>
      <div class="service-list">
        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
          <?php
          $osus_service = osus_mod( "service_{$i}_title" );
          if ( ! $osus_service ) {
              continue;
          }
          $osus_photo = osus_mod( "service_{$i}_image" );
          $osus_id    = 'service-' . str_pad( $i, 2, '0', STR_PAD_LEFT );
          ?>
        <article class="service-item scroll-reveal">
          <h3 class="service-heading"><button type="button" class="service-row" aria-expanded="false" aria-controls="<?php echo esc_attr( $osus_id ); ?>"><span class="service-num"><?php echo esc_html( str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?></span><span class="service-title"><?php echo esc_html( $osus_service ); ?></span><span class="service-arrow" aria-hidden="true">↗</span></button></h3>
          <div class="service-panel" id="<?php echo esc_attr( $osus_id ); ?>" role="region">
            <div class="service-panel-inner">
              <p><?php echo esc_html( osus_mod( "service_{$i}_text" ) ); ?></p>
              <?php if ( $osus_photo ) : ?>
              <img fetchpriority="low" decoding="async" alt="<?php echo esc_attr( $osus_service ); ?>" src="<?php echo esc_url( $osus_photo ); ?>">
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php endfor; ?>
      </div>
    </section>

    <section class="details-section section">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'Room by room', 'osus' ); ?></p>
          <h2><?php esc_html_e( 'Every corner.', 'osus' ); ?><br><?php esc_html_e( 'Every consideration.', 'osus' ); ?></h2>
        </div>
        <p class="details-intro"><?php esc_html_e( 'From the first light in your bedroom to the last conversation around the kitchen island—beautiful design belongs in the everyday.', 'osus' ); ?></p>
      </div>
      <div class="detail-grid">
        <?php
        $osus_rooms = array(
            array( 'room-kitchen', 'Sage green kitchen with marble island', __( 'Kitchens / Natural finishes', 'osus' ), __( 'The heart of the home', 'osus' ) ),
            array( 'room-bedroom', 'Soft-toned bedroom with sculpted headboard', __( 'Bedrooms / Soft textures', 'osus' ), __( 'A quieter kind of luxury', 'osus' ) ),
            array( 'room-living', 'Living room with blue accent wall and natural light', __( 'Living / Light & landscape', 'osus' ), __( 'Inside meets outside', 'osus' ) ),
        );
        foreach ( $osus_rooms as $room ) :
            $base = osus_asset( 'images/' . $room[0] );
            ?>
        <article class="detail-card scroll-reveal">
          <div class="project-image"><img loading="lazy" alt="<?php echo esc_attr( $room[1] ); ?>" src="<?php echo esc_url( $base . '.webp' ); ?>" srcset="<?php echo esc_attr( $base . '-800.webp 800w, ' . $base . '.webp 1080w' ); ?>" sizes="(max-width: 800px) 100vw, 33vw" decoding="async"></div>
          <p class="section-label"><?php echo esc_html( $room[2] ); ?></p>
          <h3><?php echo esc_html( $room[3] ); ?></h3>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="quote scroll-reveal">
      <p class="section-label"><?php esc_html_e( 'Our philosophy', 'osus' ); ?></p>
      <blockquote data-scroll-fill>“<?php esc_html_e( 'A room should feel collected, not decorated.', 'osus' ); ?>”</blockquote>
      <p><?php esc_html_e( 'We design for real life—calm, tactile and quietly expressive.', 'osus' ); ?></p>
    </section>

    <section class="reviews section" id="reviews" aria-label="<?php esc_attr_e( 'Client reviews', 'osus' ); ?>">
      <div class="section-heading scroll-reveal">
        <div>
          <p class="section-label"><?php esc_html_e( 'Client stories', 'osus' ); ?></p>
          <h2><?php esc_html_e( 'Kind words from', 'osus' ); ?><br><?php esc_html_e( 'our clients.', 'osus' ); ?></h2>
        </div>
      </div>
      <?php if ( $osus_reviews ) : ?>
      <div class="reviews-marquee scroll-reveal" data-reviews>
        <div class="reviews-row">
          <?php foreach ( $osus_reviews as $review ) : ?>
          <figure class="review">
            <blockquote>“<?php echo esc_html( $review['text'] ); ?>”</blockquote>
            <figcaption><strong><?php echo esc_html( $review['name'] ); ?></strong><span><?php echo esc_html( $review['meta'] ); ?></span></figcaption>
          </figure>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      <details class="review-submit">
        <summary class="text-link"><?php esc_html_e( 'Share your experience', 'osus' ); ?> <span aria-hidden="true">↗</span></summary>
        <!-- Reviews arrive by email for approval; nothing is published automatically -->
        <form class="enquiry-form review-form" data-web3form data-success="<?php esc_attr_e( 'Thank you! Your review will appear on the site once it has been approved.', 'osus' ); ?>" action="https://api.web3forms.com/submit" method="POST">
          <input type="hidden" name="access_key" value="<?php echo esc_attr( $osus_form_key ); ?>">
          <input type="hidden" name="subject" value="<?php echo esc_attr( sprintf( 'New client review from %s', wp_parse_url( home_url(), PHP_URL_HOST ) ) ); ?>">
          <input type="hidden" name="from_name" value="<?php echo esc_attr( get_bloginfo( 'name' ) . ' website' ); ?>">
          <input type="checkbox" name="botcheck" class="form-trap" tabindex="-1" autocomplete="off" aria-hidden="true">
          <label><span><?php esc_html_e( 'Your name', 'osus' ); ?></span><input name="name" required autocomplete="name"></label>
          <label><span><?php esc_html_e( 'Project & city', 'osus' ); ?></span><input name="project" placeholder="<?php esc_attr_e( 'e.g. Residence · Lahore', 'osus' ); ?>"></label>
          <label class="review-form-wide"><span><?php esc_html_e( 'Your review', 'osus' ); ?></span><textarea name="review" rows="4" required></textarea></label>
          <button type="submit" class="form-submit"><?php esc_html_e( 'Send review', 'osus' ); ?> <span aria-hidden="true">↗</span></button>
          <p class="form-status" role="status" aria-live="polite"></p>
        </form>
      </details>
    </section>

    <section class="contact-details section scroll-reveal" id="enquiry">
      <div>
        <p class="section-label"><?php esc_html_e( 'The journey · Free consultation', 'osus' ); ?></p>
        <h2><?php esc_html_e( 'Great spaces start with a conversation.', 'osus' ); ?></h2>
        <p><?php esc_html_e( 'Tell us about your space, your ideas and what you have in mind. We will get back to you within one working day.', 'osus' ); ?></p>
        <p class="contact-note"><?php echo esc_html( osus_mod( 'contact_location' ) ); ?></p>
      </div>
      <!-- Submissions are emailed by Web3Forms; the access key is tied to the receiving Gmail address -->
      <form class="enquiry-form" data-web3form data-success="<?php esc_attr_e( 'Thank you—we will be in touch shortly.', 'osus' ); ?>" action="https://api.web3forms.com/submit" method="POST">
        <input type="hidden" name="access_key" value="<?php echo esc_attr( $osus_form_key ); ?>">
        <input type="hidden" name="subject" value="<?php echo esc_attr( sprintf( 'New enquiry from %s', wp_parse_url( home_url(), PHP_URL_HOST ) ) ); ?>">
        <input type="hidden" name="from_name" value="<?php echo esc_attr( get_bloginfo( 'name' ) . ' website' ); ?>">
        <input type="checkbox" name="botcheck" class="form-trap" tabindex="-1" autocomplete="off" aria-hidden="true">
        <label><span><?php esc_html_e( 'Name', 'osus' ); ?></span><input name="name" required autocomplete="name"></label>
        <label><span><?php esc_html_e( 'Email', 'osus' ); ?></span><input type="email" name="email" required autocomplete="email"></label>
        <label><span><?php esc_html_e( 'Phone / WhatsApp', 'osus' ); ?></span><input type="tel" name="phone" required autocomplete="tel" inputmode="tel"></label>
        <label><span><?php esc_html_e( 'Tell us about your space', 'osus' ); ?></span><textarea name="message" rows="3"></textarea></label>
        <button type="submit" class="form-submit"><?php esc_html_e( 'Send enquiry', 'osus' ); ?> <span aria-hidden="true">↗</span></button>
        <p class="form-status" role="status" aria-live="polite"></p>
      </form>
    </section>
<?php
get_footer();
