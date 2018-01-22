<?php
/**
 * Ajax functions
 *
 * @package     EDD\Manual_Emails\Ajax
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * The main ajax function responsible for sending the emails
 *
 * @access      public
 * @since       1.0.0
 * @return      void
 */
function edd_manual_emails_send_ajax() {
    $response = array();

    if(
        ( isset( $_REQUEST['subject'] ) && ! empty( $_REQUEST['subject'] ) ) &&
        ( isset( $_REQUEST['content'] ) && ! empty( $_REQUEST['content'] ) )
    ) {
        $from_name = edd_get_option( 'from_name', get_bloginfo( 'name' ) );
        $from_email = edd_get_option( 'from_email', get_option( 'admin_email' ) );

        $subject = $_REQUEST['subject'];
        $to = $_REQUEST['to'];
        $content = $_REQUEST['content'];

        $to_all_users = ( isset($_REQUEST['to_all_users'] ) && ( $_REQUEST['to_all_users'] == 'true' || $_REQUEST['to_all_users'] === true ) );
        $to_all_vendors = ( isset($_REQUEST['to_all_vendors'] ) && ( $_REQUEST['to_all_vendors'] == 'true' || $_REQUEST['to_all_vendors'] === true ) );

        // This replaces \" to " (jQuery.ajax() automatically adds the \)
        $content = str_replace( '\"', '"', $content );

        // This replaces \' to ' (jQuery.ajax() automatically adds the \)
        $content = str_replace( "\\'", "'", $content );

        // Adds support to send to all users or to all vendors
        if ( $to_all_users || $to_all_vendors ) {
            $user_query_args = array(
                'exclude'   => array( wp_get_current_user()->ID ),
                'number'    => -1
            );

            if ( $to_all_vendors ) {
                $user_query_args['role'] = 'frontend_vendor';
            }

            $users = get_users( $user_query_args );

            foreach ( $users as $user ) {
                $to[] = $user->user_email;
            }
        }

        foreach ( $to as $to_email ) {
            $emails = new EDD_Emails;

            $emails->from_name    = $from_name;
            $emails->from_address = $from_email;
            $emails->heading      = $subject;

            $response[ $to_email ] = $emails->send( $to_email, $subject, $content );
        }
    } else {
        $response[] = false;
    }

    wp_send_json( $response );
}
add_action( 'wp_ajax_edd_manual_emails_send', 'edd_manual_emails_send_ajax' );
