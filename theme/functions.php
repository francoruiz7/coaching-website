<?php
/**
 * Theme functions.
 *
 * @package francoruiz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Address that receives the notification for each new application.
 */
define( 'FR_NOTIFY_EMAIL', 'you@example.com' );

/**
 * Basic theme support.
 */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'script', 'style' ) );
	}
);

/**
 * Styles, fonts and the form script.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$version = wp_get_theme()->get( 'Version' );

		// Version is null so WordPress does not alter the Google Fonts URL.
		wp_enqueue_style(
			'fr-fonts',
			'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,500;1,7..72,400&display=swap',
			array(),
			null
		);
		wp_enqueue_style( 'fr-style', get_stylesheet_uri(), array( 'fr-fonts' ), $version );

		wp_enqueue_script( 'fr-main', get_template_directory_uri() . '/assets/js/main.js', array(), $version, true );
		wp_localize_script( 'fr-main', 'FR_APPLY', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}
);

/**
 * Application fields: key => label shown in the admin panel and in the email.
 */
function fr_fields() {
	return array(
		'claridad'    => '¿En qué área de tu vida sentís que necesitás mayor claridad ahora mismo?',
		'objetivo'    => '¿Qué te gustaría lograr en los próximos 6–12 meses?',
		'expectativa' => '¿Qué esperás de una conversación conmigo?',
		'nombre'      => 'Nombre',
		'apellido'    => 'Apellido',
		'edad'        => 'Edad',
		'email'       => 'Correo electrónico',
		'telefono'    => 'Teléfono',
		'contacto'    => 'Prefiere que lo contacten por',
	);
}

/**
 * Private post type where applications are stored.
 */
add_action(
	'init',
	function () {
		register_post_type(
			'fr_aplicacion',
			array(
				'labels'              => array(
					'name'          => 'Aplicaciones',
					'singular_name' => 'Aplicación',
					'menu_name'     => 'Aplicaciones',
					'all_items'     => 'Todas las aplicaciones',
					'edit_item'     => 'Ver aplicación',
					'search_items'  => 'Buscar aplicaciones',
					'not_found'     => 'Todavía no llegó ninguna aplicación.',
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 25,
				'menu_icon'           => 'dashicons-email-alt',
				'supports'            => array( 'title' ),
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_in_rest'        => false,
				'map_meta_cap'        => true,
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			)
		);
	}
);

/**
 * Receives the form, stores the application and sends the email notification.
 */
function fr_handle_apply() {
	// Honeypot: humans never see this field; if it is filled in, it is a bot. Reply "ok" without storing anything.
	if ( ! empty( $_POST['empresa_web'] ) ) {
		wp_send_json_success();
	}

	// Nobody completes three steps in under three seconds.
	$elapsed = isset( $_POST['fr_t'] ) ? absint( $_POST['fr_t'] ) : 0;
	if ( $elapsed < 3 ) {
		wp_send_json_error( array( 'message' => 'Envío demasiado rápido.' ), 400 );
	}

	// Rate limit per IP address.
	$ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rl_key  = 'fr_rl_' . md5( $ip );
	$attempt = (int) get_transient( $rl_key );
	if ( $attempt >= 5 ) {
		wp_send_json_error( array( 'message' => 'Demasiados envíos. Probá de nuevo más tarde.' ), 429 );
	}
	set_transient( $rl_key, $attempt + 1, HOUR_IN_SECONDS );

	$long = array( 'claridad', 'objetivo', 'expectativa' );
	$data = array();
	foreach ( array_keys( fr_fields() ) as $key ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$raw = is_string( $raw ) ? $raw : '';
		if ( in_array( $key, $long, true ) ) {
			$data[ $key ] = mb_substr( sanitize_textarea_field( $raw ), 0, 5000 );
		} elseif ( 'email' === $key ) {
			$data[ $key ] = sanitize_email( $raw );
		} else {
			$data[ $key ] = mb_substr( sanitize_text_field( $raw ), 0, 200 );
		}
	}

	$data['contacto'] = ( 'E-mail' === $data['contacto'] ) ? 'E-mail' : 'WhatsApp';
	$data['edad']     = $data['edad'] ? (string) absint( $data['edad'] ) : '';

	$required = array( 'claridad', 'objetivo', 'expectativa', 'nombre', 'apellido', 'email' );
	if ( 'WhatsApp' === $data['contacto'] ) {
		$required[] = 'telefono';
	}
	foreach ( $required as $key ) {
		if ( '' === $data[ $key ] ) {
			wp_send_json_error( array( 'message' => 'Faltan datos obligatorios.' ), 400 );
		}
	}
	if ( ! is_email( $data['email'] ) ) {
		wp_send_json_error( array( 'message' => 'El correo no es válido.' ), 400 );
	}

	$full_name = trim( $data['nombre'] . ' ' . $data['apellido'] );
	$post_id   = wp_insert_post(
		array(
			'post_type'   => 'fr_aplicacion',
			'post_status' => 'private',
			'post_title'  => $full_name,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => 'No se pudo guardar la aplicación.' ), 500 );
	}
	foreach ( $data as $key => $value ) {
		update_post_meta( $post_id, '_fr_' . $key, $value );
	}

	// Email notification. To change the recipient, edit FR_NOTIFY_EMAIL at the top of this file.
	$lines = array();
	foreach ( fr_fields() as $key => $label ) {
		$lines[] = $label . "\n" . ( '' !== $data[ $key ] ? $data[ $key ] : '-' );
	}
	$lines[] = 'Ver en el panel: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' );

	wp_mail(
		FR_NOTIFY_EMAIL,
		'Nueva aplicación: ' . $full_name,
		implode( "\n\n", $lines ),
		array( 'Reply-To: ' . $full_name . ' <' . $data['email'] . '>' )
	);

	wp_send_json_success();
}
add_action( 'wp_ajax_fr_apply', 'fr_handle_apply' );
add_action( 'wp_ajax_nopriv_fr_apply', 'fr_handle_apply' );

/**
 * Applications are stored as private posts; these are the columns of the admin list.
 */
add_filter(
	'manage_fr_aplicacion_posts_columns',
	function () {
		return array(
			'cb'          => '<input type="checkbox" />',
			'title'       => 'Nombre',
			'fr_email'    => 'Correo',
			'fr_telefono' => 'Teléfono',
			'fr_contacto' => 'Contactar por',
			'date'        => 'Recibida',
		);
	}
);

add_action(
	'manage_fr_aplicacion_posts_custom_column',
	function ( $column, $post_id ) {
		$map = array(
			'fr_email'    => '_fr_email',
			'fr_telefono' => '_fr_telefono',
			'fr_contacto' => '_fr_contacto',
		);
		if ( isset( $map[ $column ] ) ) {
			echo esc_html( (string) get_post_meta( $post_id, $map[ $column ], true ) );
		}
	},
	10,
	2
);

/**
 * Read-only detail view of each application.
 */
add_action(
	'add_meta_boxes_fr_aplicacion',
	function () {
		add_meta_box( 'fr_respuestas', 'Respuestas', 'fr_render_respuestas', 'fr_aplicacion', 'normal', 'high' );
	}
);

function fr_render_respuestas( $post ) {
	foreach ( fr_fields() as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, '_fr_' . $key, true );
		echo '<p style="margin:1.2em 0 0.3em;"><strong>' . esc_html( $label ) . '</strong></p>';
		echo '<p style="margin:0;font-size:14px;line-height:1.6;">' . ( '' !== $value ? nl2br( esc_html( $value ) ) : '&mdash;' ) . '</p>';
	}
}
