<?php

defined('MOODLE_INTERNAL') || die();

use core_course\course;

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

require_once($CFG->dirroot . '/theme/suap/lib.php');

global $DB;

// require_once(__DIR__ . '/../../config.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $secondary = $PAGE->secondarynav;

    if ($secondary->get_children_key_list()) {
        $tablistnav = $PAGE->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
        $extraclasses[] = 'has-secondarynavigation';
    }

    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

// A frontpage utiliza a largura maior
$extraclasses[] = 'layout-width-expanded';

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$conf = get_config('theme_suap');
$frontpage_buttons_configtextarea = parse_configtextarea_string($conf->frontpage_buttons_configtextarea);
$frontpage_buttons_configtextarea_when_user_logged = parse_configtextarea_string($conf->frontpage_buttons_configtextarea_when_user_logged);

$courses_request_url = $CFG->wwwroot . '/theme/suap/api/get_courses.php';
$PAGE->requires->js_call_amd('theme_suap/frontpage_courses', 'init', [$courses_request_url]);

$learningpaths_records = $DB->get_records('suap_learning_path', null, '', 'id, name');
$learningpaths = [];
foreach ($learningpaths_records as $learningpath) {
    $learningpath_obj = new stdClass();
    $learningpath_obj->id = $learningpath->id;
    $learningpath_obj->name = $learningpath->name;
    $learningpaths[] = $learningpath_obj;
}

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'isloggedin' => isloggedin(),
    'userid' => $USER->id,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'frontpage_title' => $conf->frontpage_title,
    'hero_title' => $conf->hero_title,
    'hero_subtitle' => $conf->hero_subtitle,
    'hero_first_column_number' => $conf->hero_first_column_number,
    'hero_first_column_description' => $conf->hero_first_column_description,
    'hero_first_column_text' => $conf->hero_first_column_text,
    'hero_second_column_number' => $conf->hero_second_column_number,
    'hero_second_column_description' => $conf->hero_second_column_description,
    'hero_second_column_text' => $conf->hero_second_column_text,
    'hero_third_column_number' => $conf->hero_third_column_number,
    'hero_third_column_description' => $conf->hero_third_column_description,
    'hero_third_column_text' => $conf->hero_third_column_text,
    'hero_fourth_column_number' => $conf->hero_fourth_column_number,
    'hero_fourth_column_description' => $conf->hero_fourth_column_description,
    'hero_fourth_column_text' => $conf->hero_fourth_column_text,
    'hero_button_text' => $conf->hero_button_text,
    'about_title' => $conf->about_title,
    'frontpage_buttons_configtextarea' => $frontpage_buttons_configtextarea,
    'frontpage_buttons_configtextarea_when_user_logged' => $frontpage_buttons_configtextarea_when_user_logged,
    'frontpage_main_courses_title' => $conf->frontpage_main_courses_title,
    'learningpaths' => $learningpaths
];

echo $OUTPUT->render_from_template('theme_suap/frontpage', $templatecontext);
