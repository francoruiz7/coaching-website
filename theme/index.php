<?php
/**
 * Single-page site: any other URL renders the same front page.
 *
 * @package francoruiz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/front-page.php';
