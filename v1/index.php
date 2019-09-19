<?php

ini_set('default_charset', 'UTF-8');
header('Content-Type: application/json;charset=UTF-8');


require_once('../../wp-config.php');


include('connect.php');
include('functions.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);


if ( isset($_GET['course_id'])  ) {
    include('courses_show.php');

} else if ( isset($_GET['bookings'])  ) {
    include('bookings_index.php');
}  else if ( isset($_GET['inscriptions'])  ) {
    include('inscriptions_index.php');
} else {
    include('courses_index.php');
}





?>
