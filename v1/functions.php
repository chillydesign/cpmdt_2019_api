<?php





// setlocale(LC_CTYPE, 'en_AU.utf8');

if (!function_exists('api_normal_chars')) {
	function api_normal_chars($string)
	{

		$str = remove_accents($string);
		//	$str =  iconv('UTF-8','ASCII//TRANSLIT',$string);
		return $str;
	}
}

if (!function_exists('api_get_request_courses')) {
	function api_get_request_courses($request_ids)
	{
		global $conn;
		if (sizeof($request_ids) > 0) :
			// GET CATEGORIES
			$c_sql = ' post_id = ';
			$c_sql .= implode(' OR post_id =   ', $request_ids);
			$c_query = $conn->prepare("SELECT  meta_value, post_id, post_title
									FROM  `wp_postmeta`
								 LEFT JOIN wp_posts ON wp_posts.ID = wp_postmeta.meta_value
									WHERE ( $c_sql ) AND meta_key = '_course_id' ");
			$c_query->execute();
			# setting the fetch mode
			$c_query->setFetchMode(PDO::FETCH_OBJ);
			$request_courses =  $c_query->fetchAll();
			unset($conn);
			return $request_courses;
		endif;
	}
}


if (!function_exists('api_get_request_times')) {
	function api_get_request_times($request_ids)
	{
		global $conn;
		if (sizeof($request_ids) > 0) :
			// GET CATEGORIES
			$c_sql = ' post_id = ';
			$c_sql .= implode(' OR post_id =   ', $request_ids);
			$c_query = $conn->prepare("SELECT  meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $c_sql ) AND meta_key = '_teacher_id' ");
			$c_query->execute();
			# setting the fetch mode
			$c_query->setFetchMode(PDO::FETCH_OBJ);
			$request_times =  $c_query->fetchAll();
			unset($conn);
			return $request_times;
		endif;
	}
}

if (!function_exists('api_get_request_emails')) {
	function api_get_request_emails($request_ids)
	{
		global $conn;
		if (sizeof($request_ids) > 0) :

			$c_sql = ' post_id = ';
			$c_sql .= implode(' OR post_id =   ', $request_ids);
			$c_query = $conn->prepare("SELECT  meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $c_sql ) AND meta_key = '_email_address' ");
			$c_query->execute();
			# setting the fetch mode
			$c_query->setFetchMode(PDO::FETCH_OBJ);
			$request_emails =  $c_query->fetchAll();
			unset($conn);
			return $request_emails;
		endif;
	}
}

if (!function_exists('api_get_booking_metafield')) {
	function api_get_booking_metafield($request_ids, $field)
	{
		global $conn;
		if (sizeof($request_ids) > 0) :

			$c_sql = ' post_id = ';
			$c_sql .= implode(' OR post_id =   ', $request_ids);
			$c_query = $conn->prepare("SELECT  meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $c_sql ) AND meta_key = '$field' ");
			$c_query->execute();
			# setting the fetch mode
			$c_query->setFetchMode(PDO::FETCH_OBJ);
			$request_metas =  $c_query->fetchAll();
			unset($conn);
			return $request_metas;
		endif;
	}
}





if (!function_exists('api_get_course_ageranges')) {
	function api_get_course_ageranges($course_ids)
	{
		global $conn;

		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$course_cat_sql = ' object_id = ';
			$course_cat_sql .= implode(' OR object_id =   ', $course_ids);
			$course_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
									FROM  `wp_term_relationships`
									LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
									LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
									WHERE ( $course_cat_sql ) AND taxonomy = 'agerange' ");
			$course_query->execute();
			# setting the fetch mode
			$course_query->setFetchMode(PDO::FETCH_OBJ);
			$course_categories =  $course_query->fetchAll();
			unset($conn);
			return $course_categories;
		endif;
	}
}



if (!function_exists('api_get_course_cats')) {
	function api_get_course_cats($course_ids)
	{
		global $conn;

		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$course_cat_sql = ' object_id = ';
			$course_cat_sql .= implode(' OR object_id =   ', $course_ids);
			$course_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
									FROM  `wp_term_relationships`
									LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
									LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
									WHERE ( $course_cat_sql ) AND taxonomy = 'programmes' ");
			$course_query->execute();
			# setting the fetch mode
			$course_query->setFetchMode(PDO::FETCH_OBJ);
			$course_categories =  $course_query->fetchAll();
			unset($conn);
			return $course_categories;
		endif;
	}
}





if (!function_exists('api_get_course_descriptions')) {
	function api_get_course_descriptions($course_ids)
	{
		global $conn;
		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$description_sql = ' post_id = ';
			$description_sql .= implode(' OR post_id =   ', $course_ids);
			$description_query = $conn->prepare("SELECT  meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $description_sql ) AND meta_key = 'description' ");
			$description_query->execute();
			# setting the fetch mode
			$description_query->setFetchMode(PDO::FETCH_OBJ);
			$course_descriptions =  $description_query->fetchAll();
			unset($conn);
			return $course_descriptions;
		endif;
	}
}

if (!function_exists('api_remove_line_breaks')) {
	function api_remove_line_breaks($string)
	{

		$new_string = str_replace(array("\r", "\n"), '', $string);
		$new_string = str_replace(';', ' ', $new_string);
		$new_string = str_replace(',', ' ', $new_string);
		$new_string = strip_tags($new_string);
		return $new_string;
	}
}


// if(!function_exists('api_get_course_ecolages')) {
// function api_get_course_ecolages($course_ids){
// 	global $conn;
// 	if (sizeof($course_ids) > 0):
// 		// GET CATEGORIES
// 		$ecolage_sql = ' post_id = ';
// 		$ecolage_sql .= implode(' OR post_id =   ', $course_ids);
// 		$ecolage_query = $conn->prepare("SELECT  meta_value, post_id
// 									FROM  `wp_postmeta`
// 									WHERE ( $ecolage_sql ) AND meta_key = 'ecolage' ");
// 		$ecolage_query->execute();
// 		# setting the fetch mode
// 		$ecolage_query->setFetchMode(PDO::FETCH_OBJ);
// 		$course_ecolages =  $ecolage_query->fetchAll();
// 		unset($conn);
// 		return $course_ecolages;
// 	endif;
//
// }
// }


// if(!function_exists('api_get_course_ecolages')) {
// function api_get_course_ecolages($course_ids){
// 	global $conn;

// 	if (sizeof($course_ids) > 0):
// 		// GET ECOLAGE TAXONOMY
// 		$course_eco_sql = ' object_id = ';
// 		$course_eco_sql .= implode(' OR object_id =   ', $course_ids);
// 		$course_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
// 									FROM  `wp_term_relationships`
// 									LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
// 									LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
// 									WHERE ( $course_eco_sql ) AND taxonomy = 'ecolage-cours' ");
// 		$course_query->execute();
// 		# setting the fetch mode
// 		$course_query->setFetchMode(PDO::FETCH_OBJ);
// 		$course_ecolages =  $course_query->fetchAll();
// 		unset($conn);
// 		return $course_ecolages;
// 	endif;
// }
// }










if (!function_exists('api_get_course_extras')) {
	function api_get_course_extras($course_ids)
	{
		global $conn;
		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$extra_sql = ' post_id = ';
			$extra_sql .= implode(' OR post_id =   ', $course_ids);
			$extra_query = $conn->prepare("SELECT meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $extra_sql ) AND meta_key = 'extra' ");


			$extra_query->execute();
			# setting the fetch mode
			$extra_query->setFetchMode(PDO::FETCH_OBJ);
			$course_extras =  $extra_query->fetchAll();
			unset($conn);

			return $course_extras;
		endif;
	}
}



if (!function_exists('api_get_course_schools')) {
	function api_get_course_schools($course_ids)
	{
		global $conn;
		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$school_sql = ' post_id = ';
			$school_sql .= implode(' OR post_id =   ', $course_ids);
			$school_query = $conn->prepare("SELECT post_id, wp_postmeta.post_id as wppid  , post_title, meta_value
									FROM  `wp_postmeta`
									LEFT JOIN wp_posts ON wp_postmeta.meta_value = wp_posts.ID
									WHERE ( $school_sql ) AND meta_key = 'school' ");


			$school_query->execute();
			# setting the fetch mode
			$school_query->setFetchMode(PDO::FETCH_OBJ);
			$course_schools =  $school_query->fetchAll();
			unset($conn);

			return $course_schools;
		endif;
	}
}







if (!function_exists('api_get_course_professeurs')) {
	function api_get_course_professeurs($course_ids)
	{
		global $conn;
		if (sizeof($course_ids) > 0) :
			// GET professeurs
			$professeur_sql = ' post_id = ';
			$professeur_sql .= implode(' OR post_id =   ', $course_ids);
			$professeur_query = $conn->prepare("SELECT  * FROM  `wp_postmeta`
							WHERE ( $professeur_sql ) AND  `meta_key` LIKE '%teachers%'   ");


			$professeur_query->execute();
			# setting the fetch mode
			$professeur_query->setFetchMode(PDO::FETCH_OBJ);
			$course_professeurs =  $professeur_query->fetchAll();
			unset($conn);

			return $course_professeurs;
		endif;
	}
}



if (!function_exists('api_get_course_locations')) {
	function api_get_course_locations($course_ids)
	{
		global $conn;
		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			$location_sql = ' post_id = ';
			$location_sql .= implode(' OR post_id =   ', $course_ids);
			$location_query = $conn->prepare("SELECT wp_postmeta.post_id as wppid  , post_title, wp_posts.ID as wid
							FROM  `wp_postmeta`
							LEFT JOIN wp_posts ON wp_postmeta.meta_value = wp_posts.ID
							WHERE ( $location_sql ) AND  `meta_key` LIKE '%location%' AND  post_title != '' ");

			$location_query->execute();
			# setting the fetch mode
			$location_query->setFetchMode(PDO::FETCH_OBJ);
			$course_locations =  $location_query->fetchAll();
			unset($conn);

			return $course_locations;
		endif;
	}
}


if (!function_exists('api_get_course_zones')) {
	function api_get_course_zones($location_ids)
	{
		global $conn;
		$location_ids =   array_unique(array_filter($location_ids));
		if (sizeof($location_ids) > 0) :
			// GET CATEGORIES
			$zone_sql = ' post_id = ';
			$zone_sql .= implode(' OR post_id =   ', $location_ids);
			$zone_query = $conn->prepare("SELECT meta_value, post_id
 									FROM  `wp_postmeta`
 									WHERE ( $zone_sql ) AND meta_key = 'zones' ");

			$zone_query->execute();
			# setting the fetch mode
			$zone_query->setFetchMode(PDO::FETCH_OBJ);
			$location_zones =  $zone_query->fetchAll();
			unset($conn);

			return $location_zones;
		endif;
	}
}


if (!function_exists('api_get_p_age')) {
	function api_get_p_age($course_ids)
	{

		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			global $wpdb;
			$extra_sql = ' post_id = ';
			$extra_sql .= implode(' OR post_id =   ', $course_ids);
			$course_p_ages = $wpdb->get_results("SELECT meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $extra_sql ) AND meta_key = 'p_age' ");


			return $course_p_ages;
		endif;
	}
}
if (!function_exists('api_get_p_age2')) {
	function api_get_p_age2($course_ids)
	{
		if (sizeof($course_ids) > 0) :
			// GET CATEGORIES
			global $wpdb;
			$extra_sql = ' post_id = ';
			$extra_sql .= implode(' OR post_id =   ', $course_ids);
			$course_p_age2s = $wpdb->get_results("SELECT meta_value, post_id
									FROM  `wp_postmeta`
									WHERE ( $extra_sql ) AND meta_key = 'p_age2' ");

			return $course_p_age2s;
		endif;
	}
}

if (!function_exists('api_get_hide_in_search')) {
	function api_get_hide_in_search($course_ids)
	{
		if (sizeof($course_ids) > 0) :
			// GET META HIDE_IN_SEARCH
			global $wpdb;
			$extra_sql = ' post_id = ';
			$extra_sql .= implode(' OR post_id =   ', $course_ids);
			$course_hides = $wpdb->get_results("SELECT meta_value, post_id
										FROM  `wp_postmeta`
										WHERE ( $extra_sql ) AND meta_key = 'hide_in_search' ");

			return $course_hides;
		endif;
	}
}



if (!function_exists('api_remove_unnecessary_things')) {
	function api_remove_unnecessary_things($object)
	{

		unset($object->to_ping);
		unset($object->pinged);
		unset($object->menu_order);
		unset($object->ping_status);
		unset($object->comment_status);
		unset($object->comment_count);
		unset($object->post_mime_type);
		unset($object->post_date_gmt);
		unset($object->post_password);
		unset($object->post_modified_gmt);
		unset($object->filter);
		unset($object->post_content_filtered);
		unset($object->post_author);

		return $object;
	}
}


if (!function_exists('api_thumbnail_of_post_url')) {
	function api_thumbnail_of_post_url($post_id,  $size = 'large')
	{

		$image_id = get_post_thumbnail_id($post_id);
		if ($image_id) {
			$image_url = wp_get_attachment_image_src($image_id, $size);
			$image = $image_url[0];
			return $image;
		}
		return null;
	}
}

if (!function_exists('api_all_booking_fields')) {
	function api_all_booking_fields()
	{
		return array(

			'no_people' => 'NOMBRE DE PERSONNE',
			'last_name' => 'NOM',
			'first_name' => 'PRÉNOM',
			'email' => 'ADRESSE ÉLECTRONIQUE',
			'telephone' => 'TEL',
			'last_name_2' => 'NOM',
			'first_name_2' => 'PRÉNOM',
			'last_name_3' => 'NOM',
			'first_name_3' => 'PRÉNOM',
			'last_name_4' => 'NOM',
			'first_name_4' => 'PRÉNOM',
			'last_name_5' => 'NOM',
			'first_name_5' => 'PRÉNOM',


		);
	}
}



if (!function_exists('api_all_booking_fields_headers')) {
	function api_all_booking_fields_headers()
	{

		$fields = api_all_booking_fields();
		$headers = array();

		foreach ($fields as $key => $value) {
			array_push($headers, $value);
		}


		return $headers;
	}
}



if (!function_exists('api_all_agenda_fields')) {
	function api_all_agenda_fields()
	{
		return array(
			'a_date' => "Date de l'événement",
			'a_time' => "Heure de l'événement",
			'address_id' => "Lieu de l'événement",
			'a_amount' => "Places pour l'événement",
			'archive_date' => "Date de fin",
			'is_required' => "Inscription à l'événement?",
		);
	}
}


if (!function_exists('api_all_agenda_fields_headers')) {
	function api_all_agenda_fields_headers()
	{

		$fields = api_all_agenda_fields();
		$headers = array();

		foreach ($fields as $key => $value) {
			array_push($headers, $value);
		}


		return $headers;
	}
}




if (!function_exists('api_process_metafield')) {
	function api_process_metafield($metafield)
	{


		$string = (sizeof($metafield) == 1)  ?  $metafield[0] : '';

		if (api_is_serialized($string)) {

			$array = unserialize($string);
			$new_array = array();
			foreach ($array as $value) {
				array_push($new_array, ($value));
			}
			$string = implode(' | ', $new_array);
		} else {
			// $string = str_replace(';', ' ', $string);
			// $string = str_replace(',', ' ', $string);
			// $string = str_replace('，', ' ', $string);
			$string = preg_replace("/(,|;|，,\")/", " ", $string);
			$string = preg_replace("/(\r|\n)/", " ", $string);
		}


		return $string;
	}
}





if (!function_exists('api_get_agenda_cats')) {
	function api_get_agenda_cats($agenda_ids)
	{
		global $conn;

		if (sizeof($agenda_ids) > 0) :
			// GET CATEGORIES
			$agenda_cat_sql = ' object_id = ';
			$agenda_cat_sql .= implode(' OR object_id =   ', $agenda_ids);
			$agenda_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
										FROM  `wp_term_relationships`
										LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
										LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
										WHERE ( $agenda_cat_sql ) AND taxonomy = 'agenda-category' ");
			$agenda_query->execute();
			# setting the fetch mode
			$agenda_query->setFetchMode(PDO::FETCH_OBJ);
			$agenda_categories =  $agenda_query->fetchAll();
			unset($conn);
			return $agenda_categories;
		endif;
	}
}


if (!function_exists('api_get_agenda_programs')) {
	function api_get_agenda_programs($agenda_ids)
	{
		global $conn;

		if (sizeof($agenda_ids) > 0) :
			// GET CATEGORIES
			$agenda_cat_sql = ' object_id = ';
			$agenda_cat_sql .= implode(' OR object_id =   ', $agenda_ids);
			$agenda_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
										FROM  `wp_term_relationships`
										LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
										LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
										WHERE ( $agenda_cat_sql ) AND taxonomy = 'agenda-program' ");
			$agenda_query->execute();
			# setting the fetch mode
			$agenda_query->setFetchMode(PDO::FETCH_OBJ);
			$agenda_categories =  $agenda_query->fetchAll();
			unset($conn);
			return $agenda_categories;
		endif;
	}
}


if (!function_exists('api_get_agenda_types')) {
	function api_get_agenda_types($agenda_ids)
	{
		global $conn;

		if (sizeof($agenda_ids) > 0) :
			// GET CATEGORIES
			$agenda_cat_sql = ' object_id = ';
			$agenda_cat_sql .= implode(' OR object_id =   ', $agenda_ids);
			$agenda_query = $conn->prepare("SELECT object_id, wp_terms.term_id, name, slug , parent
										FROM  `wp_term_relationships`
										LEFT JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
										LEFT JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
										WHERE ( $agenda_cat_sql ) AND taxonomy = 'agenda-type' ");
			$agenda_query->execute();
			# setting the fetch mode
			$agenda_query->setFetchMode(PDO::FETCH_OBJ);
			$agenda_categories =  $agenda_query->fetchAll();
			unset($conn);
			return $agenda_categories;
		endif;
	}
}





if (!function_exists('api_is_serialized')) {
	function api_is_serialized($string)
	{
		return (@unserialize($string) !== false);
	}
}







if (!function_exists('api_all_inscription_fields')) {
	function api_all_inscription_fields()
	{
		return array(

			'last_name' =>  'Nom de l\'élève',
			'first_name' => 'Prénom de l\'élève',
			'gender' =>  'Sexe',
			'date_of_birth' =>  'Date de naissance',
			'address' =>  'Adresse',
			'postcode' => 'N° postal',
			'town' => 'Ville',
			'title_guardian' => 'Titre',
			'last_name_guardian' =>  'Nom',
			'first_name_guardian' => 'Prénom',
			'address_guardian' => 'Adresse si différente de l\'élève',
			'postcode_guardian' => 'N° postal',
			'town_guardian' => 'Ville',
			'telephone_private' => 'Téléphone privé',
			'telephone_professional' => 'Téléphone professionnel',
			'telephone_portable' => 'Téléphone portable',
			'email' =>  'Courriel',
			'geneva_taxpayer' => 'Contribuable à Genève',
			'payment_frequency' => 'Je paierai ma facture en',
			'course_id' => 'Choix du cours',
			'instrument_chant_remarks' => 'Remarque',
			'prof_inst_chant' => 'Professeur Instr. / chant',
			'location_id' => 'Lieu',
			'other_place_possible' => 'Autre lieu possible',
			// 'other_place_possible_ids' => 'Autres lieux possibles',
			'course_id_second_choice' => 'Second choix',
			'formation_musicale' => 'Formation musicale',
			'musical_remarks' => 'Remarques',
			'prof_musical' => 'Professeur FM',
			'musical_location_id' => 'Lieu',
			'musical_other_place_possible' => 'Autre lieu possible',
			// 'musical_other_place_possible_ids' => 'Autres lieux possibles',
			'authorisation_photo' => 'Autorisation photo',
			'terms' => 'Conditions générales',
			'date_inscription' => 'Date de l’inscription',
			'how_know_school' => 'Comment avez-vous eu connaissance de notre école?',
			'message' => 'Remarques si nécessaire',
			'unused' => 'Liste déroulante',
			'ip_address' => 'IP'
			//    'inscription_year' => 'Inscription pour l\'année ',
			//    'musical_level' => 'Niveau musical ',
			// 'choix_tarif' => 'Choix du tarif ',
			// 'choix_tarif_collectif' => 'Choix du tarif – cours collectif ',
			//    'course_type' => 'course_type',




		);
	}
}



// Nom de l'élève;Prénom de l'élève;Sexe;Date de naissance;Adresse;N° postal;Ville;Titre;Nom;Prénom;Adresse si différente de l'élève;N°postal;Ville;Téléphone privé;Téléphone professionnel;Téléphone portable;Courriel;Contribuable à Genève;Je paierai ma facture en;Choix du cours;Professeur;Lieu;Autre lieu possible;Autorisation photo;Conditions générales;Conditions générales;Date de l’inscription;Comment avez-vous eu connaissance de notre école?;Remarques si nécessaire;IP;ID

if (!function_exists('api_all_inscription_fields_47musicale')) {
	function api_all_inscription_fields_47musicale()
	{
		return array(

			'last_name' =>  'Nom de l\'élève',
			'first_name' => 'Prénom de l\'élève',
			'gender' =>  'Sexe',
			'date_of_birth' =>  'Date de naissance',
			'address' =>  'Adresse',
			'postcode' => 'N° postal',
			'town' => 'Ville',
			'title_guardian' => 'Titre',
			'last_name_guardian' =>  'Nom',
			'first_name_guardian' => 'Prénom',
			'address_guardian' => 'Adresse si différente de l\'élève',
			'postcode_guardian' => 'N°postal',
			'town_guardian' => 'Ville',
			'telephone_private' => 'Téléphone privé',
			'telephone_professional' => 'Téléphone professionnel',
			'telephone_portable' => 'Téléphone portable',
			'email' =>  'Courriel',
			'geneva_taxpayer' => 'Contribuable à Genève',
			'payment_frequency' => 'Je paierai ma facture en',
			'course_id' => 'Choix du cours',
			'unused' => 'Professeur',
			'location_id' => 'Lieu',
			'other_place_possible' => 'Autre lieu possible',
			// 'other_place_possible_ids' => 'Autres lieux possibles',
			'authorisation_photo' => 'Autorisation photo',
			'terms' => 'Conditions générales',
			'unused2' => 'Conditions générales',
			'date_inscription' => 'Date de l’inscription',
			'how_know_school' => 'Comment avez-vous eu connaissance de notre école?',
			'message' => 'Remarques si nécessaire',
			'ip_address' => 'IP'





		);
	}
}



if (!function_exists('api_remove_spaces_from_number')) {
	function api_remove_spaces_from_number($number)
	{
		return str_replace(" ", "", $number);
	}
}



if (!function_exists('api_number_is_french')) {
	function api_number_is_french($number)
	{
		$number =  api_remove_spaces_from_number($number);
		if (substr($number, 0, 4) == '0033') {
			return true;
		} else if (substr($number, 0, 3) == '+33') {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('api_split_number_into_chunks')) {
	function api_split_number_into_chunks($number, $chunks)
	{
		$offset = 0;
		$ret = array();

		foreach ($chunks as $length) {
			$bit = substr($number, $offset, $length);
			$offset += $length;
			array_push($ret, $bit);
		}

		return implode(' ', $ret);
	}
}


if (!function_exists('api_get_id_from_object')) {
	function api_get_id_from_object($obj)
	{
		return $obj->ID;
	}
}
if (!function_exists('api_get_wid_from_object')) {
	function api_get_wid_from_object($obj)
	{
		return $obj->wid;
	}
}
if (!function_exists('api_get_meta_value_from_object')) {
	function api_get_meta_value_from_object($obj)
	{
		return $obj->meta_value;
	}
}
if (!function_exists('api_get_slug_from_object')) {
	function api_get_slug_from_object($obj)
	{
		return $obj->slug;
	}
}



if (!function_exists('api_format_phone_number')) {
	function api_format_phone_number($number)
	{

		$number = strval($number);

		$number = api_remove_spaces_from_number($number);


		if (strlen($number) > 5) {


			if (api_number_is_french($number)) {

				// replace +33 with 0033
				$exp = explode('+33', $number);
				if (sizeof($exp) == 2) {
					$goodpart =  $exp[1];
					$number =  '0033' . $goodpart;
				}

				// replace 00330X with 0033X
				$exp = explode('00330', $number);
				if (sizeof($exp) == 2) {
					$goodpart = $exp[1];
					$number =  '0033' . $goodpart;
				}

				// split into chunks of 7, 2, 2, 2, 2, 2, 2, 2
				$chunks = array(7, 2, 2, 2, 2, 2, 2, 2);
				$number = api_split_number_into_chunks($number, $chunks);

				// return french phone number
				return  $number;
			} else {

				// remove +41
				$exp = explode('+41', $number);
				if (sizeof($exp) == 2) {
					$goodpart =  $exp[1];
					$number =  $goodpart;
				}

				// remove 0041
				$exp = explode('0041', $number);
				if (sizeof($exp) == 2) {
					$goodpart =  $exp[1];
					$number =  $goodpart;
				}

				// remove any more pluses from beginning
				if ($number[0] == '+') {
					$number = substr($number, 1);
				}


				// add zero to start of number if not there
				if ($number[0] != '0') {
					$number = '0' . $number;
				}


				// split into chunks of 3, 3, 2, 2, 2, 2, 2, 2, 2
				$chunks = array(3, 3, 2, 2, 2, 2, 2, 2, 2);
				$number = api_split_number_into_chunks($number, $chunks);

				// return suisse phone number
				return  $number;
			}
		} else { // $number is very short
			return $number;
		}
	}
}


if (!function_exists('api_format_address')) {
	function api_format_address($address, $housenumber, $postcode)
	{
		if ($housenumber && $housenumber != null && $housenumber != '') {

			if ($postcode && $postcode != null && $postcode != '') {
				$postcode = api_remove_spaces_from_number($postcode);
				if (strlen($postcode) == 5) {
					return $housenumber  . ' ' .  $address;
				} else {
					return $address . ' ' . $housenumber;
				}
			} else {
				return $address . ' ' . $housenumber;
			}
		} else {
			return $address;
		}
	}
}


if (!function_exists('api_get_result_from_array')) {
	function api_get_result_from_array($collection, $inscription)
	{
		$metafield = array_filter(
			$collection,
			function ($e)  use ($inscription) {
				return $e->post_id == $inscription->ID;
			}
		);
		// $metafield =  array_values(array_map(create_function('$p', 'return $p->meta_value;'), $metafield));
		$metafield =  array_values(array_map('api_get_meta_value_from_object', $metafield));


		// turn it into a proper string to output
		return api_process_metafield($metafield);
	}
}
