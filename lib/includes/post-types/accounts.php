<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'abas_accounts_post_type_init' ) ) :
	function abas_accounts_post_type_init() {
		$labels = array(
			'add_new_item' 			=> esc_html__('Add New Account', 'accounts-buddy-accounting'),
			'singular_name' 		=> esc_html__('Accounts', 'accounts-buddy-accounting'), 
			'menu_name' 			=> esc_html__('Accounts', 'accounts-buddy-accounting'),
			'all_items' 			=> esc_html__('Accounts', 'accounts-buddy-accounting'),
			'edit_item' 			=> esc_html__('Edit Account', 'accounts-buddy-accounting'),
			'new_item' 				=> esc_html__('New Account', 'accounts-buddy-accounting'),
			'view_item' 			=> esc_html__('View Account', 'accounts-buddy-accounting'),
			'search_items' 			=> esc_html__('Search Account', 'accounts-buddy-accounting'),
			'not_found' 			=> esc_html__('No Account found', 'accounts-buddy-accounting'),
			'not_found_in_trash' 	=> esc_html__('No Account in trash', 'accounts-buddy-accounting')
		);
		$args = array(
			'labels'             	=> $labels,
			'label'					=> esc_html__('Accounts', 'accounts-buddy-accounting'),
			'description'        	=> esc_html__('Accounts Section', 'accounts-buddy-accounting'),
			'supports' 				=> array( 'title' ),
			'public'             	=> false,
			'publicly_queryable' 	=> false,
			'show_ui'            	=> true,
			'show_in_menu'       	=> false,
			'query_var'          	=> true,
			'rewrite'               => array('slug' => 'accounts'),
			'capability_type'    	=> 'post',
			'map_meta_cap'        	=> true,
			'has_archive'        	=> true,
			'menu_icon'			 	=> 'dashicons-clipboard',
			'menu_position'      	=> 30,
			'register_meta_box_cb' 	=> 'abas_accounts_features',
		);
		register_post_type( 'abas_accounts', $args );
	}
	add_action( 'init', 'abas_accounts_post_type_init' );
endif;
	//registeration of post type ends here.

if (  ! function_exists( 'abas_accounts_features' ) ) :
    function abas_accounts_features() { 
        $screens = array ( 'abas_accounts' );
   
        foreach ( $screens as $screen ) {
            add_meta_box (
                'abas_accounts_details_box',
                esc_html__( 'Accounts Details', 'accounts-buddy-accounting' ),
                'abas_accounts_features_callback',
                $screen
            );
        }
    } //Parts features post.
    add_action ( 'add_meta_boxes', 'abas_accounts_features' );
endif;

