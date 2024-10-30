<?php
/*
Plugin Name: Clean Widget IDs
Description: Enables you to change the widget HTML id attribute.
Version: 0.1.1
Author: Hassan Derakhshandeh

		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Clean_Widget_IDs {

	function Clean_Widget_IDs() {
		add_action( 'in_widget_form', array( &$this, 'options' ), 10, 3 );
		add_filter( 'widget_update_callback', array( &$this, 'update' ), 10, 3 );
		add_filter( 'dynamic_sidebar_params', array( &$this, 'dynamic_sidebar_params' ) );
	}

	function options( $widget, $return, $instance ) {
		if( $widget->id_base == 'wic-divider' ) return;
	 ?>
		<p>
			<label for="<?php echo $widget->get_field_id( 'clean_id' ); ?>"><?php _e( 'ID' ) ?>: </label>
			<input type="text" name="<?php echo $widget->get_field_name( 'clean_id' ); ?>" id="<?php echo $widget->get_field_id( 'clean_id' ); ?>" value="<?php echo $instance['clean_id'] ?>" />
		</p>
	<?php }

	/**
	 * Save the clean_id option for current widget in admin area
	 */
	function update( $instance, $new_instance, $old_instance ) {
		$instance['clean_id'] = $new_instance['clean_id'];
		return $instance;
	}

	function dynamic_sidebar_params( $params ) {
		global $wp_registered_widgets;

		if( is_admin() )
			return $params;

		// get options
		$options = get_option( $wp_registered_widgets[$params[0]['widget_id']]['callback'][0]->option_name );
		$widget_id = substr( strstr( $params[0]['widget_id'], '-' ), -1 );

		// replace id
		if( isset( $options[$widget_id]['clean_id'] ) && ! empty( $options[$widget_id]['clean_id'] ) )
			$params[0]['before_widget'] = preg_replace( '/id=(["\'])(.*?)["\']/', "id=$1{$options[$widget_id][clean_id]}$1", $params[0]['before_widget'] );
		return $params;
	}
}
new Clean_Widget_IDs;