<?php


$location_id =  intval($_GET['location_id']);
$post = get_post($location_id);



$post->description = get_field('description', $post->ID);
$post->responsible = get_field('responsible', $post->ID);
$post->addresse = get_field('addresse', $post->ID);


// $cours_complementaires = get_field('cours_complementaires', $post->ID);


$courses = courses_from_location_id($location_id);


$location_link = $post->guid;
$courses_html = '<!--Start--><h2 id="location_name"><a href="' . $location_link . '" id="location_link">' . $post->post_title  . '</a></h2>';
$courses_html .= '<p id="location_description">' . $post->description  . '</p>';
$courses_html .= '<p id="location_responsible">' . $post->responsible  . '</p>';
$courses_html .= '<p id="location_addresse">' . $post->addresse  . '</p>';
$courses_html .= '<h3>Disciplines enseignées:</h3><div class="location_courses_container">';



foreach ($courses as $course) {
    $times = get_field('times',  $course->ID);
    // api_remove_unnecessary($course);
    // $course->times = $times;
    $courses_html .= '<div class="single_course_for_location">';
    $courses_html .= '<h4><a href="' . $course->guid . '">' .  $course->post_title . '</a></h4><ul>';
    foreach ($times as $time) {
        if ($time['location']  && $time['location']->ID == $post->ID) {
            if ($time['teachers']) {
                foreach ($time['teachers'] as $teacher) :
                    $courses_html .= ' <li>' . $teacher->post_title .  '</li>';
                endforeach;
            }
        }
    };
    $courses_html .= '</ul></div>';
}

$courses_html .= '</div><!-- end -->';
$post->courses_html = $courses_html;
// $post->courses = $courses;







api_remove_unnecessary($post);





echo json_encode($post,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
