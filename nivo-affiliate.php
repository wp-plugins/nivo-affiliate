<?php
/*  
Plugin Name: Nivo Affiliate
Plugin URI: http://www.polevaultweb.com/plugins/nivo-affiliate/  
Description: Easily add Nivo Slider affiliate banners to your posts, pages and sidebars using this plugin.
Author: polevaultweb 
Version: 0.1.2
Author URI: http://www.polevaultweb.com/

Copyright 2012  polevaultweb  (email : info@polevaultweb.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

//plugin version
define( 'NAP_PVW_PLUGIN_VERSION', '0.1.2');
//plugin name
define( 'NAP_PVW_PLUGIN_NAME', 'Nivo Affiliate');
//plugin shortcode
define( 'NAP_PVW_PLUGIN_SHORTCODE', 'nap');

//plugin text domain
define( 'NAP_PVW_PLUGIN_SETTINGS', str_replace(" ","",strtolower(NAP_PVW_PLUGIN_NAME)));
//plugin linking
define( 'NAP_PVW_PLUGIN_LINK',  str_replace(" ","-",strtolower(NAP_PVW_PLUGIN_NAME)));
//plugin class
define( 'NAP_PVW_PLUGIN_CLASS',  str_replace(" ","_",strtolower(NAP_PVW_PLUGIN_NAME)));

//helpful paths
define( 'NAP_PVW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NAP_PVW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NAP_PVW_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'NAP_PVW_PLUGIN_DIR',dirname( plugin_basename( __FILE__ ) ));

//plugin specific
define( 'NAP_PVW_AFFILIATE_LINK','http://nivo.dev7studios.com?ap_id=');
define( 'NAP_PVW_AFFILIATE_IMG_LINK','http://nivo.dev7studios.com/wp-content/uploads/2011/11/');
define( 'NAP_PVW_AFFILIATE_IMG_EXT','.png');
define( 'NAP_PVW_AFFILIATE_IMG_LINK_ALT','http://nivo.dev7studios.com/wp-content/uploads/2012/02/');
define( 'NAP_PVW_AFFILIATE_IMG_EXT_ALT','.jpg');

//require ADMIN file for plugin settings
require_once NAP_PVW_PLUGIN_PATH.'admin/admin-page.php';

//require OPTIONS file for plugin settings
require_once NAP_PVW_PLUGIN_PATH.'admin/admin-options.php';

//require WIDGETS
require_once plugin_dir_path( __FILE__ ).'widgets/get_banner.php';

//require INCLUDES other php files for API handlers etc

if (!class_exists(NAP_PVW_PLUGIN_CLASS)) {

class nivo_affiliate {

		//BEGIN - FUNCTIONS FOR PLUGIN FRAMEWORK //
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
		
		/* Plugin loading method */
		public static function load_plugin() {
			
			// -- BEGIN PLUGIN FRAMEWORK ---------------------------------------------------------------------------------------- //
			
			//language support
			add_action('init', get_class()  . '::load_language_support');
			
			//settings menu
			add_action('admin_menu',get_class()  . '::register_settings_menu' );
			//settings link
			add_filter('plugin_action_links', get_class()  . '::register_settings_link', 10, 2 );
			//styles and scripts
			add_action('admin_init', get_class()  . '::register_styles');
			//register custom css from settings
			//add_action('wp_head',  get_class()  . '::custom_head_css');
			//register default css from settings
			//add_action('get_header', get_class()  . '::custom_css');
			
			//register settings options
			add_action('admin_init', get_class()  . '::register_settings');
			
			//register default settings template on activation
			////add_action('admin_init', 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_options_template_init' );
			register_activation_hook(__FILE__, 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_options_template_init' );
			//register default settings options on activation
			register_activation_hook(__FILE__, get_class()  . '::options_setup' );

			//register upgrade check function
			add_action('admin_init', get_class()  . '::upgrade_check');
			
			//register uninstall hook
			register_uninstall_hook(__FILE__,  get_class()  . '::plugin_uninstall');
			
			// -- END PLUGIN FRAMEWORK ---------------------------------------------------------------------------------------- //
			
			// -- SHORTCODES ---------------------------------------------------------------------------------------- //
			
			//add shortcode for shots
			add_shortcode(NAP_PVW_PLUGIN_SHORTCODE.'_get_banner', array(NAP_PVW_PLUGIN_CLASS, NAP_PVW_PLUGIN_SHORTCODE.'_get_banner_sc') );
			
			
			// -- WIDGETS ---------------------------------------------------------------------------------------- //
			
			//register widget for shots
			add_action( 'widgets_init', create_function( '', 'register_widget( "'.NAP_PVW_PLUGIN_SHORTCODE.'_get_banner_widget" );' ) );
			
			
			// -- CUSTOM REGISTRATIONS ---------------------------------------------------------------------------------------- //
				
		}
		
		/* Add language support */
		public static function load_language_support() {
		
			//load_plugin_textdomain( NAP_PVW_PLUGIN_LINK, false, NAP_PVW_PLUGIN_DIR . '/languages' );
		
		}
		
		/* Add settings options for plugin  */
		public static function register_settings() {  
		
			//register settings options
			register_setting( 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_plugin_options', 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_options',NAP_PVW_PLUGIN_SHORTCODE.'_pvw_validate_setting' );
		}
		
		/* Add hook to admin framework for options set up */
		public static function options_setup() {
				
			nap_pvw_plugin_framework::pvw_options_setup();
			
		}
   		  			
		/* Add menu item for plugin to Settings Menu */
		public static function register_settings_menu() {  
   		  			
   			add_options_page( NAP_PVW_PLUGIN_NAME, NAP_PVW_PLUGIN_NAME, 1, NAP_PVW_PLUGIN_SETTINGS, get_class() . '::settings_page' );
	  				
		}

		/* Add settings link to Plugin page */
		public static function register_settings_link($links, $file) {  
   		  		
			static $this_plugin;
				if (!$this_plugin) $this_plugin = NAP_PVW_PLUGIN_BASE;
				 
				if ($file == $this_plugin){
				$settings_link = '<a href="options-general.php?page='.NAP_PVW_PLUGIN_SETTINGS.'">' . __('Settings', NAP_PVW_PLUGIN_SETTINGS) . '</a>';
				array_unshift( $links, $settings_link );
			}
			return $links;
				
		}
		
		/* Register custom stylesheets and script files */
		public static function register_styles() {
		 		
			if (isset($_GET['page']) && $_GET['page'] == NAP_PVW_PLUGIN_SETTINGS) {
		 
				//register styles
				wp_register_style( NAP_PVW_PLUGIN_SHORTCODE.'_adminstyle', NAP_PVW_PLUGIN_URL . 'admin/admin-style.css');
				
				//enqueue styles	
				wp_enqueue_style(NAP_PVW_PLUGIN_SHORTCODE.'_adminstyle' );
				wp_enqueue_style('dashboard');
				
				//enqueue scripts
				wp_enqueue_script('admin-tabs-script', NAP_PVW_PLUGIN_URL . 'admin/scripts/admin-tabs.js');
				wp_enqueue_script('dashboard');
				wp_enqueue_script('jquery-ui-core');
				
				//add script for reset admin options
				if (isset($_POST['pvw_reset']))  {
				
					if ($_POST['pvw_reset'] == 'reset' ) {
						nap_pvw_plugin_framework::pvw_options_reset();
						header("Location: ".$_SERVER['REQUEST_URI']);
						exit();	
					}
				
				}
			}
		}
		
		/* Register custom upgrade check function */
		public static function upgrade_check() {
		
			$saved_version = get_option( 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_version' );
			$current_version = isset($saved_version) ? $saved_version : 0;

			if ( version_compare( $current_version, NAP_PVW_PLUGIN_VERSION, '==' ) )
				return;
				
			//specific version checks on upgrade
			//if ( version_compare( $current_version, '0.1', '<' ) ) {}	
				
			//update the database version
			update_option( 'pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_version', NAP_PVW_PLUGIN_VERSION );
		
		} 
		
		/* Register custom uninstall function */
		public static function plugin_uninstall() {
		
			//delete settings and template options
			delete_option('pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_template'); 
			delete_option('pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_options'); 

			//delete version
			delete_option('pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_version');
			
		
		}
		
		/* Plugin Settings page and settings data */
		public static function settings_page() {
					
			nap_pvw_plugin_framework::pvw_plugin_settings();
		
		}
		
		//END - FUNCTIONS FOR PLUGIN FRAMEWORK //
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
			
					
		/* Function used by shortcode */
		public static function nap_get_banner_sc($atts, $content = null) {
		
			//extract shortcode parameters
			extract(shortcode_atts(array(	'username' => 'polevaultweb',
											'banner' => '125x125',
											'alt' => 'Nivo Slider - The worlds most awesome jQuery and WordPress image slider'
										), $atts));
			

			$html = self::get_banner($username, $banner, $alt,'sc');
			
			return $html;
		
		}
		
		/* General function to display banners */
		public static function get_banner($username, $banner, $alt, $type) {
		
			$shortname = NAP_PVW_PLUGIN_SHORTCODE;
			$saved_options = get_option( 'pvw_'.$shortname.'_options' );
			$target_blank = $saved_options[$shortname.'_target_blank'];
			
			$src = self::get_banner_img($banner);
			$url = NAP_PVW_AFFILIATE_LINK.$username;
			
			$html  .= "\n";
			
			$target = '';
			if ($target_blank == "true") { $target = 'target="_blank"';}
			
			$html  .= '<!-- Nivo Affiliate Banner Advert served by '.NAP_PVW_PLUGIN_NAME.', a WordPress plugin by polevaultweb.com -->'."\n";
			$html  .= '<!-- http://wordpress.org/extend/plugins/'.NAP_PVW_PLUGIN_LINK.' -->'."\n";
			$html  .= '<div class="nivo-affiliate">'."\n";
			$html  .= '<a href="'.$url.'" title="'.$alt.'" '.$target .'>'."\n";
			$html  .= '<img src="'.$src.'" alt="'.$alt.'" />'."\n";	
		
			//END a
			$html .= '</a>'."\n";
			//END div nivo-affiliate
			$html .= '</div>';
			
					
			return $html;
			
		}
		
		/* General function to return array of banner titles */
		public static function get_banners() {
		
			$banners = array(
								"125x125",
								"125x125_alt",
								"180x100",
								"260x120",
								"300x250",
								"300x250_alt",
								"468x60",
								"728x90"
							);
		
			return $banners;
		
		}
		
		/* General function to return banner image */
		public static function get_banner_img($banner) { 
		
			if (substr($banner,-3) == "alt") {
			
				return NAP_PVW_AFFILIATE_IMG_LINK_ALT.$banner.NAP_PVW_AFFILIATE_IMG_EXT_ALT;
			
			} else {
			
				return NAP_PVW_AFFILIATE_IMG_LINK.$banner.NAP_PVW_AFFILIATE_IMG_EXT;
			
			}
			
		
		
		}
		
		/* General function to return banner images in html form */
		public static function get_banners_html() {
		
			$banners = self::get_banners();
			
			$html = '';
						
			for($i = 0; $i < sizeof($banners); ++$i)
			{
				$html .= '<div style="float:left; margin: 0 20px 20px 0; min-height: 155px">';
				$html .= '<img src="'.self::get_banner_img($banners[$i]).'" alt="'.$banners[$i].'" />';
				$html .= '<br/><b>Banner '.$banners[$i].'</b>';
				$html .= '</div>';
			}
		
			return $html;
		
		}
				
	}

}

if (class_exists(NAP_PVW_PLUGIN_CLASS)) {

	//Load plugin
	nivo_affiliate::load_plugin();
	
}
?>