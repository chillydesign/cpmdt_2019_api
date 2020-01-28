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

$agenda_categories = api_get_agenda_cats($agenda_ids);
$agenda_programs = api_get_agenda_programs($agenda_ids);
$agenda_types = api_get_agenda_types($agenda_ids);



foreach (api_all_agenda_fields() as $field => $value) {
	$fn = 'agenda_' . $field;
	$$fn = api_get_booking_metafield($agenda_ids,  $field);
}


//$data =  'nom,cours,date,' . implode(';' , api_all_agenda_fields_headers()     ) .   "\n";
$data = 'Titre;Catégorie;Programme;Type;' .   implode(';' , api_all_agenda_fields_headers() ) .  "\n";




foreach ($agendas_array as $agenda) {


    // get the categories for the specific agenda
    $cats = array_filter(
        $agenda_categories,
        function ($e)  use ($agenda) {
            return $e->object_id == $agenda->ID;
        }
    );
    $cats =  array_values($cats);
    if (sizeof($cats) > 0 ) {
        $agenda->category = $cats[0]->name;
    } else {
        $agenda->category = '-';
    }
    

    // get the programs for the specific agenda
    $progs = array_filter(
        $agenda_programs,
        function ($e)  use ($agenda) {
            return $e->object_id == $agenda->ID;
        }
    );
    $progs = array_values($progs);
    if (sizeof($progs) > 0 ) {
        $agenda->program = $progs[0]->name;
    } else {
        $agenda->program = '-';
    }


    

    // get the types for the specific agenda
    $typs = array_filter(
        $agenda_types,
        function ($e)  use ($agenda) {
            return $e->object_id == $agenda->ID;
        }
    );

    $typs =  array_values($typs);
    if (sizeof($typs) > 0 ) {
        $agenda->type = $typs[0]->name;
    } else {
        $agenda->type = '-';
    }

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

        api_remove_line_breaks($agenda->post_title),
        api_remove_line_breaks($agenda->category),
        api_remove_line_breaks($agenda->program),
        api_remove_line_breaks($agenda->type),

	);

	$ar =  array_merge($ar, $meta_strings);


    $data .=  implode(';', $ar);
    $data .=  "\n";


}


 //echo $data;





 if (isset($_GET['test']))  {



    echo json_encode( $ar ,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK );

     
 } else {


    $encoded_csv = mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');



    $filename = $file.'_'.date('Y-m-d_H-i',time());
    header('Content-type: application/vnd.ms-excel');
    header('Content-disposition: csv' . date('Y-m-d') . '.csv');
    header( 'Content-disposition: filename='.$filename.'.csv');
    header('Content-Length: '. strlen($encoded_csv));
    $encoded_csv =   chr(255) . chr(254) . $encoded_csv;
    print $encoded_csv;
    
 }



exit;


?>
