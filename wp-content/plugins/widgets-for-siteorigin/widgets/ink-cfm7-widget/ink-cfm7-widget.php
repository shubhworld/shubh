<?php

/*
Widget Name: Inked Contact Form 7
Description: Add and style contact form 7
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Contact_Form_7_SO_Widget extends SiteOrigin_Widget {

	function __construct() {
		parent::__construct(

			'ink-contact-form-7',
			__( 'Inked Contact Form 7', 'wpinked-widgets' ),
			array(
				'description' => __( 'Add and style contact form 7', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/forms/contact-form-7/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

    function get_widget_form() {
        return array(

			'form'      => array(
				'type'        => 'section',
				'label'       => __( 'Contact Form' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'form'       => array(
						'type'      => 'select',
						'label'     => __( 'Select Form', 'wpinked-widgets' ),
						'default'   => '0',
						'options'   => wpinked_so_cf7_list()
					),

					'title'      => array(
						'type'        => 'text',
						'label'       => __( 'Title', 'wpinked-widgets' ),
						'default'     => ''
					),

					'description'      => array(
						'type'        => 'textarea',
						'label'       => __( 'Description', 'wpinked-widgets' ),
						'default'     => '',
						'rows' => 3
					),

					'error'          => array(
						'type'         => 'checkbox',
						'label'        => __( 'Display error messages ?', 'wpinked-widgets' ),
						'default'      => true
					),

					'validation'    => array(
						'type'         => 'checkbox',
						'label'        => __( 'Display validation error messages ?', 'wpinked-widgets' ),
						'default'      => true
					),

				),

			),

			'styling'      => array(
				'type'        => 'section',
				'label'       => __( 'Styling' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'background'       => array(
						'type'      => 'color',
						'label'     => __( 'Background Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'padding'       => array(
						'type'        => 'measurement',
						'label'       => __( 'Padding', 'wpinked-widgets' ),
						'default'     => '15px',
					),

					'corners'       => array(
						'type'        => 'measurement',
						'label'       => __( 'Corners', 'wpinked-widgets' ),
						'default'     => '0px',
					),

					'title-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Title Font', 'wpinked-widgets' ),
					),

					'title-size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Title Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'title-color'       => array(
						'type'      => 'color',
						'label'     => __( 'Title Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'desc-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Description Font', 'wpinked-widgets' ),
					),

					'desc-size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Description Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'desc-color'       => array(
						'type'      => 'color',
						'label'     => __( 'Desciption Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'label-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Label Font', 'wpinked-widgets' ),
					),

					'label-size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Label Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'label-color'       => array(
						'type'      => 'color',
						'label'     => __( 'Label Color', 'wpinked-widgets' ),
						'default'   => ''
					),

				),

			),

			'f-styling'      => array(
				'type'        => 'section',
				'label'       => __( 'Fields Styling' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'background'       => array(
						'type'      => 'color',
						'label'     => __( 'Background Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'gap'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Gap between fields', 'wpinked-widgets' ),
						'default'                => '25px',
					),

					'width'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Field width', 'wpinked-widgets' ),
						'default'                => '100%',
					),

					'i-border'       => array(
						'type'        => 'checkbox',
						'label'       => __( 'Show Input Border?', 'wpinked-widgets' ),
						'default'     => true
					),

					'i-border-clr'   => array(
						'type'        => 'color',
						'label'       => __( 'Input Border Color', 'wpinked-widgets' ),
						'default'     => ''
					),

					'i-border-width'    => array(
						'type'          => 'measurement',
						'label'         => __( 'Input Border width', 'wpinked-widgets' ),
						'default'       => '2px',
					),

					'btn-style'       => array(
						'type'        => 'checkbox',
						'label'       => __( 'Custom Style for Submit Button?', 'wpinked-widgets' ),
						'default'     => true
					),

					'btn-size'             => array(
						'type'                => 'select',
						'label'               => __( 'Button Size', 'wpinked-widgets' ),
						'default'             => 'default',
						'options'             => array(
							'default'            => __( 'Default', 'wpinked-widgets' ),
							'full'               => __( 'Fullwidth', 'wpinked-widgets' ),
						),
					),

					'btn-bg'             => array(
						'type'                => 'color',
						'label'               => __( 'Button Background Color', 'wpinked-widgets' ),
					),

					'btn-bg-h'              => array(
						'type'                => 'color',
						'label'               => __( 'Button Background Hover Color', 'wpinked-widgets' ),
					),

					'btn-clr'             => array(
						'type'                => 'color',
						'label'               => __( 'Button Color', 'wpinked-widgets' ),
					),

					'btn-clr-h'              => array(
						'type'                => 'color',
						'label'               => __( 'Button Hover Color', 'wpinked-widgets' ),
					),

					'btn-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Button Font', 'wpinked-widgets' ),
					),

					'btn-corners'          => array(
						'type'                => 'measurement',
						'label'               => __( 'Button Corners', 'wpinked-widgets' ),
						'default'             => '0.25em',
					),

					'error-color'       => array(
						'type'        => 'color',
						'label'       => __( 'Error Text Color', 'wpinked-widgets' ),
						'default'     => ''
					),

				),

			),

        );
    }

	function get_template_name($instance) {
		return 'contact-form-7';
	}

	function get_style_name($instance) {
		return 'contact-form-7';
	}

	function initialize() {

		$this->register_frontend_styles(
			array(
				array( 'iw-contact-form-7-css', plugin_dir_url(__FILE__) . 'css/contact-form-7.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables( $instance ) {

		if ( empty( $instance ) ) return array();

		$less_variables = array(
			'background'         => $instance['styling']['background'],
			'padding'            => $instance['styling']['padding'],
			'corners'            => $instance['styling']['corners'],
			'title-size'         => $instance['styling']['title-size'],
			'title-color'        => $instance['styling']['title-color'],
			'desc-size'          => $instance['styling']['desc-size'],
			'desc-color'         => $instance['styling']['desc-color'],
			'label-size'         => $instance['styling']['label-size'],
			'label-color'        => $instance['styling']['label-color'],
			'f-background'       => $instance['f-styling']['background'],
			'gap'                => $instance['f-styling']['gap'],
			'width'              => $instance['f-styling']['width'],
			'input-border-clr'   => $instance['f-styling']['i-border-clr'],
			'input-border-width' => $instance['f-styling']['i-border-width'],
			'choice-bg-clr'      => $instance['f-styling']['choice-bg-clr'],
			'choice-select-clr'  => $instance['f-styling']['choice-select-clr'],
			'btn-size'           => $instance['f-styling']['btn-size'],
			'btn-bg'             => $instance['f-styling']['btn-bg'],
			'btn-bg-h'           => $instance['f-styling']['btn-bg-h'],
			'btn-clr'            => $instance['f-styling']['btn-clr'],
			'btn-clr-h'          => $instance['f-styling']['btn-clr-h'],
			'btn-corners'        => $instance['f-styling']['btn-corners'],
			'error-color'        => $instance['f-styling']['error-color'],
		);

		if ( $instance['styling']['title-font']  ) {
            $title_font = siteorigin_widget_get_font( $instance['styling']['title-font'] );
            $less_variables['title-font-fly'] = $title_font['family'];
            if( ! empty( $title_font['weight'] ) ) {
                $less_variables['title-font-wt'] = $title_font['weight'];
            }
		}
		if ( $instance['styling']['desc-font']  ) {
            $title_font = siteorigin_widget_get_font( $instance['styling']['desc-font'] );
            $less_variables['desc-font-fly'] = $title_font['family'];
            if( ! empty( $title_font['weight'] ) ) {
                $less_variables['desc-font-wt'] = $title_font['weight'];
            }
		}
		if ( $instance['styling']['label-font']  ) {
            $title_font = siteorigin_widget_get_font( $instance['styling']['label-font'] );
            $less_variables['label-font-fly'] = $title_font['family'];
            if( ! empty( $title_font['weight'] ) ) {
                $less_variables['label-font-wt'] = $title_font['weight'];
            }
        }
		if ( $instance['styling']['btn-font']  ) {
            $title_font = siteorigin_widget_get_font( $instance['styling']['btn-font'] );
            $less_variables['btn-font-fly'] = $title_font['family'];
            if( ! empty( $title_font['weight'] ) ) {
                $less_variables['btn-font-wt'] = $title_font['weight'];
            }
        }
		return $less_variables;

	}

	function get_template_variables( $instance, $args ) {

		if ( empty( $instance ) ) return array();

		return array(
			'form'         => $instance['form']['form'],
			'title'        => $instance['form']['title'],
			'description'  => $instance['form']['description'],
			'error'        => $instance['form']['error'],
			'validation'   => $instance['form']['validation'],
			'input_border' => $instance['f-styling']['i-border'],
			'btn_style'    => $instance['f-styling']['btn-style'],
		);
	}

	function get_google_font_fields( $instance ) {
		if ( empty( $instance ) ) return array();

		$fonts = array();
		if ( $instance['styling']['title-font'] ) {
			$fonts[] = $instance['styling']['title-font'];
        }
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-contact-form-7', __FILE__, 'Inked_Contact_Form_7_SO_Widget' );
