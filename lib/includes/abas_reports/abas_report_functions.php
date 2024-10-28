<?php
defined( 'ABSPATH' ) || exit;
/**
 * Report Functions
 * 
 * Holds Various functions related to print functionality of reports
 */
if ( ! function_exists( 'abas_report_company_info' ) ): 
    function abas_report_company_info() {
        global $wpdb;
        
        $company_id 	= abas_active_company_id () ;
        if ( isset( $company_id ) && ! empty( $company_id ) ) {
                    
            $accounts_query = new WP_Query ( array ( 'post_type' => 'abas_company', 'p' => $company_id, true) );
    
            $companytitle           = 'post_title';
            $companylogo            = '_company-logo';
            $address_1              = '_address_1';
            $address_2              = '_address_2';
            $city                   = '_city';
            $state                  = '_state';
            $country                = '_country';
            $zip_code               = '_zip_code';
            $phone                  = '_phone';
            $email                  = '_email';

            $companylogo        = $accounts_query->post->$companylogo;
            $companyname        = $accounts_query->post->$companytitle;
            $address_1          = $accounts_query->post->$address_1;
            $address_2          = $accounts_query->post->$address_2;
            $city               = $accounts_query->post->$city;
            $state              = $accounts_query->post->$state;
            $country            = $accounts_query->post->$country;
            $zip_code           = $accounts_query->post->$zip_code;
            $phone              = $accounts_query->post->$phone;
            $email              = $accounts_query->post->$email;
    
            $content = '';
            $content ='<div class="company_info-hed">';
        
            if ( $image = wp_get_attachment_image_url ( $companylogo, 'Large' ) ) {			
                $content .= '<img class="company_logo" src="'.esc_url( $image).'">';
            }
            $content .='<div class="company_info_text">';
            $content .='<h2>'.$companyname.'</h2>';
            $content .='<p> <strong>Address</strong> '.$address_1.'&nbsp;'.$address_2.'&nbsp;'.$city.'&nbsp;'.$state.'&nbsp;'.$country.'&nbsp;'.$zip_code.'</p>';
            $content .='<p> <strong>Email: </strong> '.$email.'</p>';
            $content .='<p> <strong>Phone: </strong>'.$phone.'</p>';
            $content .='</div></div>';

            return $content;
        } else {
            //Ignoring empty.
        }
    }
endif;

if(!function_exists('abas_report_company_info_Voucher_info')): 
	function abas_report_company_info_Voucher_info () {
        global $wpdb, $jv_company_id_tr;
        
        if ( isset( $_GET["jv_print"] ) && ! empty( $_GET["jv_print"] ) ) {
            
            $jv_print                = sanitize_text_field( $_GET["jv_print"] );

            $voucher_result_items 		= $wpdb->prefix . 'abas_journal_voucher';            
            $voucher_result_query       = $wpdb->prepare( "SELECT * FROM `{$voucher_result_items}` WHERE `jv_id`= %s ", $jv_print );
            $voucher_result 		    = $wpdb->get_results( $voucher_result_query );

            $jv_id 				= 'jv_id';
            $jv_date 			= 'date';
            $jv_id_manual 		= 'jv_id_manual';
            $jv_title 			= 'jv_title';
            $jv_description 	= 'jv_description';
            $user_id 			= 'user_id';                      
            $jv_company_id 		= 'company_id';

            $content 			= '';

            foreach ( $voucher_result as $result ) {
               $jv_company_id_tr    = $result->$jv_company_id;               
               $company_id 	        = abas_active_company_id();
               
                if ( isset ( $company_id ) && ( $company_id ) ==  $jv_company_id_tr ) {
                    $user_info = get_userdata( $result->$user_id );

                   //$user_email = $user_info->user_email;
                   $user_name = $user_info->display_name;
                   
                   $content .='<div class="journal"><table cellpadding="5" class="wp-list-table widefat"><tbody>';
                   $content .='<tr>
                                    <th width="95px">'. esc_html__( 'Date', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( date_i18n('Y-m-d', strtotime ( $result->$jv_date ) ) ).'</td>
                                    <th width="95px">'. esc_html__( 'Manual ID', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( $result->$jv_id_manual ).'</td>
                                </tr>';
                        
                    $content .='<tr>
                                    <th>'. esc_html__( 'Posted By', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( $user_name ).'</td>
                                    <th>'. esc_html__( 'Unique ID', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( $result->$jv_id).'</td>
                                </tr>';

                    $content .='<tr>
                                    <th>'. esc_html__( 'Title', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( $result->$jv_title ).'</td>
                                    <th>'. esc_html__( 'Description', 'accounts-buddy-accounting' ).'</th>
                                    <td>'.esc_html( $result->$jv_description ).'</td>
                                </tr>';                
                    $content .='</tbody></table></div>';
               }            
            }
           return $content;	
        } else {
            //Ignoring empty.
        }
	}
