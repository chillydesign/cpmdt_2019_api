<?php


$location_id =  intval($_GET['location_id']);
$post = get_post($location_id);



$post->description = get_field('description', $post->ID);
$post->responsible = get_field('responsible', $post->ID);
$post->addresse = get_field('addresse', $post->ID);


// $cours_complementaires = get_field('cours_complementaires', $post->ID);



$courses_html = '<!--Start-->';
$courses = courses_from_location_id($location_id);



foreach ($courses as $course) {
    $times = get_field('times',  $course->ID);
    $courses_html .= '<div class="single_course_for_location">';
    $courses_html .= '<h4><a href="' . $course->guid . '">' .  $course->post_title . '</a></h4><ul>';


    foreach ($times as $time) {
        if ($time['location']  && $time['location']->ID == $location_id) {
            if ($time['teachers']) {
                foreach ($time['teachers'] as $teacher) :
                    $courses_html .= ' <li>' . $teacher->post_title .  '</li>';
                endforeach;
            }
        }
    };

    $courses_html .= '</ul></div>';
}


$courses_html .= '<!-- end -->';


$post->courses_html = $courses_html;


$courses;



api_remove_unnecessary($post);





echo json_encode($post,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
