<?php

// =============================================================================
// VIEWS/ETHOS/TEMPLATE-LAYOUT-FULL-WIDTH.PHP
// -----------------------------------------------------------------------------
// Fullwidth page output for Ethos.
// =============================================================================

get_header();

?>

  <div class="x-container max width main">
    <div class="offset cf">
      <div class="<?php x_main_content_class(); ?>" role="main">

        <?php while ( have_posts() ) : the_post(); ?>
          <?php x_get_view( 'ethos', 'content', 'page' ); ?>
          <?php x_get_view( 'global', '_comments-template' ); ?>
        <?php endwhile; ?>

      </div>
    </div>
  </div>

<?php get_footer(); ?>
