<?php
defined( 'ABSPATH' ) || exit;

function abas_dashboard_page () {
  if ( ! current_user_can( 'manage_options' ) ) {
    wp_die ( esc_html__( 'You do not have sufficient permissions to access this page.', 'accounts-buddy-accounting' ) );
  }
?>
  <div class="main-container accountsbuddy">
    <div class="grid-x grid-container grid-margin-x grid-padding-y" style="width:100%;">
      <div class="large-12 medium-12 small-12 cell">
        <div class="team-wrap grid-x" data-equalizer data-equalize-on="medium">

          <div class="cell medium-2 thebluebg sidebarmenu">
            <div class="the-brand-logo">
              <a href="https://www.webfulcreations.com/contact-us/" target="_blank">
                <img src="<?php echo esc_url( ABAS_URL . '/assets/images/accounts-buddy-logo.jpg' ); ?>" alt="Accounts Buddy Logo" />
              </a>
            </div>
            <ul class="vertical tabs thebluebg" data-tabs="82ulyt-tabs" id="example-tabs">
              <li class="tabs-title is-active" role="presentation">
                <a href="#main_page" role="tab" aria-controls="main_page" aria-selected="true" id="main_page-label">
                  <h2><?php echo esc_html__( 'Dashboard', 'accounts-buddy-accounting' ); ?></h2>
                </a>
              </li>
              <li class="tabs-title" role="presentation">
                <a href="#panel1" role="tab" aria-controls="panel1" aria-selected="false" id="panel1-label">
                  <h2><?php echo esc_html__( 'General Settings', 'accounts-buddy-accounting' ); ?></h2>
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
                  $MAINPAGEOUTPUT = new ABAS_DASHBOARD;
                  $MAINPAGEOUTPUT->output_main_page(); 
                ?>
              </div><!-- tabs-panel /-->

              <div class="tabs-panel team-wrap" id="panel1" role="tabpanel" aria-labelledby="panel1-label">
                <?php do_action ( 'abas_settings_page_tab' ) ; ?>
              </div><!-- tabs-panel /-->

            </div><!-- tabs content ends -->
          </div>

        </div><!-- Team Wrap /-->
      </div><!-- Columns /-->
    </div><!-- Grid Container /-->
  </div><!-- Main Container /-->

<?php }