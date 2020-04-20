<?php

$file = 'inscription';
$inscription_type = 'all';
$all_fields = api_all_inscription_fields();
if (isset($_GET['type'])) {
    $inscription_type = $_GET['type'];
    $file = $file . '_' . $inscription_type;

    if ($inscription_type == '47musicale') {
        $all_fields = api_all_inscription_fields_47musicale();
    };
}





$insc_args = array(
    'post_type'  => 'inscription',
    'posts_per_page' => -1,
    'post_status' => 'publish',
);
$inscriptions_array = get_posts($insc_args);




// $inscription_ids =  array_map(create_function('$p', 'return $p->ID;'), $inscriptions_array);
$inscription_ids =  array_map('api_get_id_from_object', $inscriptions_array);

$inscription_results = array();


foreach ($all_fields as $field => $value) {
    $fn = 'inscription_' . $field;
    $$fn = api_get_booking_metafield($inscription_ids,  $field);
}

// dont put in $all_fields because they wont show up in their own column
$inscription_housenumber = api_get_booking_metafield($inscription_ids, 'housenumber');
$inscription_housenumber_guardian = api_get_booking_metafield($inscription_ids, 'housenumber_guardian');
$inscription_course_option = api_get_booking_metafield($inscription_ids, 'course_option');

$inscription_other_place_ids = api_get_booking_metafield($inscription_ids, 'other_place_possible_ids');
$inscription_musical_other_place_ids = api_get_booking_metafield($inscription_ids, 'musical_other_place_possible_ids');



var_dump($inscription_other_place_ids);


$data =  implode(';', $all_fields) .  ';ID' .  "\n";


$returned_inscriptions_array = array();



foreach ($inscriptions_array as $inscription) {


    $current_insc_type = get_field('course_type', $inscription->ID);
    if ($inscription_type == 'all' || $inscription_type == $current_insc_type) {

        $meta_strings = array();

        foreach ($all_fields as $field => $value) {

            $fn = 'inscription_' . $field;
            $metafield_string =   api_get_result_from_array($$fn, $inscription);



            //      // THIS IS NOW api_get_result_from_array
            //      // $metafield = array_filter(
            // 		// 	$$fn,
            // 		// 	function ($e)  use ($inscription) {
            // 		// 		return $e->post_id == $inscription->ID;
            // 		// 	}
            // 		// );
            // 		// $metafield =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $metafield));
            //      // $metafield =   getResultFromArray( $$fn , $inscription )  );
            //      // // turn it into a proper string to output
            // 		// $metafield_string = api_process_metafield($metafield);


            if ($field == 'location_id' || $field == 'musical_location_id' || $field == 'course_id' || $field == 'course_id_second_choice') {


                if ($metafield_string != '' && $metafield_string != '0' && $metafield_string != 0) {
                    $post = get_post($metafield_string);
                    if ($post) {
                        $metafield_string = $post->post_title;


                        // ADD OPTION TO COURSE TITLE IF THERE IS AN OPTION PRESENT
                        if ($field == 'course_id') {
                            $course_option =  api_get_result_from_array($inscription_course_option, $inscription);
                            if ($course_option) {
                                $metafield_string .= ' | ' .  $course_option;
                            }
                        }
                    }
                } else {
                    $metafield_string = '';
                }
            } else if ($field == 'other_place_possible' || $field == 'musical_other_place_possible') {

                if ($field == 'other_place_possible') {
                    $metafield_string_ids =  api_get_result_from_array($inscription_other_place_ids, $inscription);
                } else {
                    $metafield_string_ids =  api_get_result_from_array($inscription_musical_other_place_ids, $inscription);
                }


                if ($metafield_string_ids != '' && $metafield_string_ids != null) {
                    $loc_titles = array();
                    $location_ids = explode(' | ', $metafield_string_ids);
                    if (sizeof($location_ids > 0)) {
                        foreach ($location_ids as $location_id) {
                            if ($location_id != 0  && $location_id != '0' && $location_id != '') {
                                $other_location = get_post($location_id);
                                if ($other_location) {
                                    array_push($loc_titles, $other_location->post_title);
                                }
                            }
                        }
                        $metafield_string = implode(' | ', $loc_titles);
                    } else {
                        $metafield_string = '';
                    }
                } else {
                    $metafield_string .= 'HELLO CHARLES';
                };
            } else if ($field == 'telephone_private' || $field == 'telephone_professional' || $field == 'telephone_portable') {
                // format number into either suisse or french format
                $metafield_string =   api_format_phone_number($metafield_string);
                // adddresses should be housenumber + address or address + housenumber
                // depending on if they are suisse or french
            } else if ($field == 'address') {
                $housenumber =  api_get_result_from_array($inscription_housenumber, $inscription);
                $postcode = api_get_result_from_array($inscription_postcode, $inscription);
                $metafield_string = api_format_address($metafield_string, $housenumber, $postcode);
            } else if ($field == 'address_guardian') {
                $housenumber =  api_get_result_from_array($inscription_housenumber_guardian, $inscription);
                $postcode = api_get_result_from_array($inscription_postcode_guardian, $inscription);
                $metafield_string = api_format_address($metafield_string, $housenumber, $postcode);
            } else if ($field == 'date_inscription') {
                $timestamp = strtotime($inscription->post_date);
                $date_inscription = date('d-m-Y', $timestamp);
                $metafield_string = $date_inscription;
            }



            array_push($meta_strings, '"' .  $metafield_string  . '"');
        }



        array_push($meta_strings,   $inscription->ID); //  add ID


        $ar =  $meta_strings;

        array_push($inscription_results, $meta_strings);

        $data .=  implode(';', $ar);
        $data .=  "\n";
    }
}


//echo $data;




// $encoded_csv = mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
// $filename = $file . '_' . date('Y-m-d_H-i', time());
// header('Content-type: application/vnd.ms-excel');
// header('Content-disposition: csv' . date('Y-m-d') . '.csv');
// header('Content-disposition: filename=' . $filename . '.csv');
// header('Content-Length: ' . strlen($encoded_csv));
// $encoded_csv =   chr(255) . chr(254) . $encoded_csv;
// print $encoded_csv;


// header('Content-type: text/html');
// print_r($data);

echo json_encode($inscription_results);

exit;
