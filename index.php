<?php
//topica api: http://www.topica.com/services/#samples

/* from Topica perl example
use LWP;
use strict;
my $SERVER="www.topica.com";
my $debug =1;
my $timeout = 600; # seconds


#############################################################################
#	These values must be changed
#   Make sure to use single quotes or perl will interpret the @ sign
#############################################################################

my $user = 'user@domain.com';
my $pass = 'password';
my $list = 'your_list@topica.com';
my $fileURL = 'http://www.topica.com/services/sample_import.txt'; # replace with your url
my $mailTo = 'user@domain.com';



	my $length = length($message);

	my $ua = LWP::UserAgent->new(agent => 'Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)');
	$ua->timeout( $timeout ) ;

	my $resp = $ua->post(
		"http://$SERVER/api/Dispatcher" ,
		'Host'			=> $SERVER,
		'Content-Type'	=> 'text/xml; charset=utf-8',
		'Content-Length'	=> $length,
		Content       => $message,
	);
        
	if ($debug) {
		print $message . "\n\n";
		print $resp->error_as_HTML . "\n" unless $resp->is_success;
		print $resp->content() . "\n";
	}
	return $resp->is_success;
	
*/


$user = 'will@onnyturf.com';
$pass = '34743474';
$list = 'tekimaki.com@app.topica.com';
$fileURL = 'http://www.topica.com/services/sample_import.txt'; # replace with your url
$mailTo = 'will@onnyturf.com';


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
				<column order="2" field="Favorite Color" create-if-new="true"  data-type="multi">
					<category-values convert-case="true" create-if-new="true">
						<map from="y" to="Yellow"/>
						<map from="yellow" to="Yellow"/>
						<map from="b" to="Black"/>
					</category-values>
				</column>
				<column order="3" field="Address 1"      create-if-new="true" data-type="text"/>
				<column order="4" field="Address 2"      create-if-new="true" data-type="text"/>
				<column order="5" field="City"   create-if-new="true" data-type="text"/>
				<column order="6" field="State or Province"  create-if-new="false" data-type="category"/>
			</mapping>
			<data source="inline">tips@curbed.com,yellow,3 Graasdnde Ave, Apt. #32,Hartford, CT</data>						
		</subscriberImport>
	</topicaAction>
  </soap:Body>
</soap:Envelope>
EOT;


//<data source="url">http://www.topica.com/services/sample_import.txt</data>

//taken from http://www.mikesparr.com/2006/08/21/more-ajax-and-soap-web-service-interaction/
/*
  PHP PROXY SCRIPT (requires cURL)
*/
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
$header_array = Array( "Content-Type: text/xml", "SOAPAction: " /*. $headers['SOAPAction']*/);
curl_setopt ($session, CURLOPT_HTTPHEADER, $header_array);
curl_setopt ($session, CURLOPT_CUSTOMREQUEST, "POST");

// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$xml = curl_exec($session);

// The web service returns XML. Set the Content-Type appropriately
header("Content-Type: text/xml");

echo stripslashes($xml);
curl_close($session);
?>