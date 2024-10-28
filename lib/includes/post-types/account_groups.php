<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists ( 'abas_account_groups_taxonomy_init' ) ) :
  function abas_account_groups_taxonomy_init () {
    $labels = array(
        'name'                =>  esc_html__( 'Account Groups', 'accounts-buddy-accounting' ),
        'singular_name'       =>  esc_html__( 'Subject', 'accounts-buddy-accounting' ),
        'search_items'        =>  esc_html__( 'Search Account Groups', 'accounts-buddy-accounting' ),
        'all_items'           =>  esc_html__( 'All Account Groups', 'accounts-buddy-accounting' ),
        'parent_item'         =>  esc_html__( 'Parent Account Groups', 'accounts-buddy-accounting' ),
        'parent_item_colon'   =>  esc_html__( 'Parent Account Groups', 'accounts-buddy-accounting' ),
        'edit_item'           =>  esc_html__( 'Edit Account Groups', 'accounts-buddy-accounting' ), 
        'update_item'         =>  esc_html__( 'Update Account Groups', 'accounts-buddy-accounting' ),
        'add_new_item'        =>  esc_html__( 'Add New Account Groups', 'accounts-buddy-accounting' ),
        'new_item_name'       =>  esc_html__( 'New Account Groups Name', 'accounts-buddy-accounting' ),
        'menu_name'           =>  esc_html__( 'Account Groups', 'accounts-buddy-accounting' ),
    );    
    // Now register the taxonomy
    register_taxonomy ( 'abas_account_groups',array ( 'abas_accounts' ), array (
        'labels'              => $labels,
        'hierarchical'        => true,
        'show_ui'             => true,
        'show_in_rest'        => false,
        'show_admin_column'   => true,
        'query_var'           => true,
        'rewrite'             => array ( 'slug' => 'account_groups' ),
    ));
  }
  add_action ( 'init', 'abas_account_groups_taxonomy_init', 0 );
endif;
//custom taxonomy Account Groups