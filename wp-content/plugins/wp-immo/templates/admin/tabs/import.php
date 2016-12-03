<?php defined( 'ABSPATH' ) or exit; ?>
<p><?php _e( "Please be patient while the items are imported. This can take a while if your server is slow (inexpensive hosting) or if there is many items. Do not navigate away from this page until this script is done. You will be notified via this page when the process is completed.", WPIMMO_PLUGIN_NAME ); ?></p>

<div id="wpimmo-bar"><div id="wpimmo-bar-percent"></div></div>
<div id="wpimmo-current-process"><?php echo __( 'Saving', WPIMMO_PLUGIN_NAME ) . ' ' . current( $process_tab['process'] ) . '...'; ?></div>

<p><input type="button" class="button hide-if-no-js" name="wpimmo-stop" id="wpimmo-stop" value="<?php _e( 'Abort process', WPIMMO_PLUGIN_NAME ) ?>" /></p>

<ul style="display:none" id="elements-for-js">
    <li id="wpi_feed"><?php echo $process_tab['url']; ?></li>
    <li id="wpi_ids"><?php echo implode( ',', array_keys( $process_tab['process'] ) ); ?></id>
    <li id="wpi_refs"><?php echo json_encode( $process_tab['process'] ); ?></id>
    <li id="wpi_existing_refs"><?php echo json_encode( $process_tab['existing'] ); ?></id>
    <li id="wpi_refs_to_delete"><?php echo json_encode( $process_tab['delete'] ); ?></id>
    <li id="wpi_text_finish"><?php echo sprintf( _n( 'All done! %1$s item was successfully imported. %2$s', 'All done! %1$s items were successfully imported. %2$s', count( $process_tab['process'] ), WPIMMO_PLUGIN_NAME ), count( $process_tab['process'] ), $text_goback ); ?></li>
</ul>