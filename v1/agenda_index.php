<?php


$file = 'agenda';

if (isset($_GET['id'])) {
    $agendas_array = get_posts(array(
        'post_parent' => $_GET['id'] ,
        'post_type'  => 'agenda',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ) );
} else {
    $agendas_array = get_posts(array(
        'post_type'  => 'agenda',
        'posts_per_page' => -1,
        'post_status' => 'publish',

    ) );
}

$addresses = get_posts(array(
    'post_type'  => 'address',
    'posts_per_page' => -1,
    'post_status' => 'publish',
) );




// $agenda_ids =  array_map(create_function('$p', 'return $p->ID;'), $agendas_array);
$agenda_ids =  array_map('api_get_id_from_object', $agendas_array);



foreach (api_all_agenda_fields() as $field => $value) {
	$fn = 'agenda_' . $field;
	$$fn = api_get_booking_metafield($agenda_ids,  $field);
}


//$data =  'nom,cours,date,' . implode(';' , api_all_agenda_fields_headers()     ) .   "\n";
$data = 'Titre;' .   implode(';' , api_all_agenda_fields_headers() ) .  "\n";


$returned_agendas_array = array();


foreach ($agendas_array as $agenda) {





	$meta_strings = array();
	foreach (api_all_agenda_fields() as $field =>$value) {
		$fn = 'agenda_' . $field;
		$metafield = array_filter(
			$$fn,
			function ($e)  use ($agenda) {
				return $e->post_id == $agenda->ID;
			}
        );
        

        // $metafield =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $metafield));
        $metafield =  array_values(array_map('api_get_meta_value_from_object', $metafield));
        // turn it into a proper string to output
        $metafield_string = api_process_metafield($metafield);


        if ($field == 'address_id') {

            if ($metafield_string) {
                foreach($addresses as $address):
                    if ($address->ID == intval($metafield_string)) {
                        $metafield_string = $address->post_title;
                    }
                endforeach;
            }

        } else {

        }



		array_push($meta_strings , $metafield_string);
	}



	$ar = array(

        api_remove_line_breaks($agenda->post_title)

	);

	$ar =  array_merge($ar, $meta_strings);


    $data .=  implode(';', $ar);
    $data .=  "\n";


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
