<?php
/**
 * Scripts
 *
 * @package     EDD\Manual_Emails\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      array $edd_settings_page The slug for the EDD settings page
 * @return      void
 */
function edd_manual_emails_admin_scripts( $hook ) {
    global $edd_settings_page;
    
    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    if( $hook == $edd_settings_page && isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'edd-manual-emails' ) {
        wp_enqueue_script( 'edd_manual_emails_admin_js', EDD_MANUAL_EMAILS_URL . '/assets/js/edd-manual-emails' . $suffix . '.js', array( 'jquery' ), true );
    }
}
add_action( 'admin_enqueue_scripts', 'edd_manual_emails_admin_scripts', 100 );
