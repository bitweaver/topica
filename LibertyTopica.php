<?php
/**
* @date created 2007/04/04
* @author Will <will@onnyturf.com>
* @version $Revision: 1.1 $ $Date: 2007/04/10 04:58:51 $
* @class LibertyTopica
*
* Copyright (c) 2007 Tekimaki LLC, Bitweaver.org
*/

require_once( KERNEL_PKG_PATH.'BitBase.php' );

class LibertyTopica extends LibertyBase {
	var $mContentId;

	function LibertyTopica( $pContentId=NULL ) {
		LibertyBase::LibertyBase();
		$this->mContentId = (int)$pContentId;
	}

	/**
	* Load the data from the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function load() {
		if( $this->isValid() ) {			
			global $gBitSystem, $gBitUser;

			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			$lookupColumn = 'content_id';
			$lookupId = $this->mContentId;
			array_push( $bindVars, $lookupId );
			$query = "
				SELECT tpc.*, lc.*, uu.`login`, uu.`email`, uu.`real_name`, lf.`storage_path` as avatar 
					$selectSql
				FROM `".BIT_DB_PREFIX."topica` tpc
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = tpc.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uu ON( uu.`user_id` = lc.`user_id` ) 
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_attachments` a ON (uu.`user_id` = a.`user_id` AND uu.`avatar_attachment_id`=a.`attachment_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_files` lf ON (lf.`file_id` = a.`foreign_id`)
					$joinSql
				WHERE tpc.`$lookupColumn`=? $whereSql ";
			
			
			$this->mInfo = $this->mDb->getRow( $query, array( $this->mContentId ) );
		}
		return( count( $this->mInfo ) );
	}

	/**
	* @param array pParams hash of values that will be used to store the page
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access public
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			if (!empty($pParamHash['topica_store'])) {
				$table = BIT_DB_PREFIX."topica";
				$this->mDb->StartTrans();
				if( !empty( $this->mInfo ) ) {
					$result = $this->mDb->associateUpdate( $table, $pParamHash['topica_store'], array( "content_id" => $this->mContentId ) );
				} else {
					$pParamHash['topica_store']['content_id'] = $this->mContentId;
					$result = $this->mDb->associateInsert( $table, $pParamHash['topica_store'] );
				}
				$this->mDb->CompleteTrans();
				$this->load();
			}
			else if (!empty($this->mInfo)) {
				$this->expunge();
			}
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	* Make sure the data is safe to store
	* @param array pParams reference to hash of values that will be used to store the page, they will be modified where necessary
	* @return bool TRUE on success, FALSE if verify failed. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verify( &$pParamHash ) {
		$pParamHash['topica_store'] = array();
		if( $this->isValid() ) {
			if( !empty( $pParamHash['topica']) ) {
				$pParamHash['topica_store']['pump'] = $pParamHash['topica'];
				if ( isset( $pParamHash['first_name'] ) ) {
					$pParamHash['topica_store']['first_name'] = $pParamHash['first_name'];
				}
				if ( isset( $pParamHash['last_name'] ) ) {
					$pParamHash['topica_store']['last_name'] = $pParamHash['last_name'];
				}
				if ( isset( $pParamHash['phone_home'] ) ) {
					$pParamHash['topica_store']['phone_home'] = $pParamHash['phone_home'];
				}				
				if ( isset( $pParamHash['phone_cell'] ) ) {
					$pParamHash['topica_store']['phone_cell'] = $pParamHash['phone_cell'];
				}
				if ( isset( $pParamHash['address'] ) ) {
					$pParamHash['topica_store']['address'] = $pParamHash['address'];
				}
				if ( isset( $pParamHash['city'] ) ) {
					$pParamHash['topica_store']['city'] = $pParamHash['city'];
				}
				if ( isset( $pParamHash['state'] ) ) {
					$pParamHash['topica_store']['state'] = $pParamHash['state'];
				}
				if ( isset( $pParamHash['zipcode'] ) ) {
					$pParamHash['topica_store']['zipcode'] = $pParamHash['zipcode'];
				}
			}
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	* check if the mContentId is set and valid
	*/
	function isValid() {
		return( @BitBase::verifyId( $this->mContentId ) );
	}

