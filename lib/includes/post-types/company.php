<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'abas_company_post_type_init' ) ) :
	function abas_company_post_type_init() {
		$labels = array(
			'add_new_item' 			=> esc_html__( 'Add company', 'accounts-buddy-accounting' ),
			'singular_name' 		=> esc_html__( 'Company', 'accounts-buddy-accounting' ), 
			'menu_name' 			=> esc_html__( 'Companies', 'accounts-buddy-accounting' ),
			'all_items' 			=> esc_html__( 'Companies', 'accounts-buddy-accounting' ),
			'edit_item' 			=> esc_html__( 'Edit Company', 'accounts-buddy-accounting' ),
			'new_item' 				=> esc_html__( 'New Company', 'accounts-buddy-accounting' ),
			'view_item' 			=> esc_html__( 'View Company', 'accounts-buddy-accounting' ),
			'search_items' 			=> esc_html__( 'Search Company', 'accounts-buddy-accounting' ),
			'not_found' 			=> esc_html__( 'No compnay Related to your search', 'accounts-buddy-accounting' ),
			'not_found_in_trash' 	=> esc_html__( 'No company in trash', 'accounts-buddy-accounting' )
		);
		$args = array(
			'labels'             	=> $labels,
			'label'					=> esc_html__( 'Manage Companies', 'accounts-buddy-accounting' ),
			'description'        	=> esc_html__( 'Admin can access all companies while other users can only access companies they have access to.', 'accounts-buddy-accounting' ),
			'supports' 				=> array ( 'title' ),
			'public'             	=> false,
			'publicly_queryable' 	=> false,
			'show_ui'            	=> true,
			'show_in_menu'       	=> false,
			'query_var'          	=> true,
			'rewrite'            	=> array('slug' => 'company' ),
			'capability_type'    	=> 'post',
			'has_archive'        	=> true,
			'menu_icon'			 	=> 'dashicons-clipboard',
			'menu_position'      	=> 30,
			'register_meta_box_cb' 	=> 'abas_company_features',
		);
		register_post_type ( 'abas_company', $args );
	}
	add_action ( 'init', 'abas_company_post_type_init' );
endif;
	
//registeration of post type ends here.
if ( ! function_exists( 'abas_company_features' ) ) :
	function abas_company_features() { 
		$screens = array ( 'abas_company' );

		foreach ( $screens as $screen ) {
			add_meta_box(
				'myplugin_sectionid',
				esc_html__ ( "Company Details", "accounts-buddy-accounting" ),
				'abas_companys_features_callback',
				$screen
			);
		}
	} //Parts features post.
	add_action( 'add_meta_boxes', 'abas_company_features' );
endif;

