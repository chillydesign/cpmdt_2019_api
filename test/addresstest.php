<?php
require_once('../../wp-config.php');


include('../v1/connect.php');
include('../v1/functions.php');




$postcode = 12345;
$housenumber = 123;
$address = 'Rue do Mont Blanc';


$formatted_address = api_format_address( $address, $housenumber, $postcode  );

echo ($address);
echo "\n";
echo ($housenumber);
echo "\n";
echo ($postcode);
echo "\n";
echo $formatted_address;
echo "\n";
echo "\n";




?>
