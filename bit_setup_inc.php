<?php
$registerHash = array(
	'package_name' => 'topica',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => LIBERTY_SERVICE_TOPICA,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'topica' ) ) {
	require_once( TOPICA_PKG_PATH.'LibertyTopica.php' );

	$gLibertySystem->registerService( LIBERTY_SERVICE_TOPICA, TOPICA_PKG_NAME, array(
		'content_edit_function'  => 'topica_content_edit',
//		'content_edit_mini_tpl' => 'bitpackage:topica/edit_topica_mini_inc.tpl',
		'content_store_function'  => 'topica_content_store',
		'content_preview_function'  => 'topica_content_preview',
		'content_expunge_function'  => 'topica_content_expunge',
		'users_register_function' => 'topica_users_register',
		'user_register_inc_tpl' => 'bitpackage:topica/user_register_inc.tpl',
	) );
}
?>
