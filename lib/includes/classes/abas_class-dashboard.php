<?php
defined( 'ABSPATH' ) || exit;

class ABAS_DASHBOARD {
	
	function output_main_page() {
		$output 	 	 = '';
		$output 		.= $this->section_navigation();

		$allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
		echo wp_kses( $output, $allowedHTML );
		
	} //Function prints the output

	function section_navigation() {
		
		$nav_items = array();

		$nav_items[] = array(
			'label' => esc_html__( 'Companies', 'accounts-buddy-accounting' ),
			'image' => 'manage-companies.png',
			'link'  => 'edit.php?post_type=abas_company',
		);

		$nav_items[] = array(
			'label' => esc_html__( 'Accounts', 'accounts-buddy-accounting' ),
			'image' => 'manage-accounts.png',
			'link'  => 'edit.php?post_type=abas_accounts',
		);

		$nav_items[] = array(
			'label' => esc_html__( 'Voucher', 'accounts-buddy-accounting' ),
			'image' => 'journal-voucher.png',
			'link'  => 'admin.php?page=abas_jvs_page',
		);

		$nav_items[] = array(
			'label' => esc_html__( 'Reports', 'accounts-buddy-accounting' ),
			'image' => 'report.png',
			'link'  => 'admin.php?page=abas_report',
		);

		$nav_items[] = array(
			'label' => esc_html__( 'Account Groups', 'accounts-buddy-accounting' ),
			'image' => 'account-groups.png',
			'link'  => 'edit-tags.php?taxonomy=abas_account_groups&post_type=abas_accounts',
		);

		$output = '<div class="wcrb_dashboard_nav wcrb_dashboard_section">';

		foreach( $nav_items as $nav_item ) {
			$output .= '<div class="wcrb_dan_item">';
			$output .= '<a href="'. esc_url( $nav_item['link'] ) .'">';
			$output .= '<img src="'. esc_url( ABAS_URL . '/assets/images/icons/' . $nav_item['image'] ) .'" />';
			$output .= '<h3>' . esc_html( $nav_item['label'] ) . '</h3>';
			$output .= '</a>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output ;

	}
}