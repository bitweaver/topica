<?php
$tables = array(
  'topica' => "
    content_id I4 NOTNULL,
	pump C(1) DEFAULT 'y',
	first_name C(64),
	last_name C(64),
	phone_home C(20),
	phone_cell C(20),
    address C(64),
    city C(64),
    state C(64),
    zipcode C(10)
    CONSTRAINT ', CONSTRAINT `topica_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )'
  "
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TOPICA_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( TOPICA_PKG_NAME, array(
	'description' => "A simple Liberty Service used to relay user registration date to Topica.com list manager.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );
?>
