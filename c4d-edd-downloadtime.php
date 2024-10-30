<?php
/*
Plugin Name: C4D Edd Download Time
Plugin URI: http://coffee4dev.com/
Description: Set download time for product
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-edd-dt
Version: 4.5.1
*/
define('C4DEDDDT_PLUGIN_URI', plugins_url('', __FILE__));
function c4d_edd_dt_day( $post_id ) {
	if( ! current_user_can( 'manage_shop_settings' ) ) {
		return;
	}
	$value = get_post_meta( $post_id, '_c4d_edd_download_time', true );
	$display = 'bundle' == edd_get_download_type( $post_id ) ? ' style="display: none;"' : '';
?>
	<div id="edd_download_limit_wrap"<?php echo $display; ?>>
		<p><strong><?php _e( 'Time Download File:', 'c4d-edd-dt' ); ?></strong></p>
		<label for="edd_download_limit">
			<?php echo EDD()->html->text( array(
				'name'  => '_c4d_edd_download_time',
				'value' => $value,
				'class' => 'small-text'
			) ); ?>
			<?php _e( 'Leave blank for global setting or 0 for unlimited', 'easy-digital-downloads' ); ?>
		</label>
		<span alt="f223" class="edd-help-tip dashicons dashicons-editor-help" title="<?php _e( '<strong>Time Download File</strong>: Set the number days to download for this product', 'c4d-edd-dt' ); ?>"></span>
	</div>
<?php
}
function c4d_edd_dt_download_arg($params) {
	if (isset($params['download_id'])) {
		$payment = edd_get_payment_by( 'key', $params['download_key'] );
		$days = get_post_meta( $params['download_id'], '_c4d_edd_download_time', true );
		if ($days) {
			$downloadTime = strtotime('+' . $days . 'days', strtotime($payment->payment_meta['date']));
			$current = current_time('timestamp');
			if ($current > $downloadTime) {
				$params['expire'] = rawurlencode($downloadTime);	
				$params['c4dendtime'] = true;
			}
		}	
	}
	return $params;
}
function c4d_edd_dt_meta_action($fields) {
	if ( current_user_can( 'manage_shop_settings' ) ) {
		$fields[] = '_c4d_edd_download_time';
	}
	return $fields;
}
add_filter('edd_download_file_url_args', 'c4d_edd_dt_download_arg', 10, 3);
add_filter('edd_metabox_fields_save', 'c4d_edd_dt_meta_action');
add_action( 'edd_meta_box_settings_fields', 'c4d_edd_dt_day', 20 );
add_action( 'wp_enqueue_scripts', 'c4d_edd_dt_safely_add_stylesheet_to_frontsite');

function c4d_edd_dt_safely_add_stylesheet_to_frontsite( $page ) {
	wp_enqueue_style( 'c4d-edd-dt-frontsite-style', C4DEDDDT_PLUGIN_URI.'/default.css' );
	wp_enqueue_script( 'c4d-edd-dt-frontsite-plugin-js', C4DEDDDT_PLUGIN_URI.'/default.js', array( 'jquery' ), false, true ); 
}
