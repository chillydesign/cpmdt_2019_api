<?php
require_once('../../wp-config.php');


include('../v1/connect.php');
include('../v1/functions.php');



$phones = [
    '+0041 012 345678',
    '+41 012 345678',
    '0041 01234567',
    '00411234567',
    '01234567',
    4112345678,
    '0123456778283478',
    '012345677828',
    '+33 012 345678',
    '+0033 0123456789',
    '0033 0123456789',
    '0033 01234567893423423242134',
    '0033789456789',
    '01234567',
    '00441234567',
    '+00441234567',
    '+441234567',
    '12345',
    '1234',
    '123',
    '12',
    '1',
    '0',
    '00',
    '0041',
    '+0041',
    '',

];

foreach ($phones as $number) {

    $formatted_number = api_format_phone_number($number);

    echo ($number);
    echo "\n";
    echo $formatted_number;
    echo "\n";
    echo "\n";
}



?>
