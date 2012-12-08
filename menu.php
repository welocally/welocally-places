<?php
/*
	Copyright 2012 clay graham, welocally & RateCred Inc.

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
/**
 * this stuff should all be moved into the class.
 * 
 */

function wl_edit_post_initialise() {
	//jquery ui
	wp_register_style( 'jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css' );
	wp_enqueue_style('media-upload');	
}

function wl_menu_initialise() {
	
	//jquery ui
	wp_register_style( 'jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css' );

	$main_slug = add_menu_page( 'Welocally Places Options', 'Welocally Places', 'manage_options', 'welocally-places-general', 'wl_general_options', WP_PLUGIN_URL . '/welocally-places/resources/images/welocally_places_button_color.png' );
	$main_content =  file_get_contents(dirname( __FILE__ ) . '/help/options-general-help.php');
	add_contextual_help( $main_slug, __( $main_content ) );
	
	$about_help = file_get_contents(dirname( __FILE__ ) . '/help/options-general-help.php');
	$about_slug = wl_places_core_add_submenu( 'Welocally Places About', 'About', 'welocally-places-about', 'wl_support_about',$about_help );
	
	$placemgr_help = file_get_contents(dirname( __FILE__ ) . '/help/manager-help.php');
	$placesmgr_slug = wl_places_core_add_submenu( 'Welocally Places Manager', 'Places Manager', 'welocally-places-manager', 'wl_places_manager',$placemgr_help );

	add_filter( 'plugin_action_links', 'wl_places_add_settings_link', 10, 2 );
	
	//hook so only the admin placemgr screen get jquery ui, conflicts were occurring
	add_action( 'admin_print_styles-' . $placesmgr_slug, 'wl_placesmgr_plugin_admin_styles' );
		
}
//edit_post 
add_action( 'edit_post','wl_edit_post_initialise' );
add_action( 'admin_menu','wl_menu_initialise' );
add_filter( 'plugin_row_meta', 'wl_set_plugin_meta', 10, 2 );

function wl_placesmgr_plugin_admin_styles() {	
	wp_enqueue_style( 'jquery-ui-style' );		
}

function wl_general_options() {
	include_once( WP_PLUGIN_DIR . "/welocally-places/options/options-general.php" );
}

function wl_support_about() {
	include_once( WP_PLUGIN_DIR . "/welocally-places/options/about.php" );
}

function wl_places_manager() {
	
	global $wpdb;
	
	$t = new StdClass();
	$t->uid = uniqid();
	$t->table = $wpdb->prefix.'wl_places';
	$t->fields = 'id,place';
	$t->filter = null;
	$t->orderBy = 'created desc';
	$t->odd = 'wl_placemgr_place_odd';
	$t->even = 'wl_placemgr_place_even';
	$t->pagesize = 10;
	$t->content = '<div class="%ROW_TOGGLE%" style="display:block;">' .
			'<div class="wl_placemgr_place_id field_inline">%id%</div>'.
			'<div class="wl_placemgr_place field_inline" id="wl_placemgr_place_%id%"></div>'.
			'<script type="text/javascript">var pval = %place%;' .
			'setPlaceRow(%id%, pval);' .
			'</script>'.
			'</div>';	
	
	ob_start();
	$imagePrefix = WP_PLUGIN_URL.'/welocally-places/resources/images/';
    include(dirname( __FILE__ ) . '/options/places-manager.php');
    $main_content = ob_get_contents();
    ob_end_clean();
    
    $t = null;
	echo $main_content;
}


function wl_places_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'welocally-places.php' ) !== false ) {
		$settings_link = '<a href="admin.php?page=welocally-places-general">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}


function wl_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'welocally-places.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="http://support.welocally.com/categories/welocally-places-wp-basic" target="_new">' . __( 'Support' ) . '</a>' ) );		
		$links = array_merge( $links, array( '<a href="http://welocally.com/?page_id=139" target="_new">' . __( 'Contact' ) . '</a>' ) );	
		$links = array_merge( $links, array( '<a href="admin.php?page=welocally-places-manager">' . __( 'Places Manager' ) . '</a>' ) );				
		$links = array_merge( $links, array( '<a href="admin.php?page=welocally-places-about">' . __( 'About' ) . '</a>' ) );		
	}

	return $links;
}


function wl_places_core_add_submenu( $page_title, $menu_title, $menu_slug, $function, $help_text ) {
	$profile_slug = add_submenu_page( 'welocally-places-general', $page_title, $menu_title, 'manage_options', $menu_slug, $function );	
	add_contextual_help( $profile_slug, __( $help_text ) );
	return $profile_slug;
}

?>