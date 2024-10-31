<?php

require_once('../../../config.php');

use core_course\course;

$current_page = max(0, optional_param('page', 0, PARAM_INT));
$courses_per_page = max(1, optional_param('limit', 8, PARAM_INT));
$query = optional_param('search', '', PARAM_TEXT);

// Nega requisições que não sejam internas
$host = $_SERVER['HTTP_HOST'];
$referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
if (!$referer || $referer['host'] !== $host) {
    echo json_encode(['error' => 'Access denied.']);
    die;
}

$categories_records = $DB->get_records('course_categories', null, '', 'id, name');
$categories = [];
foreach ($categories_records as $category) {
    $category_obj = new stdClass();
    $category_obj->name = $category->name;
    $category_obj->url = "{$CFG->wwwroot}/course/index.php?categoryid={$category->id}";
    $categories[$category->id] = $category_obj;
}

if (empty($query)) {
    $sql = "
    SELECT id, fullname, category, lang
    FROM {course}
    WHERE visible = 1 AND id != 1
    ORDER BY id
    LIMIT :limit OFFSET :offset
    ";
    $params = [
        'limit' => $courses_per_page,
        'offset' => $current_page * $courses_per_page
    ];
    $size = $DB->count_records('course', ['visible' => 1]) - 1;
} else {
    $sql = "
        SELECT id, fullname, category, lang
        FROM {course}
        WHERE visible = 1 AND id != 1 AND fullname LIKE :query
        ORDER BY id
        LIMIT :limit OFFSET :offset
    ";
    $params = [
        'query' => "%{$query}%",
        'limit' => $courses_per_page,
        'offset' => $current_page * $courses_per_page
    ];
    $size = $DB->count_records_sql("
        SELECT COUNT(*)
        FROM {course}
        WHERE visible = 1 AND id != 1 AND fullname LIKE :query", 
        ['query' => "%{$query}%"]
    );
}

$courses = $DB->get_records_sql($sql, $params);

$courses_response = [];
foreach ($courses as $course) {
    $image_url = \core_course\external\course_summary_exporter::get_course_image($course);
    if (empty($image_url)) {
        $image_url = "{$CFG->wwwroot}/theme/suap/pix/default.jpeg";
    }

    $custom_fields_metadata = \core_course\customfield\course_handler::create()->export_instance_data_object($course->id, true);
    $category = $categories[$course->category];

    $course_response = new stdClass();
    $course_response->has_certificate = $custom_fields_metadata->tem_certificado;
    $course_response->workload = $custom_fields_metadata->carga_horaria;
    $course_response->id = $course->id;
    $course_response->fullname = $course->fullname;
    $course_response->category_name = $category->name;
    $course_response->category_url = $category->url;
    $course_response->image_url = $image_url;
    $course_response->lang = $course->lang;
    $course_response->url = "{$CFG->wwwroot}/course/view.php?id={$course->id}";

    $courses_response[] = $course_response;
}

echo json_encode(['total' => $size, 'courses' => $courses_response, 'baseurl' => $CFG->wwwroot]);
die;
