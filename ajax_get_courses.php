<?php

require_once(__DIR__ . '/../../config.php');

use core_course\course;

$current_page = max(0, optional_param('page', 0, PARAM_INT));
$courses_per_page = max(1, optional_param('limit', 8, PARAM_INT));
$query = optional_param('q', '', PARAM_TEXT);

// Nega requisições que não sejam internas
$host = $_SERVER['HTTP_HOST'];
$referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;
if (!$referer || $referer['host'] !== $host) {
    echo json_encode(['error' => 'Access denied.']);
    die;
}

$categories = $DB->get_records('course_categories', null, '', 'id, name');
$category_names = [];
foreach ($categories as $category) {
    $category_names[$category->id] = $category->name;
}

if (!empty($query)) {
    $sql = "
        SELECT id, fullname, category
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
    $size = $DB->count_records('course', ['visible' => 1, 'fullname' => $query]) - 1;
} else {
    $sql = "
        SELECT id, fullname, category
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
}

$courses = $DB->get_records_sql($sql, $params);

$courses_response = [];
foreach ($courses as $course) {
    $image_url = \core_course\external\course_summary_exporter::get_course_image($course);
    if (empty($image_url)) {
        $image_url = "{$CFG->wwwroot}/theme/suap/pix/default.jpeg";
    }

    $course_response = new stdClass();
    $course_response->id = $course->id;
    $course_response->fullname = $course->fullname;
    $course_response->category_name = $category_names[$course->category];
    $course_response->image_url = $image_url;
    $courses_response[] = $course_response;
}

echo json_encode(['total' => $size, 'courses' => $courses_response]);
die;
