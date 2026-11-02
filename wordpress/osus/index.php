<?php
/**
 * Fallback template for blog posts, pages and archives.
 */

get_header();
?>
    <section class="entry section">
      <?php if ( have_posts() ) : ?>
        <?php if ( ! is_singular() ) : ?>
        <p class="section-label"><?php echo is_home() ? esc_html__( 'Journal', 'osus' ) : wp_kses_post( get_the_archive_title() ); ?></p>
        <?php endif; ?>

        <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'entry-item' ); ?>>
          <?php if ( is_singular() ) : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php if ( has_post_thumbnail() ) : ?>
            <div class="entry-image"><?php the_post_thumbnail( 'large' ); ?></div>
            <?php endif; ?>
            <div class="entry-content"><?php the_content(); ?></div>
          <?php else : ?>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="entry-content"><?php the_excerpt(); ?></div>
            <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'osus' ); ?> <span>↗</span></a>
          <?php endif; ?>
        </article>
        <?php endwhile; ?>

        <?php the_posts_pagination(); ?>
      <?php else : ?>
        <p class="section-label"><?php esc_html_e( 'Not found', 'osus' ); ?></p>
        <h1 class="entry-title"><?php esc_html_e( 'This page could not be found.', 'osus' ); ?></h1>
        <a class="text-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'osus' ); ?> <span>↗</span></a>
      <?php endif; ?>
    </section>
<?php
get_footer();
