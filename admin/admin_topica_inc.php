<?php
// Copyright (c) 2007 Tekimaki LLC

if( !empty( $_REQUEST['topica_user_id'] ) ) {
	$gBitSystem->storeConfig( 'topica_user_id', $_REQUEST['topica_user_id'], TOPICA_PKG_NAME );
}
if( !empty( $_REQUEST['topica_password1'] ) && !empty( $_REQUEST['topica_password2'] ) && $_REQUEST['topica_password1'] == $_REQUEST['topica_password2']) {
	$gBitSystem->storeConfig( 'topica_acct_pass', $_REQUEST['topica_password1'], TOPICA_PKG_NAME );
}
if( !empty( $_REQUEST['topica_list'] ) ) {
	$gBitSystem->storeConfig( 'topica_list', $_REQUEST['topica_list'], TOPICA_PKG_NAME );
}
if( !empty( $_REQUEST['topica_user_email'] ) ) {
	$gBitSystem->storeConfig( 'topica_user_email', $_REQUEST['topica_user_email'], TOPICA_PKG_NAME );
}

require_once( TOPICA_PKG_PATH.'LibertyTopica.php' );
?>