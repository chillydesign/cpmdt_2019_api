<?php


$posts_array = get_posts(
    array(
        'post_type'  => 'programme',
        'posts_per_page' => -1,
        'post_status' => 'publish' ,
        'orderby' => 'menu_order',
        'order'=> 'ASC'
      )
   );
$post_ids =  array_map(create_function('$p', 'return $p->ID;'), $posts_array);
$course_ageranges = api_get_course_ageranges($post_ids);
$course_categories = api_get_course_cats($post_ids);
$course_locations = api_get_course_locations($post_ids);
$location_ids =  array_map(create_function('$p', 'return $p->wid;'), $course_locations);
$course_zones = api_get_course_zones($location_ids);
$course_professeurs = api_get_course_professeurs($post_ids);
$lower_ages = api_get_p_age($post_ids);
$upper_ages = api_get_p_age2($post_ids);
$hideinsearches = api_get_hide_in_search($post_ids);





foreach ($posts_array as $post) {


    // get the categories for the specific post
    $cats = array_filter(
        $course_categories,
        function ($e)  use ($post) {
            return $e->object_id == $post->ID;
        }
    );
    $post->categories = array_values($cats);


    if (sizeof($post->categories) > 0 ) {
        $post->slug = $post->categories[0]->slug;
    } else {
        $post->slug = '';
    }



    // get the ageranges for the specific post
    $agranges = array_filter(
        $course_ageranges,
        function ($e)  use ($post) {
            return $e->object_id == $post->ID;
        }
    );
    $agrang_val = array_values($agranges);
    if (sizeof($agrang_val) > 0) {
        $post->age_ranges =  (array_map(create_function('$p', 'return $p->slug;'), $agrang_val));
    } else {
        $post->age_ranges = array();
    }


    // get the hide_in_search
    $post->hide_in_search = false;
    $hsea = array_filter(
        $hideinsearches,
        function ($e)  use ($post) {
            return $e->post_id == $post->ID;
        }
    );
    $hsear =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $hsea));
    if ($hsear) {
        $post->hide_in_search = $hsear[0];
    }



    // get the lower ages
    $post->lower_age = 0;
    $l_age = array_filter(
        $lower_ages,
        function ($e)  use ($post) {
            return $e->post_id == $post->ID;
        }
    );
    $l_ages =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $l_age));
    if ($l_ages) {
        $post->lower_age = $l_ages[0];
    }


    // get the upper ages
    $post->upper_age = 100;
    $u_age = array_filter(
        $upper_ages,
        function ($e)  use ($post) {
            return $e->post_id == $post->ID;
        }
    );
    $u_ages =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $u_age));
    if ($u_ages) {
        $post->upper_age = $u_ages[0];
    }



    // get the location
    $location = array_filter(
        $course_locations,
        function ($e)  use ($post) {
            return $e->wppid == $post->ID;
        }
    );
    $course_location_ids =  array_values(array_map(create_function('$p', 'return $p->wid;'), $location));
    $post->locations = $location;
    $post->location_ids = $course_location_ids;



    // get the zone
    $post->zone = [];
    if ($post->location_ids) :
        $zone = array_filter(
            $course_zones,
            function ($e)  use ($post) {
                return in_array($e->post_id, $post->location_ids);
            }
        );

        $zones =  array_values(array_map(create_function('$l', 'return $l->meta_value;'), $zone));
        if ($zones) {
            $return_zone_ids = array();
            foreach ($zones as $zonearray) {
                $zones_ds = maybe_unserialize($zonearray);
                if (is_array($zones_ds)) {
                    foreach($zones_ds as $zid) {
                        array_push($return_zone_ids, $zid);
                    }
                }
            }
            $post->zone = array_unique($return_zone_ids);
        };

    endif;









    // get the prof
    $professuers = array_filter(
        $course_professeurs,
        function ($e)  use ($post) {
            return $e->post_id == $post->ID;
        }
    );
    $prof_array = array();
    foreach ($professuers as $post_meta) {
        if ($post_meta->meta_key[0] != '_' && $post_meta->meta_value != '' ) {

            if (  strlen($post_meta->meta_value) > 1 ) {
                if ( $post_meta->meta_value[1] == ':' ) {  // it is a serialised array
                    $unserialised = unserialize($post_meta->meta_value);

                    // horrible bug where array is serialized twice
                    if ( is_string($unserialised)  ) {
                        if ($unserialised[1] == ':') {
                            $unserialised = unserialize($unserialised);
                        }
                    }


                    foreach ($unserialised as $teacher_id) {
                        array_push($prof_array,  $teacher_id );
                    }

                } else {
                    //    array_push($prof_array,  $post_meta->meta_value );
                }

            } else {
                array_push($prof_array,  $post_meta->meta_value );
            }


        }
    }
    $post->professuers = array_values(array_unique($prof_array));






    $post->image = api_thumbnail_of_post_url($post->ID, 'medium');


    $searchable_fields = array($post->post_title);
    $searchable_fields = implode('  ', $searchable_fields);
    $searchable_fields = wp_strip_all_tags($searchable_fields);
    $searchable_fields = str_replace(array("\n","\r"), ' ', $searchable_fields);
    $searchable_fields = str_replace(array("!","’" , ',' , '/' ,':', "?", '.', '–' ), ' ', $searchable_fields);
    $searchable_fields = remove_accents($searchable_fields);
    $searchable_fields = strtolower($searchable_fields);
    $post->searchfield = $searchable_fields;







    // remove unncessary params
    $unncessary_params = ['comment_count', 'post_status', 'post_mime_type',  'ping_status', 'comment_status' , 'post_date_gmt',  'post_modified_gmt', 'post_password',  'post_excerpt', 'pinged', 'to_ping', 'filter', 'post_content_filtered'];
    foreach ($unncessary_params as $up) {
        unset($post->$up);
    }




}




echo json_encode( $posts_array ,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK );



// get the location
// $location = array_filter(
// 	$course_locations,
// 	function ($e)  use ($post) {
// 		return $e->wppid == $post->ID;
// 	}
// );
// $loc =  reset(array_values($location))->wid ;
// $post->location = ($loc != null) ? $loc : '';


?>