if ( ! function_exists( 'abas_accounts_features_callback' ) ) :
    function abas_accounts_features_callback( $post ) {
	
		wp_nonce_field ( 'abas_meta_box_nonce', 'abas_accounts_features_sub' );
		settings_errors ();

		$company_id = get_post_meta ( $post->ID, "_company_id", true );

		//Check if Store is selected or user is admin
		if(!empty ( $company_id ) ) {
			abas_current_user_company_access ( $company_id ) ;
		}
		$company_id = abas_active_company_id ();

		if( ! isset ( $company_id ) && !current_user_can ( "manage_options" ) ) {
			HEADER( "LOCATION: edit.php?post_type=abas_company") ;
		}

        $content ='';

		$content = '<table class="form-table table-style-one">';
		
		$value = get_post_meta ( $post->ID, '_account_number', true );
        
        $content .= '<tr>';
		$content .= '<td><label for="account_number">'.esc_html__( "Account Number *", "accounts-buddy-accounting" ).'</label>';		
		$content .= '<input type="text" class="regular-text" name="account_number" id="account_number" value="'.esc_attr($value). '" placeholder="e.g 10001" />';
		$content .= '</td>';

        $value = get_post_meta ( $post->ID, '_account_title', true );
					
		$content .= '<td><label for="account_title">'.esc_html__("Account Title *", "accounts-buddy-accounting" ).'</label>';		
		$content .= '<input type="text" class="regular-text" name="account_title" id="account_title" value="'.esc_attr($value). '" placeholder="e.g Salary" />';
		$content .= '</td>';
        $content .= '</tr>';
        $content .= '<tr>';

        $value = get_post_meta ( $post->ID, '_account_type', true );

        if( !empty($value) ) {
            $account_type = esc_attr($value);
			
        } else {
            $account_type = 'Select Type';
        }
					
		$content .= '<td><label for="account_type">'.esc_html__("Account Type*", "accounts-buddy-accounting").'</label>';

        $content .= '
	    	<select class="regular-text" id="account_type" name="account_type" required>
                <option selected="" value="'.esc_html($account_type).'">'.esc_html($account_type).'</option>
                <option value="asset">Assets</option>
                <option value="liability">Liability</option>
                <option value="revenue">Revenue</option>
                <option value="expense">Expense</option>
                <option value="equity">Equity</option>
            </select>        
        
        ';

		$content .= '</td>';
					
		$content .= '<td><label for="company_id">'.esc_html__( "Company Id", "accounts-buddy-accounting" ).'</label>';		
		$content .= '<input type="text" class="regular-text" name="company_id" id="company_id" value="'.esc_attr($company_id). '" readonly disabled/>';
		$content .= '</td>';
		
        $content .= '</tr>';

        $value = get_post_meta( $post->ID, '_account_description', true );
					
		$content .= '<tr><td><label for="account_description">'.esc_html__( "Description", "accounts-buddy-accounting" ).'</label>';			
		$content .= '<textarea rows="2" class="large-text" name="account_description" id="account_description" placeholder="Some words about account">'.esc_attr($value). '</textarea>';
		$content .= '</td></tr>';	

		$content .= '</table>';

		$allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
		echo wp_kses( $content, $allowedHTML );
		
	}
