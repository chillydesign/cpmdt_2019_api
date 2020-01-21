<?php


$file = 'booking';

if (isset($_GET['id'])) {
    $bookings_array = get_posts(array(
        'post_parent' => $_GET['id'] ,
        'post_type'  => 'booking',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ) );
} else {
    $bookings_array = get_posts(array(
        'post_type'  => 'booking',
        'posts_per_page' => -1,
        'post_status' => 'publish',

    ) );
}


// $booking_ids =  array_map(create_function('$p', 'return $p->ID;'), $bookings_array);
$booking_ids =  array_map('api_get_id_from_object', $bookings_array);



foreach (api_all_booking_fields() as $field => $value) {
	$fn = 'booking_' . $field;
	$$fn = api_get_booking_metafield($booking_ids,  $field);
}


//$data =  'nom,cours,date,' . implode(',' , api_all_booking_fields_headers()     ) .   "\n";
$data =  "Date, Évènement Id, Titre de l'Évènement," . implode(',' , api_all_booking_fields_headers() ) .  "\n";


$returned_bookings_array = array();


foreach ($bookings_array as $booking) {





	$meta_strings = array();
	foreach (api_all_booking_fields() as $field =>$value) {
		$fn = 'booking_' . $field;
		$metafield = array_filter(
			$$fn,
			function ($e)  use ($booking) {
				return $e->post_id == $booking->ID;
			}
		);
		$metafield =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $metafield));

		// turn it into a proper string to output
		$metafield_string = api_process_metafield($metafield);


		array_push($meta_strings , $metafield_string);
	}


    $event = '';
    $include_booking_in_export = true;
    if ($booking->post_parent > 0) {
        $event_post = get_post($booking->post_parent);
        if ($event_post->post_status == 'archived') {
            // DONT INCLUDE  BOOKINGS OF ARCHIVED EVENT POSTS IN THE download_url
            $include_booking_in_export = false;
        }


        if ($event_post) {
            $event = str_replace(',', ' ', $event_post->post_title );
        }
    }





	$ar = array(
        $booking->post_date,
        $booking->post_parent,
        $event,

	);

	$ar =  array_merge($ar, $meta_strings);




    if ($include_booking_in_export) {
        //$data .=  implode(',', $ar);
        $data .=  implode(',', $ar);
        $data .=  "\n";
    }


}


 //echo $data;







 $encoded_csv = mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');



$filename = $file.'_'.date('Y-m-d_H-i',time());
header('Content-type: application/vnd.ms-excel');
header('Content-disposition: csv' . date('Y-m-d') . '.csv');
header( 'Content-disposition: filename='.$filename.'.csv');
header('Content-Length: '. strlen($encoded_csv));
$encoded_csv =   chr(255) . chr(254) . $encoded_csv;
print $encoded_csv;
//header('Content-type: text/html' );
//print_r($data);

exit;


?>
