<?php
defined( 'ABSPATH' ) || exit;
add_action ( 'abas_settings_page_tab', 'abas_settings_page', 10, 2 );

if(!function_exists("abas_settings_page")):    
  function abas_settings_page () {

    if ( !current_user_can ( 'manage_options' ) ) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $abas_menu_name_a 		= get_option ( "abas_main_menu_name" );
    //Processing Logo
    $abas_accounting_logo = get_option ( "abas_accounting_logo" );
    if ( empty ( $abas_accounting_logo ) ) {
      $custom_logo_id 		  = get_theme_mod ( 'custom_logo' );
      $image 					      = wp_get_attachment_image_src ( $custom_logo_id , 'full' );
      $abas_accounting_logo	= $image [0];	
    }
    $abas_accounting_email = get_option ( "abas_accounting_email" );
    if( empty ( $abas_accounting_email ) ) {
      $abas_accounting_email	= get_option ("admin_email");	
    }

    $content ='';

    $content .= '<div class="main-wrap">';
    $content .= '<div class="page-title">';
    $content .= '<h2>'.esc_html__( 'WordPress Accounting', 'accounts-buddy-accounting' ).'</h2>';
    $content .= '</div>';
    $content .= '<form method="post">';
    
    $content .= wp_nonce_field( 'abas_meta_settings_nonce', 'abas_settings_sub' );

    $content .= '<table cellpadding="5" cellspacing="5" class="form-table">';     
    $content .= '
                <tr>
                <th scope="row">
                  <label for="abas_menu_name">'.esc_html__("Menu Name e.g WordPress Accounting", "accounts-buddy-accounting").'</label>
                </th>
                <td>
                  <input 
                    name="abas_menu_name" 
                    id="abas_menu_name" 
                    class="regular-text" 
                    value="'.esc_html($abas_menu_name_a).'" 
                    type="text" 
                    placeholder="'.esc_html__("Enter Menu Name Default WordPress Accounting", "accounts-buddy-accounting").'"/>
                </td>
              </tr> ';


    $content .= '
                <tr>
                  <th scope="row">
                    <label for="abas_accounting_logo">'.esc_html__("Logo to use", "accounts-buddy-accounting").'</label>
                  </th>
                  <td>
                    <input 
                      name="abas_accounting_logo" 
                      id="abas_accounting_logo" 
                      class="regular-text" 
                      value="'.esc_url($abas_accounting_logo).'" 
                      type="text" 
                      placeholder="'.esc_html__("Enter url of logo", "accounts-buddy-accounting").'"/>
                  </td>
                </tr> ';

    $content .= '
                <tr>
                  <th scope="row">
                    <label for="abas_accounting_email">'.esc_html__("Email", "accounts-buddy-accounting").
                    '<small>'.esc_html__("Where quote forms and other admin emails would be sent.", "accounts-buddy-accounting").'</small></label>
                  </th>
                  <td>
                    <input 
                      name="abas_accounting_email" 
                      id="abas_accounting_email" 
                      class="regular-text" 
                      value="'.esc_html($abas_accounting_email).'" 
                      type="text" 
                      placeholder="'.esc_html__("Where to send emails like Quote and other stuff.", "accounts-buddy-accounting").'"/>
                  </td>
                </tr> ';
    $content .= ' <input type="hidden" name=" abas_version_settings" value="1" /> ';          
    $content .= '
                <tr>
                  <td>
                    <input 
                      class="button button-primary" 
                      type="Submit"  
                      value="'.esc_html__("Save Changes", "accounts-buddy-accounting").'"/>
                  </td>
                </tr> ';
    $content .= '</table> </form> </div>';
    $content .= '';

    $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
    echo wp_kses( $content, $allowedHTML );

  }
endif;
  
//Function to save data. 
if ( ! function_exists( 'abas_version_settings_submission' ) ) :
  function abas_version_settings_submission() {
    global $wpdb; //to use database functions inside function.

    // Verify that the nonce is valid.
    if ( ! isset( $_POST['abas_settings_sub'] ) || ! wp_verify_nonce ( sanitize_key($_POST['abas_settings_sub']), 'abas_meta_settings_nonce' ) ) {
      return;
    }

    if ( isset ( $_POST ['abas_version_settings'] ) && sanitize_text_field ( $_POST ['abas_version_settings'] ) == '1') {
        
      $abas_menu_name	        = ( ! isset( $_POST['abas_menu_name'] ) ) ? '' : sanitize_text_field( $_POST['abas_menu_name'] );
      $abas_accounting_logo	  = ( ! isset( $_POST['abas_accounting_logo'] ) ) ? '' : sanitize_url( $_POST['abas_accounting_logo'] );
      $abas_accounting_email	  = ( ! isset( $_POST['abas_accounting_email'] ) ) ? '' : sanitize_email( $_POST['abas_accounting_email'] );
  
      update_option ('abas_main_menu_name', $abas_menu_name );
      update_option ("abas_accounting_logo", $abas_accounting_logo );
      update_option ("abas_accounting_email", $abas_accounting_email );

      //Show message.
      add_action    ("admin_notices", "abas_main_settings_saved" );

    }

  }//End of abas_version_settings_submission()

  add_action( 'admin_init', 'abas_version_settings_submission' );

endif;

if ( !function_exists ("abas_main_settings_saved") ):
  function abas_main_settings_saved () {
    $content = '<div class="updated">';
    $content .= '<p>'.esc_html__("Settings saved!", "accounts-buddy-accounting").'</p>';
    $content .= '</div>';

    $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
		echo wp_kses( $content, $allowedHTML );    
  }
endif;