endif;

   	/**
	 * Save infor.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */

	function abas_account_features_save_box ( $post_id ) {
		
		if (!current_user_can ( 'manage_options' ) ) {
			wp_die ( __ ('You do not have sufficient permissions to access this page.') );
		}

		// Verify that the nonce is valid.
		if ( ! isset( $_POST['abas_accounts_features_sub'] ) || ! wp_verify_nonce( sanitize_key( $_POST['abas_accounts_features_sub']), 'abas_meta_box_nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		// Check the user's permissions.
		if ( isset ( $_POST [ 'post_type' ] ) ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	
		//Form PRocessing
		$submission_values = array(
								"account_number",
								"company_id",
								"account_title",
								"account_type",
								"account_description",
        						);
	
		foreach ( $submission_values as $submit_value ) {
			$my_value = sanitize_text_field ( $_POST [ $submit_value ] );
			update_post_meta ( $post_id, '_' .$submit_value, $my_value );
		}
	}
	add_action ( 'save_post', 'abas_account_features_save_box' ) ;

  //Add filter to show Meta Data in front end of post!
	
	add_filter ( 'manage_edit-abas_accounts_columns', 'abas_table_list_account_columns' ) ;

	function abas_table_list_account_columns ( $columns ) {
		$columns = array(
			'cb' 								=> '<input type="checkbox" />',
			'title' 							=> esc_html__( 'Company Name', 'accounts-buddy-accounting' ),
			'account_number' 		    		=> esc_html__( 'Account Number', 'accounts-buddy-accounting' ),
			'account_title' 					=> esc_html__( 'Account Title', 'accounts-buddy-accounting' ),
			'account_type' 						=> esc_html__( 'Account Type', 'accounts-buddy-accounting' ),
			'company_id' 						=> esc_html__( 'Company Id', 'accounts-buddy-accounting' ),
			'account_description' 				=> esc_html__( 'Account Description', 'accounts-buddy-accounting' ),
            'taxonomy-abas_account_groups' 		=> esc_html__( 'Groups', 'accounts-buddy-accounting' ),
		);
		return $columns;
	}

	add_action ( 'manage_abas_accounts_posts_custom_column', 'abas_table_account_list_meta_data', 10, 2 );
	function abas_table_account_list_meta_data ( $column, $post_id ) {
		global $post;
		
		switch ( $column ) {
		
			case 'account_number' :
				$account_number = get_post_meta($post_id, '_account_number', true);
				if ( isset ( $account_number ) && !empty ( $account_number ) ) {
					echo esc_html( $account_number );
				}
			break;

            case 'account_title' :
				$account_title = get_post_meta( $post_id, '_account_title', true );
				if ( isset ( $account_title ) && !empty ( $account_title ) ) {
					echo esc_html ( $account_title );
				}
			break;
			
            case 'account_type' :
				$account_type = get_post_meta($post_id, '_account_type', true);
				if ( isset ( $account_type ) && !empty ( $account_type ) ) {
					echo esc_html ( $account_type );
				}
			break;
			
			case 'company_id' :
				$company_id = get_post_meta($post_id, '_company_id', true);
				if(isset($company_id) && !empty($company_id)) {
					echo esc_html($company_id);
				}
			break;

            case 'account_description' :                
				$account_description = get_post_meta ( $post_id, '_account_description', true );
				if ( isset ( $account_description ) && !empty ( $account_description ) ) {
					echo esc_html ( $account_description );
				}
			break;

            case 'taxonomy-abas_account_groups' :
				$account_groups = get_post_meta ( $post_id, '_taxonomy-abas_account_groups', true );
				if( isset ( $account_groups ) && !empty ( $account_groups ) ) {
					echo esc_html ( $account_groups );
				}
			break;

				//Break for everything else to show default things.
			default :
				break;
		}
	}

/**
 * Update Account status query
 * @since 1.0
 * @return void
 */
if ( ! function_exists( "abas_filter_account_by_status_query" ) ):
	function abas_filter_account_by_status_query ( $query ) {
		global $pagenow;
		$type = 'abas_accounts';
		 
		if ( isset ( $_GET [ 'post_type' ] ) && $_GET [ 'post_type' ] == $type && $pagenow =='edit.php' ) {

			$queryParamsCounter = 0;

			if ( isset ( $_GET [ 'account_number' ] ) && $_GET [ 'account_number' ] !='0' ) {
				$queryParamsCounter ++ ;
				$account_number = sanitize_text_field( $_GET ['account_number'] );
				$abas_account_number = (int) $account_number;
			}

			if ( isset ( $_GET [ 'account_type' ] ) && $_GET [ 'account_type' ] !='0' ) {
				$queryParamsCounter ++ ;
				$account_type = sanitize_text_field( $_GET ['account_type'] );
				$abas_account_type = (int) $account_type;
			}

			$company_id = abas_active_company_id ();

			if ( isset ( $company_id ) ) {
				$queryParamsCounter ++;
				if ( empty ( $abas_company_id ) ) {
					$abas_company_id = (int) $company_id;
				}
			}

			$meta_query = array ();

			if ( $queryParamsCounter > 1 ) {
				$meta_query ['relation'] = 'AND';
			}

			if ( isset ($abas_company_id) ) {
				$meta_query [] 	=	array (
					'key' 		=> '_company_id',
					'value'    	=> $abas_company_id,
					'compare' 	=> '=',
					'type'    	=> 'NUMERIC',
				);
			}
			if ( isset ( $abas_account_number ) ) {
				$meta_query [] 	=	array(
					'key' 		=> '_account_number',
					'value'    	=> $abas_account_number,
					'compare' 	=> '=',
				);
			}
			if( isset ( $abas_account_type ) ) {
				$meta_query []  =	array (
					'key' 		=> '_account_type',
					'value'    	=> $abas_account_type,
					'compare' 	=> '=',
				);
			}
			$query-> set ( 'meta_query', $meta_query );
		}
	}
	add_filter( 'parse_query', 'abas_filter_account_by_status_query' );
endif;