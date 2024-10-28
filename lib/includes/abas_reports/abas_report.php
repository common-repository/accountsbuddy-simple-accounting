<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'abas_report' ) ) :
function abas_report() {
    if ( ! current_user_can ( 'manage_options' ) ) {
        wp_die( esc_html__('You do not have sufficient permissions to access this page.', 'accounts-buddy-accounting' ) );
    }

?>
      
<div class="main-container accountsbuddy">
  <div class="grid-x grid-container grid-margin-x grid-padding-y" style="width:100%;">
    <div class="large-12 medium-12 small-12 cell">
      <div class="team-wrap grid-x" data-equalizer data-equalize-on="medium">
   
        <div class="cell medium-2 thebluebg sidebarmenu">
          <div class="the-brand-logo">
              <h2>Accounts Buddy Reports</h2>
          </div>

          <ul class="vertical tabs thebluebg" data-tabs="82ulyt-tabs" id="example-tabs">    
            <li class="tabs-title is-active" role="presentation">
              <a href="#main_page" role="tab" aria-controls="main_page" aria-selected="true" id="main_page-label">
                <h2><?php echo esc_html__( 'Journal Vouchers', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>

            <li class="tabs-title" role="presentation">
              <a href="#summary_groups" role="tab" aria-controls="summary_groups" aria-selected="true" id="summary_groups-label">
                <h2><?php echo esc_html__( 'Journal Summary By Groups', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>

            <li class="tabs-title" role="presentation">
              <a href="#summary_account" role="tab" aria-controls="summary_account" aria-selected="true" id="summary_account-label">
                <h2><?php echo esc_html__( 'Journal Summary by Account', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>

            <li class="tabs-title" role="presentation">
              <a href="#single_voucher" role="tab" aria-controls="single_voucher" aria-selected="true" id="single_voucher-label">
                <h2><?php echo esc_html__( 'Single Voucher', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>
            
            <li class="external-title">
              <a href="admin.php?page=abas_jvs_page">
                <h2><?php echo esc_html__( 'Add Voucher', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>

            <li class="thespacer"><hr></li>

            <li class="external-title">
              <a href="https://www.webfulcreations.com/contact-us/" target="_blank">
                <h2><span class="dashicons dashicons-buddicons-pm"></span> <?php echo esc_html__( 'Contact Us', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>

            <li class="external-title">
              <a href="https://www.facebook.com/WebfulCreations" target="_blank">
                <h2><span class="dashicons dashicons-facebook"></span> <?php echo esc_html__( 'Chat With Us', 'accounts-buddy-accounting' ); ?></h2>
              </a>
            </li>
            
          </ul>
        </div><!-- Sidebar Menu /-->
    
        <div class="cell medium-10 thewhitebg contentsideb">
          <div class="tabs-content vertical" data-tabs-content="example-tabs">
            <div class="tabs-panel team-wrap is-active" id="main_page" role="tabpanel" aria-labelledby="main_page-label">
            <?php
              $content = '';

              $content = '<div id="invoice-box" class="invoice-box">';
              $today = wp_date ( 'Y-m-d' );
              $content .= "<h2 class='text-center select-head'>".esc_html__("Select options to generate report", "accounts-buddy-accounting")."</h2>";

              $content .= "<form method='get' action=''>";

              $content .= '<div class="grid-container">';
              $content .= '<div class="grid-x grid-margin-x">';
              
              $content .= '<div class="medium-6 cell">';
              $content .= '<label>'.esc_html__("From Date", "accounts-buddy-accounting");
              $content .= '<input type="date" name="start_date" value="'.$today.'">';
              $content .= '</label>';
              $content .= '</div>';

              $content .= '<div class="medium-6 cell">';
              $content .= '<label>'.esc_html__("To Date", "accounts-buddy-accounting");
              $content .= '<input type="date" name="end_date" value="'.$today.'">';
              $content .= '</label>';
              $content .= '</div>';

              $content .= '</div></div>';
              
              $content .= '<input type="hidden" name="report_by_date" value="yes" />';
              $content .= '<input type="hidden" name="page" value="abas_report_print" />';
              $content .= '<input type="submit" class="button button-primary" value="'.esc_html__("Generate Report", "accounts-buddy-accounting").'">';

              $content .= "</form>";
                    
              $content .= '</div>';

              $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
              echo wp_kses( $content, $allowedHTML );
             ?>
              </div><!-- tabs-panel /-->
                  
              <div class="tabs-panel team-wrap" id="summary_groups" role="tabpanel" aria-labelledby="summary_groups-label">
              <?php
                $abas_accounts_get_list = abas_get_option_list_taxonomy ( 'abas_account_groups' ) ; 

                $content = '';
                $content = '<div id="invoice-box" class="invoice-box">';
                $today = wp_date ( 'Y-m-d');
                $content .= "<h2 class='text-center select-head'>".esc_html__("Summary by Account Groups", "accounts-buddy-accounting")."</h2>";
                $content .= "<form method='get' action=''>";
                $content .= '<div class="grid-container">';
                $content .= '<div class="grid-x grid-margin-x">';
                    
                $content .= '<div class="medium-6 cell">';
                $content .= '<label>'.esc_html__("From Date", "accounts-buddy-accounting");
                $content .= '<input type="date" name="start_date" value="'.$today.'">';
                $content .= '</label>';
                $content .= '</div>';

                $content .= '<div class="medium-6 cell">';
                $content .= '<label>'.esc_html__("To Date", "accounts-buddy-accounting");
                $content .= '<input type="date" name="end_date" value="'.$today.'">';
                $content .= '</label>';
                $content .= '</div>';

                $content .= '<div class="medium-6 cell">';
                $content .= '<label>'.esc_html__("Select Group" , "accounts-buddy-accounting"). '<br>';
                $content .= '<small>' .esc_html__("Leave default to fetch all groups." , "accounts-buddy-accounting").'</small> <br>';
                $content .='<select class="form-control" value="" name="account_groups" required>' .$abas_accounts_get_list. '</select>';
                $content .= '</label>';
                $content .= '</div>';

                $content .= '</div></div>';

                $content .= '<input type="hidden" name="report_by_group" value="yes" />';
                $content .= '<input type="hidden" name="page" value="abas_report_print" />';
                $content .= '<input type="submit" class="button button-primary" value="'.esc_html__("Generate Report", "accounts-buddy-accounting").'">';

                $content .= "</form>";
                
                $content .= '</div>';

                $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
                echo wp_kses( $content, $allowedHTML );
              ?>
               </div><!-- tabs-panel /-->

               <div class="tabs-panel team-wrap" id="summary_account" role="tabpanel" aria-labelledby="summary_account-label">
               <?php
                 $abas_accounts_get_list = abas_get_option_list_post_type ( 'abas_accounts' ) ; 

                  $content = '';
                  $content = '<div id="invoice-box" class="invoice-box">';
                  $content .= "<h2 class='text-center select-head'>".esc_html__("Journal Summary by Account", "accounts-buddy-accounting")."</h2>";
                  $content .= "<form method='get' action=''>";
                  $content .= '<div class="grid-container">';
                  $content .= '<div class="grid-x grid-margin-x">';
                  $content .= '<div class="medium-6 cell">';
                  $content .= '<label>'.esc_html__("From Date", "accounts-buddy-accounting");
                  $content .= '<input type="date" name="start_date" value="'.$today.'">';
                  $content .= '</label>';
                  $content .= '</div>';

                  $content .= '<div class="medium-6 cell">';
                  $content .= '<label>'.esc_html__("To Date", "accounts-buddy-accounting");
                  $content .= '<input type="date" name="end_date" value="'.$today.'">';
                  $content .= '</label>';
                  $content .= '</div>';

                  $content .= '<div class="medium-6 cell">';
                  $content .= '<label>'.esc_html__("Select Account" , "accounts-buddy-accounting"). '<br>';
                  $content .='<select class="form-control" value="" name="report_account" required>' .$abas_accounts_get_list. '</select>';
                  $content .= '</label>';
                  $content .= '</div>';

                  $content .= '</div></div>';

                  $content .= '<input type="hidden" name="report_by_account" value="yes" />';
                  $content .= '<input type="hidden" name="page" value="abas_report_print" />';
                  $content .= '<input type="submit" class="button button-primary" value="'.esc_html__("Generate Report", "accounts-buddy-accounting").'">';
                  $content .= "</form>";
                  $content .= '</div>';

                  $allowedHTML = ( function_exists( 'abas_return_allowed_tags' ) ) ? abas_return_allowed_tags() : '';
                  echo wp_kses( $content, $allowedHTML );

                  ?>
                  </div><!-- tabs-panel /-->

                  <div class="tabs-panel team-wrap" id="single_voucher" role="tabpanel" aria-labelledby="single_voucher-label">
                  <?php
                    $content = '';

                    $content = '<div id="invoice-box" class="invoice-box">';
                    $content .= "<h2 class='text-center select-head'>".esc_html__("Single  Voucher", "accounts-buddy-accounting")."</h2>";
                    
                    $content  .= '<div class="wrap" id="JvsPage">';
                    $content .= '<table class="wp-list-table widefat fixed striped users"> <thead> ';
                  
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
                    $content .= '</div>';
                    ?>

                  </div><!-- tabs-panel /-->

                </div><!-- tabs content ends -->
              </div>
    
            </div><!-- Team Wrap /-->
          </div><!-- Columns /-->
        </div><!-- Grid Container /-->
      </div><!-- Main Container /-->
<?php
}
endif;