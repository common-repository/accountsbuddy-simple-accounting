<?php
defined( 'ABSPATH' ) || exit;

//Installation of plugin starts here.
if ( ! function_exists( 'abas_install' ) ) :
	function abas_install () {
		//Installs default values on activation.
		global $wpdb;
			
		require_once ABSPATH .'wp-admin/includes/upgrade.php';

		$webful_accounting_journal_voucher 		= $wpdb->prefix.'abas_journal_voucher';
		$webful_accounting_transactions			= $wpdb->prefix.'abas_transactions';
			
		$sql = 'CREATE TABLE '.$webful_accounting_journal_voucher.'(
			`jv_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`date` timestamp NOT NULL,
			`jv_id_manual` varchar(100) NULL,
			`jv_title` varchar(100) NULL,
			`jv_description` varchar(200) NULL,
			`user_id` bigint(20) NULL,
			`company_id` bigint(20) NULL,
			PRIMARY KEY (`jv_id`)
		)';	
		dbDelta($sql);

		$sql = 'CREATE TABLE '.$webful_accounting_transactions.'(
			`tr_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`jv_id` bigint(20) NULL,
			`account_id` bigint(20) NULL,
			`date` timestamp NULL,
			`memo` varchar(400) NULL,
			`debit` decimal(10,2),
			`credit` decimal(10,2),
			PRIMARY KEY (`tr_id`)
		)';	
		dbDelta($sql);

		update_option ( 'abas_plugin_is_activated', 'yes' );
	}//end of function abas_restaurant_install()
	add_action ('abas_install', 'abas_install' );
endif;