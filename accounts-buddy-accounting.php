<?php
/***
	Plugin Name: Accounts Buddy Accounting - Simple Accounting
	Plugin URI: https://www.webfulcreations.com/products/webful-general-ledger-accounting-php-script/
	Description: WordPress Accounting Plugin which helps you manage your Accounts, companies and expenses. Effective General ledger.
	Version: 1.0
	Author: Webful Creations
	Author URI: https://www.webfulcreations.com/
	License: GPLv2 or later.
	License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	Text Domain: accounts-buddy-accounting
	Domain Path: languages
	Requires at least: 5.0
	Tested up to: 6.2.2
	Requires PHP: 7.4
	@package : 1.0
*/
    
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( ! defined( 'DS' ) ) {
	//Directory Separator
	define( 'DS', '/' ); 
}

if ( ! session_id() ) {
	session_start();
}

define( 'ABAS_VERSION', '1.0' );
define( 'ABAS_ACCOUNTS_BUDDY_FILE', __FILE__ );
define( 'ABAS_ACCOUNTS_BUDDY_FOLDER', dirname( plugin_basename(__FILE__) ) );
define( 'ABAS_ACCOUNTS_BUDDY_DIR', plugin_dir_path( __FILE__ ) );	
define( 'ABAS_URL', plugins_url( '', __FILE__ ) );

if( ! function_exists( 'abas_language_plugin_init' ) ) :
	function abas_language_plugin_init() {
		load_plugin_textdomain( 'accounts-buddy-accounting', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}
	add_action( 'plugins_loaded', 'abas_language_plugin_init');
endif;

require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'enginestarter.php';

//Installation of plugin check already exist.
$abas_plugin_is_activated = get_option ( "abas_plugin_is_activated" );

if ( empty ( $abas_plugin_is_activated ) || $abas_plugin_is_activated !== 'yes') {
	register_activation_hook ( ABAS_ACCOUNTS_BUDDY_FILE, 'abas_install' ) ;
}
	
if ( ! function_exists( 'abas_admin_notice_company' ) ) :
	function abas_admin_notice_company() {
		$content = '<div class="updated">';
		$content .= '<p>' . esc_html__( "Administrators can view all types of company!", "accounts-buddy-accounting" ) . '</p>';
		$content .= '</div>';

		$content .= '<div class="error">';
		$content .= '<p>' . esc_html__( "Please Select Your Company", "accounts-buddy-accounting" ) . '</p>';
		$content .= '<p>' . esc_html__( "Company Must Published", "accounts-buddy-accounting" ) . '</p>';
		$content .= '</div>';

		$allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
		echo wp_kses( $content, $allowedHTML );
	}
endif;

if ( ! function_exists( 'abas_run_plugin_company' ) ) : 
	function abas_run_plugin_company () {
		global $pagenow;

		$abas_current_page = get_current_screen() ;

		if( isset ($_GET["company_select"]) && isset ( $_GET["company_selection"] ) ) {
			$_SESSION["company_id"] = sanitize_text_field($_GET["company_select"]) ;
			$company_id =  $_SESSION["company_id"] = sanitize_text_field( $_GET["company_select"] );			
			abas_add_active_company ( $company_id );
		} 
		
		$company_id = abas_active_company_id ();

		$accounts_query = new WP_Query ( array ( 
			'post_type' 	=> 'abas_company', 
			'p' 			=> $company_id,
			'post_status' 	=> array( 'pending' , 'draft' , 'trash'),
			) 		
		);
		
		$accounts_query		=  ( empty ( $accounts_query->post->ID ) ) ? '' : $accounts_query->post->ID;

		if ( isset ( $company_id ) && ! empty ($company_id) ==  $accounts_query ) {
			if ( $abas_current_page->id === 'edit-abas_accounts' ) { 
				wp_redirect( 'edit.php?post_type=abas_company' );
			} elseif (isset($_GET["page"]) && $_GET["page"] === "abas_jvs_page") { 
				wp_redirect( 'edit.php?post_type=abas_company' );
			} elseif (isset($_GET["page"]) && $_GET["page"] === "abas_report") { 
				wp_redirect( 'edit.php?post_type=abas_company' );
			}
		}

		if ( isset ( $company_id ) && ! empty ($company_id) ==  $accounts_query ) {
			add_action( 'admin_notices', 'abas_admin_notice_company' );
		}
		
	}
	add_action ( 'admin_enqueue_scripts', 'abas_run_plugin_company' );
endif;

//Ajax Script Enque
if(!function_exists("abas_ajax_script_enqueue")):
	function abas_ajax_script_enqueue() {
		wp_enqueue_script( 'ajax_script', plugin_dir_url(__FILE__ ).'assets/admin/js/wc-ac-ajax-scripts.js', array('jquery'), '1.0', true );
		wp_localize_script( 'ajax_script', 'ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
	add_action ( 'admin_enqueue_scripts', 'abas_ajax_script_enqueue' );
endif;