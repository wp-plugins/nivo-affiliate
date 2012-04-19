<?php

class nap_get_banner_widget extends WP_Widget {

/* Register widget with WordPress. */
public function __construct() {
	parent::__construct(
		NAP_PVW_PLUGIN_SHORTCODE.'_get_banner_widget', //Base ID
		'Nivo Affiliate', //Name
		array( 'description' => __( 'A widget from the '.NAP_PVW_PLUGIN_NAME.' plugin that displays a banner advert with your affiliate link to the awesome Nivo Slider', NAP_PVW_PLUGIN_LINK ), ) //Args
	);
}

/* Front-end display of widget. */
public function widget( $args, $instance ) {
	extract( $args );
	$title = apply_filters( 'widget_title', $instance['title'] );
	$username = isset($instance['username']) ? $instance['username'] : false;
	$banner = isset($instance['banner']) ? $instance['banner'] : false;
	$alt = isset($instance['alt']) ? $instance['alt'] : false;


	echo $before_widget;
	if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
	
	$html = nivo_affiliate::get_banner($username, $banner, $alt, 'wi');
			
	echo $html;
	
	echo $after_widget;
}

/* Sanitize widget form values as they are saved. */
public function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['username'] = strip_tags( $new_instance['username'] );
	$instance['banner'] = strip_tags( $new_instance['banner'] );
	$instance['alt'] = strip_tags( $new_instance['alt'] );
	
	return $instance;
}

/* Back-end widget form. */
public function form( $instance ) {

	$defaults = array( 	'title' => '' , 
						'username' => 'polevaultweb', 
						'banner' => '125x125',
						'alt' => 'Nivo Slider - The worlds most awesome jQuery and WordPress image slider'
						);
						
						
	$instance = wp_parse_args( (array) $instance, $defaults );
	
	$title = strip_tags( $instance['title'] );
	$username = strip_tags( $instance['username'] );
	$banner = strip_tags( $instance['banner'] );
	$alt = strip_tags( $instance['alt'] );
	
	$banners = nivo_affiliate::get_banners();

	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ',NAP_PVW_PLUGIN_LINK ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>	
	<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username: ',NAP_PVW_PLUGIN_LINK ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'banner' ); ?>"><?php _e( 'Banner: ',NAP_PVW_PLUGIN_LINK ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id('banner'); ?>" name="<?php echo $this->get_field_name('banner'); ?>">
		<?php 
		
		foreach ( $banners as $advert ) :
					
		?>
			<option value="<?php echo esc_attr($advert) ?>" <?php selected($advert, $banner) ?>><?php echo $advert; ?></option>
		<?php endforeach; ?>
		</select>
	
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'alt' ); ?>"><?php _e( 'Image Alt: ',NAP_PVW_PLUGIN_LINK ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'alt' ); ?>" name="<?php echo $this->get_field_name( 'alt' ); ?>" type="text" value="<?php echo $alt; ?>" />
	</p>
	
	<?php 
}

} 

