<?php


$location_id =  intval($_GET['location_id']);
$post = get_post($location_id);



$post->description = get_field('description', $post->ID);
$post->responsible = get_field('responsible', $post->ID);
$post->addresse = get_field('addresse', $post->ID);


$cours_complementaires = get_field('cours_complementaires', $post->ID);
// convert this to html
$post->cours_complementaires = $cours_complementaires;



// remove unncessary params
$unncessary_params = ['comment_count', 'post_status', 'post_mime_type',  'ping_status', 'comment_status', 'post_parent', 'post_date_gmt',  'post_modified_gmt', 'post_password',  'post_excerpt', 'pinged', 'to_ping', 'filter', 'post_content_filtered'];
foreach ($unncessary_params as $up) {
    unset($post->$up);
}





echo json_encode($post,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
