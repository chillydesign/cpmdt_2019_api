<?php


$location_id =  intval($_GET['location_id']);
$post = get_post($location_id);



$post->description = get_field('description', $post->ID);
$post->responsible = get_field('responsible', $post->ID);
$post->addresse = get_field('addresse', $post->ID);


// $cours_complementaires = get_field('cours_complementaires', $post->ID);



$courses = courses_from_location_id($location_id);
foreach ($courses as $course) {
    $course->times = get_field('times',  $course->ID);
    api_remove_unnecessary($course);
}
// // convert this to html
$post->courses = $courses;



api_remove_unnecessary($post);





echo json_encode($post,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
