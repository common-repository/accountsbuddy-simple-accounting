<?php
defined( 'ABSPATH' ) || exit;
	
/**
 * Function to add admin menu
 * 
 * @Since 1.0
 * @package Accounts Buddy
 */
if ( ! function_exists( 'abas_add_accounting_pages' ) ) :
	function abas_add_accounting_pages() {
		// main_sub Menu Page
		$abas_main_menu_name = ( empty( get_option ( 'abas_main_menu_name' ) ) ) ? esc_html__( 'Accounts Buddy', 'accounts-buddy-accounting' ) : get_option( 'abas_main_menu_name' );	

		add_menu_page ( $abas_main_menu_name, $abas_main_menu_name, 'manage_options', 'abas_accounting_handle', 'abas_dashboard_page', ABAS_URL . '/assets/images/calculator-solid.svg', '3' );

		add_submenu_page ( 'abas_accounting_handle', esc_html__( 'Manage Companies', 'accounts-buddy-accounting' ), esc_html__( 'Manage Companies', 'accounts-buddy-accounting' ), 'manage_options' , 'edit.php?post_type=abas_company' );

		add_submenu_page ( 'abas_accounting_handle', esc_html__( 'Manage Accounts', 'accounts-buddy-accounting' ), esc_html__( 'Manage Accounts', 'accounts-buddy-accounting' ), 'manage_options', 'edit.php?post_type=abas_accounts' );

		add_submenu_page ( 'abas_accounting_handle', esc_html__( 'Journal Voucher', 'accounts-buddy-accounting' ), esc_html__( 'Journal Voucher', 'accounts-buddy-accounting' ), 'manage_options' , 'abas_jvs_page', 'abas_jvs_page' );

		add_submenu_page ( 'abas_accounting_handle', esc_html__( 'Report', 'accounts-buddy-accounting' ), esc_html__( 'Report', 'accounts-buddy-accounting' ), 'manage_options' , 'abas_report', 'abas_report' );
				
		add_submenu_page( 'abas_report', esc_html__( 'Print Screen', 'accounts-buddy-accounting' ), esc_html__( 'Print Screen', 'accounts-buddy-accounting' ), 'manage_options', 'abas_report_print', 'abas_report_print_functionality' );
	}
	add_action( 'admin_menu', 'abas_add_accounting_pages' );
endif;