endif;

if ( ! function_exists( 'abas_report_company_info_Voucher_tr' ) ) : 
	function abas_report_company_info_Voucher_tr () {
        global $wpdb, $jv_company_id_tr;
        
        if ( isset( $_GET["jv_print"] ) && ! empty( $_GET["jv_print"] ) ) {
            
            $accounts_query             = new WP_Query ( array ( 'post_type' => 'abas_company', 'p' => $jv_company_id_tr ) );
            $_currency                  = $accounts_query->post->_currency;

            $jv_print                   = sanitize_text_field( $_GET["jv_print"] );
            
            $voucher_result_items 		= $wpdb->prefix . 'abas_transactions';            
            $voucher_result_query       = $wpdb->prepare( "SELECT * FROM `{$voucher_result_items}` WHERE `jv_id`= %s ", $jv_print );
            $voucher_result 		    = $wpdb->get_results( $voucher_result_query );
            
            $result_debit_jv_total      = 0 ;
            $result_credit_jv_total     = 0 ;
            $result_balance_total       = 0 ;
            
            $content 			        = '';

            $content .='<thead><tr>
                                <th>'. esc_html__( 'Account', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Memo', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Dr.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'CR.', 'accounts-buddy-accounting' ).'</th>
                                </tr></thead><tbody>';

            $account_id 		= 'account_id';
            $memo 				= 'memo';
            $debit 				= 'debit';
            $credit 			= 'credit';
            $jv_id 				= 'jv_id';

            foreach ( $voucher_result as $result ) {

                $result_account_id_jv 		= (empty($result->account_id)) ? '' : $result->$account_id;
                $result_memo_jv 		    = (empty($result->memo)) ? '' : $result->$memo;
                $result_debit_jv 		    = (empty($result->debit)) ? 0 : (float)$result->$debit;
                $result_credit_jv 		    = (empty($result->credit)) ? 0 : (float)$result->$credit;
                $result_jv_id_jv 		    = (empty($result->jv_id)) ? '' : $result->$jv_id;            

                $result_debit_jv_total      += (int)$result_debit_jv ;
                $result_credit_jv_total     += (int)$result_credit_jv ;

                $result_balance_total       += (int)$result_debit_jv ;
                $result_balance_total       -= (int)$result_credit_jv ;
                
                if (isset ($result_jv_id_jv) && ! empty ($result_account_id_jv)) {
    				$accounts_query = new WP_Query ( array ( 'post_type' => 'abas_accounts', 'p' => $result_account_id_jv, true ) );
                }

                if (isset ($accounts_query) && ! empty ($accounts_query)) {
                    $account_name_jv		= (empty($accounts_query->post->post_title)) ? '' : $accounts_query->post->post_title;
                    $company_id 		= (empty($accounts_query->post->_company_id)) ? '' : $accounts_query->post->_company_id;
                }
 
				$active_company 	    = abas_active_company_id();

				if ( isset ( $company_id ) && ! empty($account_name_jv ) && ($company_id) ==  $active_company ) {

                    $content .='<tr class="item-row">';
                    $content .='<td class="item-name">'.esc_html( $account_name_jv ).'</td>';
                    $content .='<td class="description">'.esc_html( $result_memo_jv ).'</td>';
                    $content .='<td class="aligncenter">'.esc_html( $result_debit_jv ).'</td>';
                    $content .='<td class="aligncenter">'.esc_html( $result_credit_jv ).'</td>';
                    $content .='</tr>';

				}                                            
            }
            
            if (isset ($company_id) && ! empty ($company_id) == $result_account_id_jv) {
                $content .='<tr>';
                $content .='<td colspan="4" class="text-right">';
                $content .='<h3>Debit: <span id="debit_amnt">'.esc_html( $result_debit_jv_total ). '' .esc_html( $_currency ). '</span></h3>';
                $content .='<h3>Credit: <span id="credit_amnt">'.esc_html( $result_credit_jv_total ). '' .esc_html( $_currency ).'</span></h3>';            
                $content .='<h3>Balance: <span id="balance_amnt">'.esc_html( $result_balance_total ). '' .esc_html( $_currency ).'</span></h3>';
                $content .='</td></tr>';
            }

            return $content;

        } else {
            //Ignoring empty.
        }

	}
