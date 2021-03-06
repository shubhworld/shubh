<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/vc_templates/vc_templates.php' ) ) {
	include_once( get_template_directory() . '/vc_templates/vc_templates.php' );
}

$output = $message_color = $el_class = $css_animation = $animation_delay = $animation_speed = '';
extract(shortcode_atts(array(
	'message_color' => '',
	'el_class' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
), $atts));
$el_class = $this->getExtraClass($el_class);

$class = "";
$div_data = array();

$message_color = ( $message_color !== '') ? ' style-'.$message_color . '-bg' : '';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_content_element' . $class . $el_class, $this->settings['base'], $atts );

if ($css_animation !== '') {
	$css_class .= 'animate_when_almost_visible ' . $css_animation;
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

?>
<div class="<?php echo esc_attr($css_class); ?>" <?php echo implode(' ', $div_data_attributes); ?>>
	<div class="messagebox_text<?php echo esc_attr($message_color); ?>"><?php echo uncode_remove_p_tag($content, true); ?></div>
</div>
