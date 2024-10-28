<?php
defined( 'ABSPATH' ) || exit;

//Installation of plugin starts here.
if ( ! function_exists( 'abas_report_print_functionality' ) ) :
    function abas_report_print_functionality () {

        if ( ! current_user_can( 'read' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'accounts-buddy-accounting' ) );
        }

        $today = wp_date ( 'Y-m-d');

        $content = '';

        $content .= '<div id="reportprint" class="report-wrap reportprint">';
        $content .= '<div class="report-wrap-hed"><h2 class="report-wrap-border">'. esc_html__( 'Reports Accounts Buddy', 'accounts-buddy-accounting' ).'</h2></div>';
        $content .= abas_report_company_info();
        $content .= abas_report_company_info_Voucher_info ();
        $content .='<div class="transactions">';

        if ( isset( $_GET["start_date"] ) && ! empty( $_GET["end_date"] ) ) {
            $start_date = sanitize_text_field( $_GET["start_date"] );
            $end_date = sanitize_text_field( $_GET["end_date"] );
            $content .='<div class="report-period">';
            $content .='<h2>' . esc_html__( 'Today:', 'accounts-buddy-accounting' ). ' ' .$today. ' ' . esc_html__( 'Statement Period:', 'accounts-buddy-accounting' ). ' ' .$start_date. ' - ' .$end_date. '</h2>';
            $content .='</div>';                
        }
        
        if ( isset( $_GET["jv_print"] ) && ! empty( $_GET["jv_print"] ) ) {
            $content .='<h3>'. esc_html__( 'Transactions', 'accounts-buddy-accounting' ).'</h3>';
        } elseif ( isset( $_GET["start_date"] ) && ! empty( $_GET["end_date"] ) && ! empty( $_GET["report_by_date"] ) == 'yes' ) {
            $content .='<h3>'. esc_html__( 'Journal Vouchers', 'accounts-buddy-accounting' ).'</h3>';
        } elseif ( isset( $_GET["account_groups"] ) && ! empty( $_GET["account_groups"] ) && ! empty( $_GET["report_by_group"] ) == 'yes' ) {
            $content .='<h3>'. esc_html__( 'Accounts Summary', 'accounts-buddy-accounting' ).'</h3>';
        }
        elseif ( isset( $_GET["report_account"] ) && ! empty( $_GET["report_account"] ) && ! empty( $_GET["report_by_account"] ) == 'yes' ) {
            $content .='<h3>'. esc_html__( 'Accounts Transactions Summary', 'accounts-buddy-accounting' ).'</h3>';
        }

        $content .='<table id="items" class="wp-list-table widefat fixed striped users">';

        $content .= abas_report_company_info_Voucher_tr ();
        $content .= abas_get_gournal_voucher_result_by_date_range ();
        $content .= abas_get_gournal_voucher_result_by_account_groups ();
        $content .= abas_get_gournal_voucher_result_by_account ();

        $content .='</tbody></table>';
        $content .='<p class="text-center">'. esc_html__( 'This is computer generated statement does not need signature.', 'accounts-buddy-accounting' ).'</p>';
        $content .='</div>';
        $content .= '<button id="btnPrint" class="hidden-print button button-primary">'.esc_html__("Print", "accounts-buddy-accounting").'</button>';
        $content .='</div>';

        $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
		echo wp_kses( $content, $allowedHTML );
    }
endif;