if ( ! function_exists( 'abas_companys_features_callback' ) ) :
	function abas_companys_features_callback( $post ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'accounts-buddy-accounting' ) );
		}
	
		wp_nonce_field ( 'abas_meta_box_nonce', 'abas_companys_features_sub' );
		settings_errors ();

		$content = '';
		$content = '<table class="form-table table-style-one">';
		$content .= '<tr><td>';
		$image_id = get_post_meta ( $post->ID, '_company-logo', true );

		$content .= '<div class="company-logo-wrap">';

			if( $image = wp_get_attachment_image_url ( $image_id, 'Large'  ) ) {			
				$content .= '<a href="#" class="company-logo-upload"> <img class="logo-medium" src="'.esc_url($image).'" /> </a>';
				$content .= '	<a href="#" class="company-logo-remove">' . esc_html__( 'Remove Company Logo', 'accounts-buddy-accounting' ) . '</a>';
				$content .= '<input type="hidden" name="company-logo" value="'. esc_attr( $image_id ) .'">';
			} else {
				$content .= '<a href="#" class="button company-logo-upload">' . esc_html__( 'Add Company Logo', 'accounts-buddy-accounting' ) . '</a>';
				$content .= '<a href="#" class="company-logo-remove" style="display:none">' . esc_html__( 'Remove Company Logo', 'accounts-buddy-accounting' ) . '</a>';
				$content .= '<input type="hidden" name="company-logo" value="">';
			}
			$content .= '</div></td></tr>';
		
		$value = get_post_meta ( $post->ID, '_company_manual_id', true );
					
		$content .= '<tr><td><label for="company_manual_id">'.esc_html__("Manual ID * Manual ID an identifier", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="company_manual_id" id="company_manual_id" value="'.esc_attr($value). '" placeholder="Enter Manual ID e.g 1234DF" />';
		$content .= '</td>';
		
		$value = get_post_meta ( $post->ID, '_business_type', true );
					
		$content .= '<td><label for="business_type">'.esc_html__("Business Type", "accounts-buddy-accounting").'</label>';	
		$content .= '<input type="text" class="regular-text" name="business_type" id="business_type" value="'.esc_attr($value). '" placeholder="Company business type" />';
		$content .= '</td></tr>';
		
		$value = get_post_meta ( $post->ID, '_address_1', true );
					
		$content .= '<tr><td><label for="address_1">'.esc_html__("Address 1", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="address_1" id="address_1" value="'.esc_attr($value). '" placeholder="Address line 1" />';
		$content .= '</td>';
		
		$value = get_post_meta ( $post->ID, '_address_2', true );
					
		$content .= '<td><label for="address_2">'.esc_html__("Address 2", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="address_2" id="address_2" value="'.esc_attr($value). '" placeholder="Address line 2" />';
		$content .= '</td></tr>';
		
		$value = get_post_meta ( $post->ID, '_city', true );
					
		$content .= '<tr><td><label for="city">'.esc_html__("city", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="city" id="city" value="'.esc_attr($value). '" />';
		$content .= '</td>';
		
		$value = get_post_meta ( $post->ID, '_state', true );
					
		$content .= '<td><label for="state">'.esc_html__("State", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="state" id="state" value="'.esc_attr($value). '" />';
		$content .= '</td></tr>';

		$value = get_post_meta ( $post->ID, '_country', true );
					
		$content .= '<tr><td><label for="country">'.esc_html__("Country", "accounts-buddy-accounting").'</label>';
		$content .= '<input type="text" class="regular-text" name="country" id="country" value="'.esc_attr($value). '" />';
		$content .= '</td>';
		
		$value = get_post_meta ( $post->ID, '_zip_code', true );
					
		$content .= '<td><label for="zip_code">'.esc_html__("Zip Code", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="zip_code" id="zip_code" value="'.esc_attr($value). '" />';
		$content .= '</td></tr>';

		$value = get_post_meta ( $post->ID, '_phone', true );
					
		$content .= '<tr><td><label for="phone">'.esc_html__("Company Phone", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="tel" class="regular-text" name="phone" id="phone" value="'.esc_attr($value). '" placeholder="Company Phone" />';
		$content .= '</td>';
		
		$value = get_post_meta ( $post->ID, '_email', true );
					
		$content .= '<td><label for="email">'.esc_html__("Company Email", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="email" class="regular-text" name="email" id="email" value="'.esc_attr($value). '" placeholder="Company Email" />';
		$content .= '</td></tr>';
		
		$value = get_post_meta ( $post->ID, '_currency', true );
					
		$content .= '<tr><td><label for="currency">'.esc_html__("Currency Symbol e.g USD or $", "accounts-buddy-accounting").'</label>';		
		$content .= '<input type="text" class="regular-text" name="currency" id="currency" value="'.esc_attr($value). '" placeholder="Currency Symbol e.g USD or $" />';
		$content .= '</td>';
		$value = get_post_meta( $post->ID, '_about_company', true );
					
		$content .= '<td><label for="about_company">'.esc_html__("About Company", "accounts-buddy-accounting").'</label>';			
		$content .= '<textarea rows="2" class="regular-text" name="about_company" id="about_company" placeholder="Some words about company">'.esc_attr($value). '</textarea>';
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
	function abas_companys_features_save_box ( $post_id ) {
		
		if ( !current_user_can ( 'manage_options' ) ) {
			wp_die ( __ ( 'You do not have sufficient permissions to access this page.' ) );
		}

		// Verify that the nonce is valid.
		if ( ! isset( $_POST['abas_companys_features_sub'] ) || ! wp_verify_nonce( sanitize_key( $_POST['abas_companys_features_sub']), 'abas_meta_box_nonce' ) ) {
			return;
		}
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined ('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}
	
		// Check the user's permissions.
		if ( isset ( $_POST['post_type'] ) ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	
		//Form PRocessing
		$submission_values = array(
								"company-logo",
								"company_manual_id",
								"business_type",
								"address_1",
								"address_2",
								"city",
								"state",
								"country",
								"zip_code",
								"phone",
								"email",
								"currency",
								"about_company",
							);
	
		foreach ( $submission_values as $submit_value ) {
			$my_value = sanitize_text_field ( $_POST [$submit_value] );
			update_post_meta( $post_id, '_' .$submit_value, $my_value );
		}
	}
	add_action ( 'save_post', 'abas_companys_features_save_box' );

	//Add filter to show Meta Data in front end of post!
	
	add_filter ( 'manage_edit-abas_company_columns', 'abas_table_list_company_columns' ) ;

	function abas_table_list_company_columns ( $columns ) {
		$columns = array(
			'cb' 						=> '<input type="checkbox" />',
			'title' 					=> esc_html__('company Name', "accounts-buddy-accounting"),
			'company_manual_id' 		=> esc_html__('Company Manual Id', "accounts-buddy-accounting"),
			'business_type' 			=> esc_html__('Business Type', "accounts-buddy-accounting"),
			'city' 						=> esc_html__('City', "accounts-buddy-accounting"),
			'phone' 					=> esc_html__('Phone', "accounts-buddy-accounting"),
			'email' 					=> esc_html__('Email', "accounts-buddy-accounting"),
			'logo' 						=> esc_html__('Logo', "accounts-buddy-accounting"),
			'currency' 					=> esc_html__('currency', "accounts-buddy-accounting"),
			'accounts' 					=> esc_html__('accounts', "accounts-buddy-accounting"),
		);
		return $columns;
	}

	add_action ( 'manage_abas_company_posts_custom_column', 'abas_table_company_list_meta_data', 10, 2 );

	function abas_table_company_list_meta_data ($column, $post_id ) {
		global $post;
		
		switch ( $column ) {

			case 'company_manual_id' :
				$company_manual_id = get_post_meta ( $post_id, '_company_manual_id', true );
				if ( isset ( $company_manual_id ) && !empty ( $company_manual_id ) ) {
					echo esc_html ( $company_manual_id );
				}
			break;
			
			case 'business_type' :
				$business_type = get_post_meta ( $post_id, '_business_type', true );
				if( isset ( $business_type ) && !empty ( $business_type ) ) {
					echo esc_html($business_type);
				}
			break;

			case 'city' :
				$city = get_post_meta ( $post_id, '_city', true );
				if(isset ($city) && !empty ($city) ) {
					echo esc_html($city);
				}
			break;
	
			case 'phone' :
				$phone = get_post_meta ( $post_id, '_phone', true );
				if ( isset ( $phone ) && !empty ( $phone ) ) {
					echo esc_html($phone);
				}
			break;
	
			case 'email' :
				$email = get_post_meta ( $post_id, '_email', true );
				if ( isset ( $email ) && !empty ( $email ) ) {
					echo esc_html($email);
				}
			break;
			
			case 'logo' :
				$image_id = get_post_meta ( $post->ID, '_company-logo', true );			
				if ( $image = wp_get_attachment_image_url ( $image_id, 'thumbnail' ) ) {			
					echo '<img src="'.esc_url( $image).'" />';
				}
			break;
	
			case 'currency' :
				$currency = get_post_meta ( $post_id, '_currency', true );
				if ( isset ( $currency ) && !empty ( $currency ) ) {
					echo esc_html ( $currency );
				}
			break;
	
			case 'accounts' :

				$company_id = abas_active_company_id ();

				$arr_params = array ( 'company_select' => $post_id, 'company_selection' => 'true' );
				
				if ( isset ( $company_id ) && ( $company_id ) == $post_id ) {
					$company_select_btn = '<a href="#" disabled class="button" title="">'.esc_html__("Selected Company", "accounts-buddy-accounting").'</a>';
				} else {
				 	$company_select_btn = '<a href="'.esc_url( add_query_arg( $arr_params ) ).'" class="button" title="'.esc_html__("Please select a Company", "accounts-buddy-accounting").'">'.esc_html__("Select", "accounts-buddy-accounting").'</a>';
				}				

				$allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
				echo wp_kses( $company_select_btn, $allowedHTML );				

			break;
			
				//Break for everything else to show default things.
			default :
				break;
		}
	}