<?php

namespace simple_captcha_wpforms;



use function simple_captcha_wpforms\core\debug\cl;

new WPForms_Field_Simple_Captcha();
/**
 * Simple Captcha field.
 *
 * @since 1.0.0
 */
class WPForms_Field_Simple_Captcha extends \WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Define field type information.
		$this->name  = esc_html__( 'Simple Captcha', 'simple-captcha-wpforms' );
		$this->type  = 'simple_captcha';
		$this->icon  = 'fa-lock';
		$this->order = 30;
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field settings.
	 */
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */

		// Options open markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Label.
		$this->field_option( 'label', $field,
            array(
                'tooltip' => __('Enter your question here.','simple-captcha-wpforms'),
            )
        );

		// Description.
		$this->field_option( 'description', $field );

        // Answers
        $lbl = $this->field_element(
            'label',
            $field,
            [
                'slug'          => 'simple_captcha_answers',
                'value'         => esc_html__( 'Answers', 'simple-captcha-wpforms' ),
                'tooltip'       => esc_html__( 'Enter one or multiple valid answers. Each in a new line.', 'simple-captcha-wpforms' ),
            ],
            false
        );
        $fld = $this->field_element(
            'textarea',
            $field,
            [
                'slug'  => 'simple_captcha_answers',
                'value' => ! empty( $field['simple_captcha_answers'] ) ? esc_attr( $field['simple_captcha_answers'] ) : '',
            ],
            false
        );
        $this->field_element(
            'row',
            $field,
            [
                'slug'    => 'simple_captcha_answers',
                'content' => $lbl . $fld,
            ]
        );

        // Custom error message
        $lbl = $this->field_element(
            'label',
            $field,
            [
                'slug'          => 'simple_captcha_error_message',
                'value'         => esc_html__( 'Custom error message', 'simple-captcha-wpforms' ),
                'tooltip'       => esc_html__( 'Enter the message you want to show when the user has not entered a correct answer. Leave empty for the default message.', 'simple-captcha-wpforms' ),
            ],
            false
        );
        $fld = $this->field_element(
            'text',
            $field,
            [
                'slug'  => 'simple_captcha_error_message',
                'value' => ! empty( $field['simple_captcha_error_message'] ) ? esc_attr( $field['simple_captcha_error_message'] ) : '',
            ],
            false
        );
        $this->field_element(
            'row',
            $field,
            [
                'slug'    => 'simple_captcha_error_message',
                'content' => $lbl . $fld,
            ]
        );

        // Hide required toggle, always needs to be required
        $this->field_element(
            'text',
            $field,
            array(
                'slug'  => 'required',
                'value' => 1,
                'type'  => 'hidden',
            )
        );

		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			array(
				'markup' => 'close',
			)
		);

		/*
		 * Advanced field options.
		 */

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			array(
				'markup' => 'open',
			)
		);

		// Size.
		$this->field_option( 'size', $field );

		// Placeholder.
		$this->field_option( 'placeholder', $field );

		// Default value.
		$this->field_option( 'default_value', $field );

		// Custom CSS classes.
		$this->field_option( 'css', $field );

		// Hide label.
		$this->field_option( 'label_hide', $field );

		// Options close markup.
		$this->field_option(
			'advanced-options',
			$field,
			[
				'markup' => 'close',
			]
		);
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field settings.
	 */
	public function field_preview( $field ) {

		// Define data.
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';

		// Label.
		$this->field_preview_option( 'label', $field );

		// Primary input.
		echo '<input type="text" placeholder="' . esc_attr( $placeholder ) . '" class="primary-input" readonly>';

		// Description.
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field      Field settings.
	 * @param array $deprecated Deprecated.
	 * @param array $form_data  Form data and settings.
	 */
	public function field_display( $field, $deprecated, $form_data ) {

		// Define data.
		$primary = $field['properties']['inputs']['primary'];

		// Primary field.
		printf(
			'<input type="text" %s %s>',
			wpforms_html_attributes( $primary['id'], $primary['class'], $primary['data'], $primary['attr'] ),
			$primary['required'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}


	/**
	 * Validate field on form submit.
	 *
	 * @since 1.6.2
	 *
	 * @param int   $field_id     Field ID.
	 * @param mixed $field_submit Field value that was submitted.
	 * @param array $form_data    Form data and settings.
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		parent::validate( $field_id, $field_submit, $form_data );

		$field = $form_data['fields'][ $field_id ];
		$value = sanitize_text_field( $field_submit );

        $valid_passwords = explode(PHP_EOL, $field['simple_captcha_answers']);
        $is_valid = false;
        foreach ($valid_passwords as $validPw){
            if(trim($value) != '' &&  trim($value) == trim($validPw)){
                $is_valid = true;
                continue;
            }
        }

		if ( !$is_valid ) {
            $message = !empty($field['simple_captcha_error_message'])
                ? esc_html($field['simple_captcha_error_message'])
                : __( 'Please answer the question correctly.','simple-captcha-wpforms' );

            wpforms()->process->errors[ $form_data['id'] ][ $field_id ]
                = $message ;
            return;
		}
	}
}

