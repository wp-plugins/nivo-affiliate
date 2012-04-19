<?php
// PVW PLUGIN ADMIN FRAMEWORK -------------------------------------------------------------------------------------//
// ADMIN OPTIONS //

if (!function_exists('pvw_'.NAP_PVW_PLUGIN_SHORTCODE.'_options_template_init')) {

/* Set up options template */
function pvw_nap_options_template_init(){
	
	$shortname = NAP_PVW_PLUGIN_SHORTCODE;
	
	// Set the Options Array
	$options = array();
												
	// ---------------------------------------------------------------------------------------------------------------------------------------------//					

	$options[] = array( "name" => __('General Settings',NAP_PVW_PLUGIN_LINK),
						"type" => "heading");
						
	$options[] = array( "name" => "",
						"message" => __('These are general settings for the plugin',NAP_PVW_PLUGIN_LINK),
						"type" => "intro");
						
	$options[] = array( "name" => "",
						"message" => 	__('To use this plugin you must have a Nivo Slider Affiliate account. ',NAP_PVW_PLUGIN_LINK).'<a href="http://nivo.dev7studios.com/affiliates/">Sign up here</a>',
						"type" => "note");
	
	$options[] = array( "name" => __('Affiliate Link Behaviour',NAP_PVW_PLUGIN_LINK),
					"desc" => __('Check this to enable links to be opened a new window.',NAP_PVW_PLUGIN_LINK),
					"id" => $shortname."_target_blank",
					"std" => "true",
					"type" => "checkbox");
					
	$options[] = array( "name" => "",
						"message" => 	__('You can style the banner advert using the parent div class "nivo-affiliate",  eg. ".nivo-affiliate a img { ... }"',NAP_PVW_PLUGIN_LINK),
						"type" => "note");				
	

					
	// ---------------------------------------------------------------------------------------------------------------------------------------------//					
		
	$options[] = array( "name" => __('Shortcode',NAP_PVW_PLUGIN_LINK),
						"type" => "heading");
	
	
	$banner_options = nivo_affiliate::get_banners();
						
	$shortcode = array(	
							array(__('Parameter',NAP_PVW_PLUGIN_LINK),__('Default',NAP_PVW_PLUGIN_LINK),__('Options',NAP_PVW_PLUGIN_LINK),__('Description',NAP_PVW_PLUGIN_LINK)),
							array('username','polevaultweb','',__('Affiliate username',NAP_PVW_PLUGIN_LINK)),
							array('banner','125x125',$banner_options,__('Name of banner',NAP_PVW_PLUGIN_LINK)),
							array('alt','Nivo Slider - The worlds most awesome jQuery and WordPress image slider','',__('Img Alternative text',NAP_PVW_PLUGIN_LINK))
						
						);
	
	$options[] = array( "name" => "",
						"message" => 	sprintf(__('The shortcode of %1$s can be used in posts and pages. It has the following options',NAP_PVW_PLUGIN_LINK), '<b>nap_get_banner</b>').': <br/>'.
										nap_pvw_plugin_framework::pvw_table_generator($shortcode).
										'<b>'.__('Example',NAP_PVW_PLUGIN_LINK).': </b><br/>[nap_get_banner username=\'polevaultweb\' banner=\'125x125\' alt=\'Get the Nivo Slider\']',
						"type" => "note");
						
		// ---------------------------------------------------------------------------------------------------------------------------------------------//					
		
	$options[] = array( "name" => __('Advert Banners',NAP_PVW_PLUGIN_LINK),
						"type" => "heading");
						
	$options[] = array( "name" => "",
						"message" => __('Below are all of the currently available banners you can use.',NAP_PVW_PLUGIN_LINK),
						"type" => "intro");
						
	
	$banners = nivo_affiliate::get_banners_html();
	
	$options[] = array( "name" => "",
						"message" => $banners,
						"type" => "html");
						
	
	// ---------------------------------------------------------------------------------------------------------------------------------------------//					
	
	// PLUGIN DEBUG AND SUPPORT SECTION
	
	// ---------------------------------------------------------------------------------------------------------------------------------------------//					
							
	$options[] = array( "name" => __('Plugin Support',NAP_PVW_PLUGIN_LINK),
						"type" => "heading");
						
	$options[] = array( "message" => __('If you have any issues with the please visit the <a href="http://www.polevaultweb.com/support/forum/'.NAP_PVW_PLUGIN_LINK.'-plugin/">Support Forum</a>.',NAP_PVW_PLUGIN_LINK),
						"type" => "intro");
						
	$options[] = array( "name" => __('Donations',NAP_PVW_PLUGIN_LINK),
						"desc" => __('If you like the plugin or receive support then please consider donating so we can keep on developing and supporting.',NAP_PVW_PLUGIN_LINK),
						"type" => "donation");
						
	$options[] = array( "name" => "",
					"desc" => __('If you raise a topic or reply on the Support Forum about an issue you are having, please send the following debug data so we can troubleshoot your issue',NAP_PVW_PLUGIN_LINK),
					"id" => $shortname."_send_debug",
					"text" => __('Send Debug Data', NAP_PVW_PLUGIN_LINK),
					"method" => "pvw_send_debug_data",
					"message" => __('Debug data sent! Thank you', NAP_PVW_PLUGIN_LINK),
					"std" => "debug",
					"type" => "button");
						
	$options[] = array( "name" => "",
						"type" => "debug");

						
	
			
	
	// ---------------------------------------------------------------------------------------------------------------------------------------------//					
	// Save options
	
	update_option('pvw_'.$shortname.'_template',$options); 
	
    
}
}


function nap_pvw_validate_setting($plugin_options) {
	  
		$shortname = NAP_PVW_PLUGIN_SHORTCODE;
		
		$template = get_option('pvw_'.$shortname.'_template');

		
		foreach($template as $option) {
		
			if($option['type'] == 'checkbox') {
							
				$id = $option['id'];
							
				$key = array_search($id, $plugin_options);	
			
				if ( !$plugin_options[$id]  ) {
						
					$plugin_options[$id] = "false";
					
				}
				
			}

		}
		
		return $plugin_options;
}


?>