endif;

if(!function_exists('abas_get_gournal_voucher_result')): 
	function abas_get_gournal_voucher_result() {
				
		global $wpdb;
        
		$table_items_tr 		= $wpdb->prefix . 'abas_transactions';
        $voucher_result         = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}abas_journal_voucher`");

		$jv_id 				= 'jv_id';
		$jv_date 			= 'date';
		$jv_id_manual 		= 'jv_id_manual';
		$jv_title 			= 'jv_title';
		$jv_description 	= 'jv_description';
		$user_id 			= 'user_id';
		$jv_company_id 		= 'company_id';
		
		$content 			= '';

		foreach ( $voucher_result as $result ) {

            $jv_ids = $result->$jv_id ;

			$jv_company_id_tr 	= $result->$jv_company_id;
			$company_id 		= abas_active_company_id();
            
            $debit_jv_debit       = $wpdb->prepare( " SELECT SUM(debit) FROM `{$table_items_tr}` WHERE `jv_id`= %s ", $jv_ids );
            $debit_jv 		      = $wpdb->get_var( $debit_jv_debit );
            
            $account_ids          = $wpdb->prepare( " SELECT account_id FROM `{$table_items_tr}` WHERE `jv_id`= %s ", $jv_ids );
            $account_id 		  = $wpdb->get_var( $account_ids );

			$accounts_query = new WP_Query ( array ( 
				'post_type' 	=> 'abas_accounts', 
				'p' 			=> $account_id,
				'post_status' 	=> array( 'publish', 'private' ),
				) 
			);

			$account_name_jv		= (empty($accounts_query->post->ID)) ? '' : $accounts_query->post->ID;

			if ( isset ( $company_id ) && ! empty ($account_name_jv) && ( $company_id ) ==  $jv_company_id_tr ) {

				$accounts_query = new WP_Query ( array ( 'post_type' => 'abas_company', 'p' => $jv_company_id_tr,) );

				$_currency 		= $accounts_query->post->_currency;

				$user_info 		= get_userdata($result->$user_id);
				$user_email 	= $user_info->user_email;
				//$user_name 	= $user_info->display_name;
				
				$content .='<tr>';

				$content .= "<td class='wc_extra_code'>".esc_html( $jv_ids )."
								<div class='row-actions'>
									<span class='View'>
										<a href='admin.php?page=abas_report_print&jv_print=".esc_html($jv_ids )."' target='_blank' class='update_user_form'>
											View
										</a>
									</span>
								</div>
							</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( date_i18n('Y-m-d', strtotime ( $result->$jv_date ) ) )."</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( $result->$jv_id_manual )."</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( $result->$jv_title )."</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( $result->$jv_description )."</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( $user_email )."</td>";
				$content .= "<td class='wc_extra_code'>".esc_html ( $debit_jv ).' '.esc_html( $_currency )."</td>";
				$content .='</tr>';

			}

		} 
		return $content;		
	}
endif;

if(!function_exists('abas_get_gournal_voucher_result_by_date_range')):
	function abas_get_gournal_voucher_result_by_date_range() {				
		global $wpdb;
        
        if ( isset( $_GET["start_date"] ) && ! empty( $_GET["end_date"] ) && ! empty( $_GET["report_by_date"] ) == 'yes' ) {

            $start_date = sanitize_text_field( $_GET["start_date"] );
            $end_date = sanitize_text_field( $_GET["end_date"] );

            $table_items_tr 		= $wpdb->prefix . 'abas_transactions';
            $voucher_result_items 	= $wpdb->prefix.'abas_journal_voucher';            

            $sql_query = "SELECT * FROM `$voucher_result_items` WHERE `jv_id` AND `date` between %s and %s ORDER BY `jv_id` DESC";
            $voucher_result = $wpdb->get_results( $wpdb->prepare( $sql_query, $start_date, $end_date ));

            $jv_id 				= 'jv_id';
            $jv_date 			= 'date';
            $jv_id_manual 		= 'jv_id_manual';
            $jv_title 			= 'jv_title';
            $jv_description 	= 'jv_description';
            $user_id 			= 'user_id';
            $jv_company_id 		= 'company_id';
            
            $content 			= '';            

            $content .='<thead> <tr>
                <th>'. esc_html__( 'ID', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Date', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Manual ID.', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Title.', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Description', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Posted By.', 'accounts-buddy-accounting' ).'</th>
                <th>'. esc_html__( 'Amount.', 'accounts-buddy-accounting' ).'</th>
                </tr></thead><tbody>';

				$total_credit 	= 0;

            foreach ( $voucher_result as $result ) {

                $jv_ids = $result->$jv_id ;

                $jv_company_id_tr 	= $result->$jv_company_id;
                $company_id 		= abas_active_company_id();

                $debit_jv_debit       = $wpdb->prepare( " SELECT SUM(debit) FROM `{$table_items_tr}` WHERE `jv_id`= %s ", $jv_ids );
                $debit_jv 		      = $wpdb->get_var( $debit_jv_debit );
                
                $account_ids          = $wpdb->prepare( " SELECT account_id FROM `{$table_items_tr}` WHERE `jv_id`= %s ", $jv_ids );
                $account_id 		  = $wpdb->get_var( $account_ids );

                $result_debit_jv        = (empty($debit_jv)) ? 0 : (float)$debit_jv;

                $total_credit  += (int)$result_debit_jv ;

                $accounts_query = new WP_Query ( array ( 
                    'post_type' 	=> 'abas_accounts', 
                    'p' 			=> $account_id,
                    'post_status' 	=> array( 'publish', 'private' ),
                    )
                );

                $account_name_jv		= (empty($accounts_query->post->ID)) ? '' : $accounts_query->post->ID;

                if ( isset ( $company_id ) && ! empty ($account_name_jv) && ( $company_id ) ==  $jv_company_id_tr ) {

                    $accounts_query = new WP_Query ( array ( 'post_type' => 'abas_company', 'p' => $jv_company_id_tr,) );

                    $_currency 		= $accounts_query->post->_currency;

                    $user_info 		= get_userdata($result->$user_id);
                    //$user_email 	= $user_info->user_email;
                    $user_name 	= $user_info->display_name;
                    
                    $content .='<tr>';

                    $content .= "<td class='wc_extra_code'>".esc_html( $result->$jv_id )."
                                    <div class='row-actions'>
                                        <span class='View'>
                                            <a href='admin.php?page=abas_report_print&jv_print=".esc_html( $result->$jv_id )."' target='_blank' class='update_user_form'>
                                                View
                                            </a>
                                        </span>
                                    </div>
                                </td>";

                    $content .= "<td class='wc_extra_code'>".esc_html( date_i18n('Y-m-d', strtotime ( $result->$jv_date ) ) )."</td>";
                    $content .= "<td class='wc_extra_code'>".esc_html( $result->$jv_id_manual )."</td>";
                    $content .= "<td class='wc_extra_code'>".esc_html( $result->$jv_title )."</td>";
                    $content .= "<td class='wc_extra_code'>".esc_html( $result->$jv_description )."</td>";
                    $content .= "<td class='wc_extra_code'>".esc_html( $user_name )."</td>";
                    $content .= "<td class='wc_extra_code'>".esc_html( $result_debit_jv ).' '.esc_html( $_currency )."</td>";
                    $content .='</tr>';

                }

            }
            
            if (  $total_credit > 0 ) {
                $content .='<tr class="group_summary_footer">';
                $content .='<td colspan="6" class="text-right"><h3>'. esc_html__( 'Total Amount', 'accounts-buddy-accounting' ).'</h3></td>';
                $content .="<td><h3>".esc_html( $total_credit )."</h3></td>";
                $content .='</tr>';
            }


            return $content;
        } else {
            //Ignoring empty.
        }
	}
endif;

if(!function_exists( 'abas_get_gournal_voucher_result_by_account_groups' )):
	function abas_get_gournal_voucher_result_by_account_groups () {				        
		global $wpdb;
        
        if ( isset( $_GET["account_groups"] ) && ! empty( $_GET["account_groups"] ) && ! empty( $_GET["report_by_group"] ) == 'yes' ) {

            $account_groups     = sanitize_text_field( $_GET["account_groups"] );
            $start_date         = sanitize_text_field( $_GET["start_date"] );
            $end_date           = sanitize_text_field( $_GET["end_date"] );

            if ( isset ($account_groups) && ($account_groups) == 'all' ) {
                $accounts_args = array(
                    'post_type' => 'abas_accounts',                    
                    );
            } else {
                $accounts_args = array(
                    'post_type' => 'abas_accounts',
                    'tax_query' => array(
                        array(
                        'taxonomy' => 'abas_account_groups',
                        'field' => 'term_id',
                        'terms' => $account_groups,
                         )
                      )
                    );
            }

            $accounts_query = new WP_Query( $accounts_args );

            $content = '';
            $content .='<thead> <tr>
                                <th>'. esc_html__( 'ID', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Account #', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Title', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Type.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Opening.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Debit	', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Credit.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Closing.', 'accounts-buddy-accounting' ).'</th>
                                </tr></thead><tbody>';

            $total_op_bal = $total_debit = $total_credit = 0;

            if ( $accounts_query->have_posts() ) { 
                while( $accounts_query->have_posts() ) {

                    $accounts_query->the_post();

                    $accounts_id 		= $accounts_query->post->ID;
                    $accounts_title 	= get_the_title();

                    $company_id         = $accounts_query->post->_company_id;
                    $account_number     = $accounts_query->post->_account_number;
                    $account_type       = $accounts_query->post->_account_type;
                    
                    $active_company 	= abas_active_company_id();

                    if ( isset ( $company_id ) &&  ($company_id) ==  $active_company ) {

                        $table_items_tr 		= $wpdb->prefix . 'abas_transactions';

                        $sql_query = "SELECT SUM(debit) FROM `$table_items_tr` WHERE `account_id` = %s  AND `date` between %s and %s ORDER BY `jv_id` DESC";
                        $debit_jv = $wpdb->get_var( $wpdb->prepare( $sql_query, $accounts_id, $start_date, $end_date ));

                        $sql_query = "SELECT SUM(credit) FROM `$table_items_tr` WHERE `account_id` = %s  AND `date` between %s and %s ORDER BY `jv_id` DESC";
                        $credit_jv = $wpdb->get_var( $wpdb->prepare( $sql_query, $accounts_id, $start_date, $end_date ));

                        $result_debit_jv        = (empty($debit_jv)) ? 0 : (float)$debit_jv;
                        $result_credit_jv       = (empty($credit_jv)) ? 0 : (float)$credit_jv;

                        $total_debit  += (int)$result_debit_jv ;
                        $total_credit  += (int)$result_credit_jv ;

						//opening balance ends here.
                        $opening_balance = $result_debit_jv+$result_credit_jv;
						$total_op_bal = $opening_balance+$total_op_bal;

                        $content .='<tr>';

                        $content .= "<td class='wc_extra_code'>".esc_html( $accounts_id )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $account_number )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $accounts_title )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $account_type )."</td>";

                        $content .= "<td class='wc_extra_code'>".esc_html( $opening_balance )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $result_debit_jv )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $result_credit_jv )."</td>";
                        $content .= "<td class='wc_extra_code'>".esc_html( $debit_jv-$credit_jv )."</td>";
                        
                        $content .='</tr>';

                    }

                }
            }

                $content .="<tr class='group_summary_footer'>";
                $content .="<td colspan='4' class='text-right'> <strong>" . esc_html__( 'Total', 'accounts-buddy-accounting' )."</strong></td>";
                $content .="<td><strong>".esc_html( $total_op_bal )."</strong></td>";
                $content .="<td><strong>".esc_html( $total_debit )."</strong></td>";
                $content .="<td><strong>".esc_html( $total_credit )."</strong></td>";
                $content .="<td><strong>".esc_html( $total_debit-$total_credit )."</strong></td>";
                $content .='</tr>';

                return $content;

        } else {
            //Ignoring empty groups or groups doesn't have any accounts.
        }

	}
endif;

if(!function_exists( 'abas_get_gournal_voucher_result_by_account' )):
	function abas_get_gournal_voucher_result_by_account () {				        
		global $wpdb;
        
        if ( isset( $_GET["report_account"] ) && ! empty( $_GET["report_account"] ) && ! empty( $_GET["report_by_account"] ) == 'yes' ) {            
            $account_groups     = sanitize_text_field( $_GET["report_account"] );
            $start_date         = sanitize_text_field( $_GET["start_date"] );
            $end_date           = sanitize_text_field( $_GET["end_date"] );

            $accounts_args = array(
                'post_type' => 'abas_accounts',
                'p' => $account_groups,
                );

            $accounts_query = new WP_Query( $accounts_args );

            $content = '';

            if ($accounts_query->have_posts() ) { 
                while($accounts_query->have_posts()) {

                    $accounts_query->the_post();

                    $accounts_id 		= $accounts_query->post->ID;
                    $accounts_title 	= get_the_title();

                    $company_id         = $accounts_query->post->_company_id;
                    $account_number     = $accounts_query->post->_account_number;
                    $account_type       = $accounts_query->post->_account_type;
                    
                    $active_company 	= abas_active_company_id();

                    if ( isset ( $company_id ) &&  ($company_id) ==  $active_company ) {
                        $content .='<div class="report-period">';
                        $content .='<p> <strong>'
                                    . esc_html__( 'Account ID:', 'accounts-buddy-accounting' ). ' </strong> ' .$accounts_id. ' <strong> ' 
                                    . esc_html__( 'Account Number:', 'accounts-buddy-accounting' ). ' </strong> ' .$account_number. ' <strong> '
                                    . esc_html__( 'Account Title:', 'accounts-buddy-accounting' ). ' </strong> ' .$accounts_title. ' <strong> '
                                    . esc_html__( 'Account Type:', 'accounts-buddy-accounting' ). ' </strong> ' .$account_type.
                                    '</p>';
                        $content .='</div>';
                    }
                }
            }

            $content .='<thead> <tr>
                                <th>'. esc_html__( 'Tr ID', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'JV ID', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Date', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Memo.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Debit.', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Credit	', 'accounts-buddy-accounting' ).'</th>
                                <th>'. esc_html__( 'Balance.', 'accounts-buddy-accounting' ).'</th>
                                </tr></thead><tbody>';

            $table_items_tr 		= $wpdb->prefix . 'abas_transactions';
            
            $sql_query = "SELECT SUM(debit) FROM `$table_items_tr` WHERE `account_id` = %s  AND `date` between %s and %s ORDER BY `jv_id` DESC";
            $debit_jv = $wpdb->get_var( $wpdb->prepare( $sql_query, $accounts_id, $start_date, $end_date ));

            $sql_query = "SELECT SUM(credit) FROM `$table_items_tr` WHERE `account_id` = %s  AND `date` between %s and %s ORDER BY `jv_id` DESC";
            $credit_jv = $wpdb->get_var( $wpdb->prepare( $sql_query, $accounts_id, $start_date, $end_date ));


            $result_debit_jv 		    = (empty($debit_jv)) ? 0 : (float)$debit_jv;
            $result_credit_jv 		    = (empty($credit_jv)) ? 0 : (float)$credit_jv;
            
            //opening balance ends here.
		
            $opening_balance = $result_debit_jv+$result_credit_jv ;

            $content .= '<tr>
                            <td colspan="5" class="text-right">'. esc_html__( 'Opening balance', 'accounts-buddy-accounting' ).'</td>
                            <td colspan="2" class="text-right">'. esc_html( $opening_balance ).'</th>
                        </tr>';

            $table_items_tr 		= $wpdb->prefix . 'abas_transactions';

            $sql_query = "SELECT * FROM `$table_items_tr` WHERE `account_id` = %s  AND `date` between %s and %s ORDER BY `jv_id` ASC";
            $voucher_result = $wpdb->get_results( $wpdb->prepare( $sql_query, $accounts_id, $start_date, $end_date ));

            $tr_id 				= 'tr_id';
            $jv_id 			    = 'jv_id';
            $date 			    = 'date';
            $memo 	            = 'memo';
            $debit 			    = 'debit';
            $credit 		    = 'credit';
            
            foreach ( $voucher_result as $result ) {
                $content .="<tr>";
                $content .= "<td class='wc_extra_code'>".esc_html( $result->$tr_id )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( $result->$jv_id )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( date_i18n('Y-m-d', strtotime ( $result->$date ) ) )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( $result->$memo )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( (float)$result->$debit )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( (float)$result->$credit )."</td>";
                $content .= "<td class='wc_extra_code'>".esc_html( $result->$debit-$result->$credit)."</td>";
                $content .="</tr>";
            }
            return $content;

        } else {
            //Ignoring empty.
        }
	}
endif;