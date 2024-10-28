<?php
	//Check if WP else Exit
	if ( ! defined( 'ABSPATH' ) ) {
		exit();
	}
	
	/**
	 * Functions file
	 * 
	 * File Includes Main functions
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . "lib" . DS . "includes" . DS . 'abas_functions.php'; //include functions menu file.
	
	/**
	 * Admin Menu Generator
	 * 
	 * File to handle Admin menu
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . "lib" . DS . 'admin_menu.php'; //include admin menu file.
		
	/**
	 * Settings Page
	 * 
	 * For plugin settings function available in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'main_page.php';

	/**
	 * Company page
	 * 
	 * For company page funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'post-types' . DS . 'company.php'; 

	/**
	 * Accounts page
	 * 
	 * For Accounts page funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR. 'lib' . DS . 'includes' . DS . 'post-types' . DS . 'accounts.php'; 

	/**
	 * Account Groups
	 * 
	 * For Account Groups page funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'post-types' . DS . 'account_groups.php'; 	

	/**
	 * settings page
	 * 
	 * For admin funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'settings.php';

	/**
	 * Journal Voucher page
	 * 
	 * For admin funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'abas_pages' . DS . 'abas_jvs.php';

	/**
	 * Journal Voucher Print Report Functions
	 * 
	 * For admin funcitons in.
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'abas_reports' . DS . 'abas_report_functions.php';

	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'abas_reports' . DS . 'abas_report.php';

	/**
	 * Journal Voucher Report Functions
	 * 
	 * For admin funcitons in.
	 */

	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'abas_pages' . DS . 'abas_report_print.php';
	
	// Admin pages starts here.
	
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'includes' . DS . 'classes' . DS . 'index.php';
	
	// Admin pages ends here.

	/**
	 * Plugin Activate page
	 * 
	 * For admin funcitons in.  
	 */
	require_once ABAS_ACCOUNTS_BUDDY_DIR . 'lib' . DS . 'activate.php';