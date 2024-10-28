<?php
defined( 'ABSPATH' ) || exit;

function abas_jvs_page () {
  if ( !current_user_can ( 'manage_options' ) ) {
    wp_die( esc_html__('You do not have sufficient permissions to access this page.', 'accounts-buddy-accounting' ) );
  }

  $content = '';
  /***
   * Journal Voucher.
  */
  $content  = '<div class="wrap" id="JvsPage">';
  $content .= '<h2 class="wp-heading-inline mb4">' . esc_html__( 'Journal Voucher', 'accounts-buddy-accounting' ).'</h2>';
  $content .= '<button id="OpenModal" class="page-title-action mb4">' . esc_html__( 'Add Voucher', 'accounts-buddy-accounting' ) . ' </button> </div>';
  $content .= '<div class="form-update-message"></div>' ;

  $content .= '<table class="wp-list-table widefat fixed striped users"><thead> ';

  $content .= '<tr> <th class="manage-column column-id">';
  $content .= '<span>' . esc_html__("ID", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-title"> <span>' .esc_html__("Date", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-description"> <span>' .esc_html__("Manual ID", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-postedby"> <span>' .esc_html__("Title", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-amount"> <span>' .esc_html__("Description", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-action"> <span>' .esc_html__("Posted By", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '<th class="manage-column column-amount"> <span>' .esc_html__("Amount", "accounts-buddy-accounting"). '</span> </th>';
  $content .= '</tr></thead>';

  $content .= '<tbody id="reloadpage">';
  $content .= abas_get_gournal_voucher_result();
  $content .= '</tbody>';

  $content .= '<tfoot> <tr>';
  $content .= '<th class="manage-column column-id"><span>' .esc_html__("ID", "accounts-buddy-accounting"). '</span></th>'; 
  $content .= '<th class="manage-column column-title"> <span>' .esc_html__("Date", "accounts-buddy-accounting"). '</span></th>';
  $content .= '<th class="manage-column column-description"><span>' .esc_html__("Manual ID", "accounts-buddy-accounting"). '</span></th>';
  $content .= '<th class="manage-column column-postedby"><span>' .esc_html__("Title", "accounts-buddy-accounting"). '</span></th>';
  $content .= '<th class="manage-column column-amount"><span>' .esc_html__("Description", "accounts-buddy-accounting"). '</span></th>';
  $content .= '<th class="manage-column column-action"><span>' .esc_html__("Posted By", "accounts-buddy-accounting"). '</span></th>';
  $content .= '<th class="manage-column column-amount"><span>' .esc_html__("Amount", "accounts-buddy-accounting"). '</span></th>';
  $content .= '</tr></tfoot>';
  $content .= '</table><!-- Wp List Table/-->';

  $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
  echo wp_kses( $content, $allowedHTML );
}

/***
 * Add Journal Voucher.
*/
function abas_add_jv () {

  $content ='';

  $jv_date = wp_date ( 'Y-m-d');

  $content = '<div class="wc-ac-modal wrap" id="WcAcModalBody">';
  $content .='<div class="wc-ac-modal-header">';

  $content .='<h2 class="wp-heading-inline">' .esc_html__("Add Voucher", "accounts-buddy-accounting"). '</h2>';
  $content .='<button role="button" id="WcAcCancelModal">X</button> </div>';

  $content .='<div class="mh-vh-80 wc-ac-modal-content"><div class="add-voucher-submit-message primary button"></div>';
  $content .='<form data-async method="post" data-print-reply=".add-voucher-submit-message">';

  $content .= wp_nonce_field( 'abas_meta_add_voucher_nonce', 'abas_add_voucher_sub' );

  $content .='<table class="form-table table-style-one"><tbody>';
  $content .='<tr><td>';
  $content .='<label for="jv_date">' .esc_html__("Date * ", "accounts-buddy-accounting"). '</label>';
  $content .='<input id="jv_date" class="regular-text" type="date" name="jv_date" value="' . esc_html( $jv_date ) . '" required>';
  $content .='</td><td>';
  $content .='<label for="jv_manual_id">' .esc_html__("Manual ID", "accounts-buddy-accounting"). '</label>'; 
  $content .='<input id="jv_manual_id" class="regular-text" placeholder="' .esc_html__("Manual ID e.g 1234343", "accounts-buddy-accounting"). '" type="text" name="jv_manual_id">';
  $content .='</td></tr>';
  $content .='<tr><td>';
  $content .='<label for="jv_title">' .esc_html__("JV Title *", "accounts-buddy-accounting"). '</label>';
  $content .='<input id="jv_title" class="regular-text" type="text" placeholder="' .esc_html__("Voucher Title", "accounts-buddy-accounting").'" name="jv_title" required>';
  $content .='</td><td>';
  $content .='<label for="jv_description">Description</label>';
  $content .='<input id="jv_description" class="regular-text" placeholder="' .esc_html__("Some words about voucher...", "accounts-buddy-accounting"). '" type="text" name="jv_description">';
  $content .='</td></tr></tbody></table>';

  $content .='<div class="transactions-box" id="ab_data">';
  $content .='<h2 class="wp-wp-heading-inline">' .esc_html__("Transactions", "accounts-buddy-accounting"). '</h2>';

  $content .='<table id="RepeatableFieldJv" class="wp-list-table widefat mb4">';
  $content .='<tbody><tr>';
  $content .='<th>' .esc_html__( 'Account', "accounts-buddy-accounting"). '</th>';
  $content .='<th>' .esc_html__( 'Memo', "accounts-buddy-accounting"). '</th>';
  $content .='<th style="width:110px;">' .esc_html__( 'DR.', 'accounts-buddy-accounting' ). '</th>';
  $content .='<th style="width:110px;">' .esc_html__( 'CR.', 'accounts-buddy-accounting' ). '</th>';
  $content .='</tr><tr class="item-row repeatable-btn-hide repeatable-row repeatable-row-add">';
  $content .='<td class="item-name"><div class="delete-wpr">';
  
  $abas_accounts_get_list = abas_get_option_list_post_type ('abas_accounts');

  if ( isset ( $abas_accounts_get_list ) && ! empty ($abas_accounts_get_list) ) {
    $accounts_get_list = abas_get_option_list_post_type ('abas_accounts');
  } else {
    $accounts_get_list = esc_html__( 'Add Accounts First', 'accounts-buddy-accounting' );
  }
  
  $content .='<select class="form-control" name="account_id[]" required>' .$accounts_get_list. '</select>';
  $content .='</div></td>';
  $content .='<td class="description"><input type="text" class="form-control" name="memo[]" placeholder="' .esc_html__("Transaction Memo", "accounts-buddy-accounting"). '"></td>';
  $content .='<td><input type="number" class="form-control dr DrAmnt" name="dr[]" placeholder="' .esc_html__("0.00", "accounts-buddy-accounting"). '" min="1"></td>';
  $content .='<td><input type="number" class="form-control cr CrAmnt" name="cr[]" placeholder="' .esc_html__("0.00", "accounts-buddy-accounting"). '" min="1"></td>';
  $content .='<td><a class="button remove-row" href="#">' .esc_html__("x", "accounts-buddy-accounting"). '</a></td>';
  $content .='</tr><tr>';
  $content .='<td colspan="4"><a id="add-row" href="#">' .esc_html__("New Row", "accounts-buddy-accounting"). '</a></td>';
  $content .='</tr><tr>';

  $content .= '<td colspan="2" class="jv_error_alert" id="JvErrorAlert">
          <h4 class="jv_error_alert_text jv_error_alert1"> ' .esc_html__("At least 1 debit amount is required", "accounts-buddy-accounting"). '</h4>
          <h4 class="jv_error_alert_text jv_error_alert2"> ' .esc_html__("At least 1 credit amount is required", "accounts-buddy-accounting"). '</h4>
          <h4 class="jv_error_alert_text jv_error_alert3"> ' .esc_html__("Debit and Credit is not equal", "accounts-buddy-accounting"). '</h4>
          <h4 class="jv_error_alert_text jv_error_alert4"> ' .esc_html__("Please enter number.", "accounts-buddy-accounting"). '</h4>
          <h4 class="jv_error_alert_text jv_error_alert5"> ' .esc_html__("Debit and credit cannot be part of 1 transaction.", "accounts-buddy-accounting"). '</h4>
          <h4 class="jv_error_alert_text jv_error_alert6"> ' .esc_html__("Please delete empty row.", "accounts-buddy-accounting"). '</h4>
          </td>'; 

  $content .='<td colspan="4" align="right">';
  $content .='<p><b> Debit:</b> <b id="debit_amnt">0.00</b><p>';
  $content .='<p><b>Credit:</b> <b id="credit_amnt">0.00</b><p>';
  $content .='<p><b>Balance:</b> <b id="balance_amnt">0.00</b><p>';
  $content .='</td></tr>';
  $content .='</tbody></table></div>';

  $content .='<input name="add_jv_form_tr_add[]" type="hidden" value="add_jv_form_tr_add" />';
  $content .='<input name="form_type" type="hidden" value="add_jv_form_text" />';
  $content .='<input type="submit" id="submit" value="' .esc_html__("Add Voucher", "accounts-buddy-accounting"). '">';
  $content .='</form></div></div>';

  $content .='<div class="wc-ac-modal-overlay" id="WcAcModalOverlay"></div>';

  $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
  echo wp_kses( $content, $allowedHTML );
}
add_action ( 'abas_add_jv', 'abas_add_jv' );