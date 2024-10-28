<?php
//Check if WP else Exit
if ( ! defined ( 'ABSPATH' ) ) {
	exit ();
}

/**
 * Functions Used throughout the plugin
 * 
 * Main Webful Accounting Functions
 */

//Adding styles and scripts for wordpress BackEnd.
if ( ! function_exists( 'abas_admin_enqueue_style' ) ) :
	function abas_admin_enqueue_style () {
		global $pagenow;

		$abas_the_page  = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : "";
		if ( ( ! empty( $abas_the_page ) && ( 'abas_accounting_handle' === $abas_the_page ) || ( 'abas_report' === $abas_the_page )  ) ) {
			if ( 'edit.php' !== $pagenow ) {
				wp_enqueue_style ('foundation-css', ABAS_URL . DS . 'assets' . DS . 'admin' .DS. 'css' . DS . 'foundation.min.css', array(), ABAS_VERSION, 'all', true  );
				wp_enqueue_style( 'foundation-css' );
			}
		}
		wp_enqueue_style ('abas-my-admin-style', ABAS_URL . DS . 'assets' . DS . 'admin' .DS. 'css' . DS . 'abas-my-admin-style.css', array(), ABAS_VERSION, 'all' );
				
	}//end of adding styles and scripts for wordpress admin.
	add_action ( 'admin_enqueue_scripts', 'abas_admin_enqueue_style', 1 );
endif;

if(!function_exists("abas_admin_scripts_js")):
	function abas_admin_scripts_js () {
		// WordPress media uploader scripts		
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media ();
		}
		
		//foundationjs
		wp_enqueue_script ('foundation-js', ABAS_URL . DS . 'assets' . DS . 'admin' .DS.  'js' . DS . 'foundation.min.js', array(), '6.5.3', 'all', true );

		// our custom JS
		wp_enqueue_script ('abas-my-admin-scripts', ABAS_URL . DS . 'assets' . DS . 'admin' .DS.  'js' . DS . 'abas-my-admin-scripts.js', array(), ABAS_VERSION, 'all' );
		wp_enqueue_script ('abas-my-media-scripts', ABAS_URL . DS . 'assets' . DS . 'admin' .DS. 'js' . DS . 'abas-my-media-scripts.js', array(), ABAS_VERSION, 'all' );
	}
	add_action( 'admin_enqueue_scripts', 'abas_admin_scripts_js' );	
endif;

