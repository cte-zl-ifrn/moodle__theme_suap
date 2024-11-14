<?php

require_once('../../../config.php');

use core_course\course;

$current_page = max(0, optional_param('page', 0, PARAM_INT));
$courses_per_page = max(1, optional_param('limit', 8, PARAM_INT));
$query = optional_param('search', '', PARAM_TEXT);
$workload = optional_param('workload', '', PARAM_TEXT);
$certificate = optional_param('certificate', '', PARAM_TEXT);
$lang = optional_param('lang', '', PARAM_TEXT);
$learningpath = optional_param('learningpath', '', PARAM_TEXT);

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
    $category_obj->url = "{$CFG->wwwroot}/course/management.php?categoryid={$category->id}";
    $categories[$category->id] = $category_obj;
}

$sql_conditions = [];
$params = [];

if (!empty($query)) {
    $sql_conditions[] = "fullname LIKE :query";
    $params['query'] = '%' . $query . '%';
}

// TODO: Validar se lang é um valor válido entre os idiomas disponíveis
if (!empty($lang)) {
    $lang_values = explode(',', $lang);
    list($lang_query, $lang_params) = $DB->get_in_or_equal($lang_values, SQL_PARAMS_NAMED, 'lang');
    $sql_conditions[] = "lang $lang_query";
    $params = array_merge($params, $lang_params);
}

// TODO: Validar workloads

if (!empty($learningpath)) {
    $learningpath_values = explode(',', $learningpath);

    foreach ($learningpath_values as $value) {
        if (empty($value) || !is_numeric($value) || $value <= 0) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Parâmetro 'learningpath' inválido. Certifique-se de que os valores são numéricos e estão bem formatados."]);
            exit;
        }
    }

    list($learningpath_query, $learningpath_params) = $DB->get_in_or_equal($learningpath_values, SQL_PARAMS_NAMED, 'learningpath');
    $sql_conditions[] = "id IN (SELECT courseid FROM {suap_learning_path_course} WHERE learningpathid $learningpath_query)";
    $params = array_merge($params, $learningpath_params);
}

$sql = "
SELECT id, fullname, category, lang
FROM {course}
WHERE visible = 1 AND id != 1" . (!empty($sql_conditions) ? ' AND ' . implode(' AND ', $sql_conditions) : '') . "
ORDER BY id";

// Executa a consulta de forma segura
$courses = $DB->get_records_sql($sql, $params);

$courses_response = [];
foreach ($courses as $course) {
    $image_url = \core_course\external\course_summary_exporter::get_course_image($course);
    if (empty($image_url)) {
        $image_url = "{$CFG->wwwroot}/theme/suap/pix/default.jpeg";
    }

    $category = $categories[$course->category];
    $custom_fields_metadata = \core_course\customfield\course_handler::create()->export_instance_data_object($course->id, true);

    if (!empty($workload)) {
        $workload_values = explode(',', $workload);
        $is_valid_workload = false;
        $course_workload = (int) $custom_fields_metadata->carga_horaria;
        if ($course_workload == 0) {
            continue;
        }
        foreach ($workload_values as $value) {
            
            if ($course_workload <= (int)$value) {
                $is_valid_workload = true;
                break;
            }
        }
        if (!$is_valid_workload) {
            continue;
        }
    }

    if (!empty($certificate)) {
        $certificate_values = explode(',', $certificate);
        $is_valid_certificate = false;
        foreach ($certificate_values as $value) {
            if ($custom_fields_metadata->tem_certificado == $value) {
                $is_valid_certificate = true;
                break;
            }
        }
        if (!$is_valid_certificate) {
            continue;
        }
    }

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

$size = count($courses_response);
$courses_response = array_slice($courses_response, $current_page * $courses_per_page, $courses_per_page);

echo json_encode(['total' => $size, 'courses' => $courses_response, 'baseurl' => $CFG->wwwroot]);
die;
