<?php
$classes = array( 'iw-so-cf7' );

if ( ! empty( $error ) ) $classes[] = 'iw-so-cf7-errors';
if ( ! empty( $validation ) ) $classes[] = 'iw-so-cf7-validation';
if ( ! empty( $input_border ) ) $classes[] = 'iw-so-cf7-input-border';
if ( ! empty( $btn_style ) ) $classes[] = 'iw-so-cf7-btn-style';

?>

<div class="<?php echo esc_attr ( implode ( ' ', $classes ) ) ?>">
    <?php if ( $title || $description ) : ?>
        <?php if ( $title ) : ?>
            <h3 class="iw-so-cf7-title"><?php echo esc_html( $title ); ?></h3>
        <?php endif; ?>
        <?php if ( $description ) : ?>
            <p class="iw-so-cf7-description"><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <?php echo do_shortcode( '[contact-form-7 id="' . $form . '" ]' ); ?>
</div>