//Ajax Script Enque
if(!function_exists("abas_ajax_script_enqueue")):
	function abas_ajax_script_enqueue() {
		wp_enqueue_script( 'ajax_script', plugin_dir_url(__FILE__ ).'../../assets/admin/js/abas-ajax-scripts.js', array('jquery'), ABAS_VERSION, true );
		wp_localize_script( 'ajax_script', 'ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
	add_action( 'admin_enqueue_scripts', 'abas_ajax_script_enqueue' );
endif;

if ( ! function_exists( 'abas_register_script_foundation' ) ) :
    /**
     * Register Scripts
     * Register Styles
     * 
     * To Enque within Shortcodes 
     */
    function abas_register_script_foundation() {
		wp_enqueue_script ('foundationjs', ABAS_URL . DS . 'assets' . DS . 'admin' .DS.  'js' . DS . 'foundation.min.js', array(), ABAS_VERSION, 'all', true );
		wp_enqueue_script ('abas_scripts', ABAS_URL . DS . 'assets' . DS . 'js' .DS. 'abas_scripts.js', array(), ABAS_VERSION, 'all', true );
    }// adding styles and scripts for wordpress admin.
    add_action( 'init', 'abas_register_script_foundation' );
endif;


//modal for Admin
if(!function_exists("abas_admin_modal")):
	function abas_admin_modal () {
		global $pagenow;
		$abas_the_page  = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : "";
		if ( ( isset( $abas_the_page ) && ( 'abas_jvs_page' === $abas_the_page ) ) ) {
			if ( 'edit.php' !== $pagenow ) {
		        do_action ( 'abas_add_jv' );
			}
		}				
	}
  add_filter ( 'admin_footer','abas_admin_modal' );
endif;

//update_user_meta
if(!function_exists("abas_current_user_company_access")):
	function abas_current_user_company_access($company_id) {
		if(empty($company_id)) {
			unset($_SESSION["company_id"]);
		}
		$user = wp_get_current_user();

		//For admin return for all type of items.
		if ( in_array( 'administrator', (array) $user->roles ) ) {
			$_SESSION["user_role"] = "administrator";
			return true;
		}
	}
endif;

//return company_id		
if(!function_exists("abas_active_company_id")):
	function abas_active_company_id() {		
		$user_id = get_current_user_id () ;
		$company_id 	= get_user_meta($user_id, "active_company", "meta_value",  true);
		if ( empty ( $company_id ) ) {
			return FALSE;
		} else { 
			return $company_id;
		}
	}
endif;

// update user meta company_id
if(!function_exists ( "abas_add_active_company" ) ) :
	function abas_add_active_company () {			
		if ( isset ( $_SESSION ['company_id'] ) ) {
			$user_id = get_current_user_id () ;
			$company_id = (int) $_SESSION [ "company_id" ] ;
			update_user_meta( $user_id, 'active_company', $company_id) ;
		}
	}
endif;

// Accounts List dropdown 
if(!function_exists("abas_get_option_list_post_type")):
	function abas_get_option_list_post_type ( $post_type ) {

		if ( empty( $post_type ) ) {
			return '';
		}
		$accounts_args = array(
						'post_type' 		=> $post_type,
						'orderby'			=> 'title',
						'order' 			=> 'ASC',
						'posts_per_page' 	=> -1,
					);

		$accounts_query = new WP_Query( $accounts_args );
		
		$content = '<option value="">----</option>';
		
		if ($accounts_query->have_posts() ) { 
			while($accounts_query->have_posts()) {

				$accounts_query->the_post();

				$accounts_id 		= $accounts_query->post->ID;
				$accounts_title 	= get_the_title();

				$company_id 		= $accounts_query->post->_company_id;
				$active_company 	= abas_active_company_id();

				if ( isset ( $company_id ) &&  ($company_id) ==  $active_company ) {
				  $content .= '<option value="'.$accounts_id.'">'.$accounts_title.'</option>';
				}
			}
		} else {
			return esc_html_e("Sorry nothing to display!", "accounts-buddy-accounting");
		}

		return $content;
	}
endif;

// Accounts List dropdown 
if(!function_exists("abas_get_option_list_taxonomy")):
	function abas_get_option_list_taxonomy ( $taxonomy ) {

		if ( empty( $taxonomy ) ) {
			return '';
		}

		$content = '<option value="all">All Groups</option>';

		$accounts_args = array(
						'post_type' 		=> 'abas_accounts',
						'orderby'			=> 'title',
						'order' 			=> 'ASC',
						'posts_per_page' 	=> -1,
					);

		$accounts_query = new WP_Query( $accounts_args );

		if ($accounts_query->have_posts() ) { 
			while($accounts_query->have_posts()) {

				$accounts_query->the_post();

				$accounts_id 		= $accounts_query->post->ID;
				$company_id 		= $accounts_query->post->_company_id;
				$active_company 	= abas_active_company_id();

				if ( isset ( $company_id ) && ($company_id) ==  $active_company ) {

					$term_obj_lists[] = wp_get_object_terms( $accounts_id, $taxonomy,);

					$term_obj_list = array_unique($term_obj_lists, SORT_REGULAR);

				}

			}
		}
		
		foreach ( $term_obj_list as $result ) {
			
			if ( isset ( $term_obj_list ) && !empty ( $result )) {

				$term_id 		= ($result[0]->term_id);
				$term_name 		= ($result[0]->name);

				if ( isset ( $term_name ) && !empty ( $term_id )) {
					$content .= '<option value="'.$term_id.'">'.$term_name.'</option>';					
				}
				
			}
		}

		return $content;

	}
endif;

// form processing ajx
if ( ! function_exists( 'abas_jv_form' ) ) :
	function abas_jv_form() {
		global $wpdb;

		$error = $message = '';

		$user_id 		= get_current_user_id();
		$company_id 	= abas_active_company_id();

		if ( empty( $company_id ) || empty( $user_id ) ) {
			$error = 1;
			$message = esc_html__( 'Company or user is missing', 'accounts-buddy-accounting' );
		}

		// Verify that the nonce is valid.
		if ( ! isset( $_POST['abas_add_voucher_sub'] ) || ! wp_verify_nonce( sanitize_key( $_POST['abas_add_voucher_sub']), 'abas_meta_add_voucher_nonce' ) ) {
			return;
		}
		
		$values      = array();

		$form_type 		= ( isset( $_POST["form_type"] ) && ! empty ( $_POST["form_type"] ) ) ? sanitize_text_field( $_POST["form_type"] ) : '';
		$jv_date 		= ( isset( $_POST["jv_date"] ) && ! empty ( $_POST["jv_date"] ) ) ? sanitize_text_field( $_POST["jv_date"] ) : '';
		$jv_manual_id 	= ( isset( $_POST["jv_manual_id"] ) && ! empty ( $_POST["jv_manual_id"] ) ) ? sanitize_text_field( $_POST["jv_manual_id"] ) : '';
		$jv_title 		= ( isset( $_POST["jv_title"] ) && ! empty ( $_POST["jv_title"] ) ) ? sanitize_text_field( $_POST["jv_title"] ) : '';
		$jv_description = ( isset( $_POST["jv_description"] ) && ! empty ( $_POST["jv_description"] ) ) ? sanitize_text_field( $_POST["jv_description"] ) : '';

		if ( $form_type != "add_jv_form_text" ) {
			$error = 1;
			$message = esc_html__( 'Unknwon form type', 'accounts-buddy-accounting' );
		}
		if ( empty( $jv_date ) ) {
			$error = 1;
			$message = esc_html__( 'Missing voucher date.', 'accounts-buddy-accounting' );
		}

		if ( $error == 1 ) {
			//Error 
		} else {
			//Let's proceed
			$jv_arguments = array(
								'date'           => $jv_date,
								'jv_id_manual'   => $jv_manual_id,
								'jv_title'       => $jv_title,
								'jv_description' => $jv_description,
								'user_id'        => $user_id,
								'company_id'     => $company_id,
							);
			$jv_id = abas_return_jv_id_posting_data( $jv_arguments );
			
			if ( ! empty( $jv_id ) ) {
				//Record transaction
				for( $i = 0; $i < count( $_POST["account_id"] ); $i++ ) {
					
					$account_id	= ( isset( $_POST["account_id"][$i] ) && ! empty ( $_POST["account_id"][$i] ) ) ? sanitize_text_field( $_POST["account_id"][$i] ) : '';
					$memo		= ( isset( $_POST["memo"][$i] ) && ! empty ( $_POST["memo"][$i] ) ) ? sanitize_text_field( $_POST["memo"][$i] ) : '';
					$debit		= ( isset( $_POST["dr"][$i] ) && ! empty ( $_POST["dr"][$i] ) ) ? sanitize_text_field( $_POST["dr"][$i] ) : '';
					$credit		= ( isset( $_POST["cr"][$i] ) && ! empty ( $_POST["cr"][$i] ) ) ? sanitize_text_field( $_POST["cr"][$i] ) : '';

					$transaction_arguments = array( 
						'jv_id' => $jv_id, 
						'account_id' => $account_id, 
						'date' => $jv_date, 
						'memo' => $memo, 
						'debit' => $debit, 
						'credit' => $credit);

					$tr_id = abas_return_transaction_id_posting_data( $transaction_arguments );
				}//End for
				
				$message = esc_html__( 'Voucher have been added!', 'accounts-buddy-accounting' );

			} else {
				$message = esc_html__( 'Invalid data', 'accounts-buddy-accounting' );
			}
		}

		$values['message'] = $message;
		$values['success'] = "YES";

		wp_send_json( $values );
		wp_die();
	}
	add_action( 'wp_ajax_abas_jv_form', 'abas_jv_form' );
endif;

/**
 * Takes array of arguments
 * array( 'jv_id' => '', 'date' => '', 'jv_id_manual' => '', 'jv_title' => '', 'jv_description' => '', 'user_id' => '', 'company_id' => '')
 * 
 * return JV ID
 */
if ( ! function_exists( 'abas_return_jv_id_posting_data' ) ) : 
	function abas_return_jv_id_posting_data( $jv_arguments ) {
		global $wpdb;

		$table_jv = $wpdb->prefix.'abas_journal_voucher';

		if ( ! is_array( $jv_arguments ) ) {
			return;
		}

		extract( $jv_arguments );

		if ( empty( $company_id ) || empty( $user_id ) || empty( $date ) ) {
			return;
		}
		$jv_id_manual   = ( ! isset( $jv_id_manual ) ) ? '': $jv_id_manual;
		$jv_title	  	= ( ! isset( $jv_title ) ) ? '': $jv_title;
		$jv_description	= ( ! isset( $jv_description ) ) ? '': $jv_description;

		$insert_query =  "INSERT INTO `{$table_jv}` VALUES( NULL, %s, %s, %s, %s, %s, %s )";
		
		$wpdb->query(
			$wpdb->prepare( $insert_query,  $date, $jv_id_manual, $jv_title, $jv_description, $user_id, $company_id )
		);

		$jv_id = $wpdb->insert_id;

		return $jv_id;
	}
endif;

/**
 * Takes array of arguments
 * array('jv_id' => '', 'account_id' => '', 'date' => '', 'memo' => '', 'debit' => '', 'credit' => '')
 * 
 * returns transaction id
 */
if ( ! function_exists( 'abas_return_transaction_id_posting_data' ) ) : 
	function abas_return_transaction_id_posting_data( $transaction_arguments ) {
		global $wpdb;

		$abas_table_transaction = $wpdb->prefix.'abas_transactions';

		if ( ! is_array( $transaction_arguments ) ) {
			return;
		}
		extract( $transaction_arguments );

		if ( empty( $credit ) && empty( $debit ) ) {
			return;
		}
		if ( empty( $account_id ) || empty( $jv_id ) || $jv_id == 0 ) {
			return;
		}

		$date = ( ! isset( $date ) ) ? wp_date( 'Y-m-d' ) : $date;
		$memo = ( ! isset( $memo ) ) ? '': $memo;

		$insert_query =  "INSERT INTO `{$abas_table_transaction}` VALUES( NULL, %s, %s, %s, %s, %d, %d )";
		
		$wpdb->query(
			$wpdb->prepare( $insert_query,  $jv_id, $account_id, $date, $memo, $debit, $credit )
		);
		$tr_id = $wpdb->insert_id;

		return $tr_id;
	}
endif;

if(!function_exists("abas_return_allowed_tags")):
	function abas_return_allowed_tags() {
		$allowed_tags = array(
		'div' => array(
			'class' 		  	=> array(),
			'id' 			  	=> array(),
			'style' 		  	=> array(),
			'data-position'   	=> array(),
			'data-alignment'  	=> array(),
			'data-dropdown'   	=> array(),
			'data-auto-focus' 	=> array(),
			'data-reveal' 	  	=> array(),
			'data-abide-error' 	=> array(),
			'data-tab-content' 	=> array(),
		),
		'form' => array(
			'class' => array(),
			'id' => array(),
			'name' => array(),
			'method' => array(),
			'action' => array(),
			'data-async' => array(),
			'data-success-class' => array(),
			'data-abide' => array(),
			'data-print-reply' => array()
		),
		'label' => array(
			'class' => array(),
			'id' => array(),
			'for'	=> array()
		),
		'input' => array(
			'class' => array(),
			'id' => array(),
			'type'	=> array(),
			'name'	=> array(),
			'required' => array(),
			'value'	=> array(),
			'placeholder'	=> array(),
			'checked' => array(),
			'step'	=> array(),
			'disabled'	=> array(),
			'readonly'	=> array(),
		),
		'textarea' => array(
			'class' => array(),
			'id' => array(),
			'type'	=> array(),
			'name'	=> array(),
			'required' => array(),
			'placeholder'	=> array(),
			'cols'	=> array(),
			'rows' => array()
		),
		'select' => array(
			'class' => array(),
			'id' => array(),
			'name'	=> array(),
			'required' => array(),
			'data-security' => array(),
			'data-placeholder' => array(),
			'data-exclude_type' => array(),
			'data-display_stock' => array(),
			'data-post' => array(),
			'style' => array(),
		),
		'option' => array(
			'value' => array(),
			'selected' => array(),
		),
		'button' => array(
			'class' => array(),
			'id' => array(),
			'for'	=> array(),
			'type' => array(),
			'data-open' => array(),
			'data-close' => array(),
			'data-type' => array(),
			'data-job-id' => array(),
			'data-toggle' => array()
		),
		'fieldset' => array(
			'class' => array(),
		),
		'legend' => array(
			'class' => array(),
		),
		'a' => array(
			'class' => array(),
			'id' => array(),
			'href'	=> array(),
			'title'	=> array(),
			'target' => array(),
			'recordid' => array(),
			'data-open' => array(),
			'data-type' => array(),
			'data-value' => array(),
			'style' => array(),
			'disabled' => array(),
		),
		'table' => array(
			'class' => array(),
			'id' => array(),
			'cellpadding' => array(),
			'cellspacing' => array()
		),
		'thead' => array(
			'class' => array(),
			'id' => array()
		),
		'tbody' => array(
			'class' => array(),
			'id' => array()
		),
		'tr' => array(
			'class' => array(),
			'id' => array()
		),
		'th' => array(
			'class' => array(),
			'id' => array(),
			'colspan' => array(),
			'data-colname' => array()
		),
		'td' => array(
			'class' => array(),
			'id' => array(),
			'align' => array(),
			'colspan' => array(),
			'data-colname' => array()
		),
		'img' => array(
			'class' => array(),
			'id' => array(),
			'src' => array(),
			'alt' => array()
		),
		'h2' => array(
			'class' => array(),
			'id' 	=> array(),
		),
		'ul' => array(
			'class' => array(),
			'id' 	=> array(),
			'data-accordion'	=> array(),
			'data-multi-expand'	=> array(),
			'data-allow-all-closed' => array(),
		),
		'li' => array(
			'class' => array(),
			'id' 	=> array(),
			'data-accordion-item' => array(),
		),
		'h3' => array(
			'class' => array()
		),
		'h4' => array(
			'class' => array()
		),
		'h5' => array(
			'class' => array()
		),
		'h6' => array(
			'class' => array()
		),
		'p' => array(
			'class' => array()
		),
		'b' => array(
			'class' => array(),
			'id' 	=> array(),
		),
		'br' => array(),
		'em' => array(),
		'em' => array(),
		'hr' => array(),
		'small' => array(),
		'strong' => array(),
		'span' => array(
			'class' => array()
		)
	);

		return $allowed_tags;
	}
endif;