	/**
	* This function removes extended user data
	**/
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."topica` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
		}
		return $ret;
	}
	
	/** 
	* This function sends personal data to Topica
	**/
	function pumpTopica() {
		global $gBitSystem;
		if( $this->isValid() ) {
			$user = $gBitSystem->getConfig('topica_user_id');
			$pass = $gBitSystem->getConfig('topica_acct_pass');
			$list = $gBitSystem->getConfig('topica_list');
			$mailTo = $gBitSystem->getConfig('topica_user_email');
			$data = $this->mInfo['email'].",".$this->mInfo['first_name'].",".$this->mInfo['last_name'].",".$this->mInfo['phone_home'].",".$this->mInfo['phone_cell'].",".$this->mInfo['address'].",".$this->mInfo['city'].",".$this->mInfo['state'].",".$this->mInfo['zipcode'];
			$soapmsg = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<topicaAction username="$user" password="$pass" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:SchemaLocation="http://www.topica.com/services/" 
xmlns="http://www.topica.com/Dispatcher/" >
<subscriberImport
list="$list"
column-delimiter=","
value-delimiter=";"
duplicate="complete-overwrite"
first-row="data"
send-confirmations="false"
email-to="$mailTo">
<mapping>
<column order="1" field="Email Address"  create-if-new="false"/>
<column order="2" field="First Name"  create-if-new="true" data-type="text"/>
<column order="3" field="Last Name"  create-if-new="true" data-type="text"/>
<column order="4" field="Home Phone"  create-if-new="true" data-type="text"/>
<column order="5" field="Cell/Mobile Phone"  create-if-new="true" data-type="text"/>
<column order="6" field="Address 1"  create-if-new="true" data-type="text"/>
<column order="7" field="City"  create-if-new="true" data-type="text"/>
<column order="8" field="State or Province"  create-if-new="false" data-type="category"/>
<column order="9" field="Zip or Postal Code"  create-if-new="false" data-type="text"/>
</mapping>
<data source="inline">$data</data>						
</subscriberImport>
</topicaAction>
</soap:Body>
</soap:Envelope>
EOT;

			
			//taken from http://www.mikesparr.com/2006/08/21/more-ajax-and-soap-web-service-interaction/
			//  PHP PROXY SCRIPT (requires cURL)
			// Allowed hostname (hard-code this for security)
			define ('HOSTNAME', 'http://www.topica.com');
			
			//set the full path
			$path = '/api/Dispatcher';
			$url = HOSTNAME.$path;
			
			// Open the Curl session
			$session = curl_init($url);
			
			//add the soap envelope
			//taken from http://curl.haxx.se/mail/curlphp-2001-05/0015.html
			curl_setopt($session, CURLOPT_POST, 1);
			curl_setopt($session, CURLOPT_POSTFIELDS, $soapmsg); 
			
			// Set outgoing request headers for SOAP requests
			$headers = apache_request_headers();
			$header_array = Array( "Content-Type: text/xml", "SOAPAction: "); //. $headers['SOAPAction']);
			curl_setopt ($session, CURLOPT_HTTPHEADER, $header_array);
			curl_setopt ($session, CURLOPT_CUSTOMREQUEST, "POST");
			
			// Don't return HTTP headers. Do return the contents of the call
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			
			// Make the call
			$xml = curl_exec($session);
			
			//@todo right here want to get xml resultings and email the results to the webmin
			// The web service returns XML. Set the Content-Type appropriately
			//header("Content-Type: text/xml");			
			//echo stripslashes($xml);
			
			curl_close($session);
			//die;
		}
		return( count( $this->mErrors )== 0 );
	}
}

/********* SERVICE FUNCTIONS *********/

function topica_content_store( &$pObject, &$pParamHash ) {
	global $gBitSystem, $gBitUser;
	$errors = NULL;
	// If a content access system is active, let's call it
	if( $gBitSystem->isPackageActive( 'topica' ) ) {
		if (isset($pParamHash['first_name']) || isset($pParamHash['last_name'])){
			$prefs= array('prefs' => 'y', 'real_name' => ($pParamHash['first_name']?$pParamHash['first_name']." ":"").($pParamHash['last_name']?$pParamHash['last_name']:""));
			require_once( USERS_PKG_PATH.'BitUser.php' );
			$tempUser = new BitUser($pObject->mUserId);
			$tempUser->load( TRUE );
			$tempUser->store( $prefs );
		}
		$topica = new LibertyTopica( $pObject->mContentId );
		if ( !$topica->store( $pParamHash ) ) {
			$errors=$topica->mErrors;
		}
	}
	return( $errors );
}

function topica_content_preview( &$pObject) {
	global $gBitSystem;
	if ( $gBitSystem->isPackageActive( 'topica' ) ) {		
		if (isset($_REQUEST['topica'])) {
			$pObject->mInfo['topica'] = $_REQUEST['topica'];
		}
		if (isset($_REQUEST['first_name'])) {
			$pObject->mInfo['first_name'] = $_REQUEST['first_name'];
		}
		if (isset($_REQUEST['last_name'])) {
			$pObject->mInfo['last_name'] = $_REQUEST['last_name'];
		}
		if (isset($_REQUEST['phone_home'])) {
			$pObject->mInfo['phone_home'] = $_REQUEST['phone_home'];
		}
		if (isset($_REQUEST['phone_cell'])) {
			$pObject->mInfo['phone_cell'] = $_REQUEST['phone_cell'];
		}
		if (isset($_REQUEST['address'])) {
			$pObject->mInfo['address'] = $_REQUEST['address'];
		}
		if (isset($_REQUEST['city'])) {
			$pObject->mInfo['city'] = $_REQUEST['city'];
		}
		if (isset($_REQUEST['state'])) {
			$pObject->mInfo['state'] = $_REQUEST['state'];
		}
		if (isset($_REQUEST['zipcode'])) {
			$pObject->mInfo['zipcode'] = $_REQUEST['zipcode'];
		}
	}
}

function topica_content_expunge( &$pObject ) {
	$topica = new LibertyTopica( $pObject->mContentId );
	$topica->expunge();
}

function topica_users_register( &$pObject ) {
	global $gBitSystem;
	if ( $gBitSystem->isPackageActive( 'topica' ) ) {		
		$topica = new LibertyTopica( $pObject->mContentId );
		$topica->load();
		if ($topica->mInfo['pump']=='y'){
			$topica->pumpTopica();
		}
	}
}
?>
