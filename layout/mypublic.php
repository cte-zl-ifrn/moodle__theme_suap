<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A drawer based layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

// User role for course context
if(isloggedin()) {
    $rolestr;
    $context = context_course::instance($COURSE->id);
    $roles = get_user_roles($context, $USER->id, true);

    if (empty($roles)) {
        $rolestr = "";
    } else {
        foreach ($roles as $role) {
            $rolestr[] = role_get_name($role, $context);
        }
        $rolestr = implode(', ', $rolestr);
    }

}

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$counterClose = get_user_preferences('theme_suap_counter_close');
if ($counterClose) {
    $extraclasses[] = 'counter-close';
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

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);
$navbar = $OUTPUT->navbar();

$isloggedin = isloggedin();
$is_admin = is_siteadmin($USER->id);

$userid = $USER->id;

//pega a preferencia no banco
$getUserPreference = get_user_preferences('visual_preference');

// Define a lista de items no formato esperado
$items_theme_suap = [];

// Adicionar condicionalmente os itens de administrador
if ($is_admin) {
    $items_theme_suap[] = [
        'id' => 'admin_item_1',
        'class' => 'the-last',
        'link' => [
            'title' => 'Admin',
            'url' => $CFG->wwwroot . '/admin/search.php',
            'pixicon' => 't/admin'
        ]
    ];

    $items_theme_suap[] = [
        'id' => 'admin',
        'class' => 'the-last',
        'link' => [
            'title' => 'Courses',
            'url' => $CFG->wwwroot . '/my/courses.php',
            'pixicon' => 't/courses'
        ]
    ];
}

// require_once("../config.php");
// require_once($CFG->dirroot.'/user/profile/lib.php');
// require_once($CFG->dirroot.'/user/lib.php');
// require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/badgeslib.php');

global $DB;

$id             = optional_param('id', 0, PARAM_INT); // User id.
$courseid       = optional_param('course', SITEID, PARAM_INT); // course id (defaults to Site).
// $showallcourses = optional_param('showallcourses', 0, PARAM_INT);

// See your own profile by default.
if (empty($id)) {
    require_login();
    $id = $USER->id;
}

if($courseid != 1) {
    $notCourseContextProfile = true;    
}

$user = core_user::get_user($id);
// $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$has_edit_button = false;

if ($is_admin || $USER->id == $id) {
    $useremail = $user->email;
    $has_edit_button = true;
}

$edit_url = new moodle_url('/user/editadvanced.php', array(
    'id' => $id,
    'course' => $courseid,
    'returnto' => 'profile'
));

// Get profile image and alternative text
$profile_picture = new user_picture($user);
$profile_picture->size = 50;
$profile_picture_url = $profile_picture->get_url($PAGE)->out();
$profile_picture_alt = $user->imagealt;

$all_certificates = array();

// Get user certificates (plugin custom Certificates)
$tool_certificates = $DB->get_records('tool_certificate_issues', array('userid' => $id));

// Get user certificates (plugin custom certificates)
$custom_certificates = $DB->get_records('customcert_issues', array('userid' => $id));

if (!empty($tool_certificates)) {
    foreach ($tool_certificates as $cert) {
        $certificate_name = $DB->get_field('tool_certificate_templates', 'name', array('id' => $cert->id));
        
        $contextid = context_system::instance()->id;  // Obtém o contexto do sistema
        $fileurl = moodle_url::make_pluginfile_url($contextid, 'tool_certificate', 'issues', $cert->id, '/', $cert->code . '.pdf', false);

        $all_certificates[] = array(
            'certificateid' => $cert->id,
            'datereceived' => date('d/m/Y', $cert->timecreated),
            'name' => $certificate_name,
            'link' => $fileurl
        );
    }
}

if (!empty($custom_certificates)) {
    foreach ($custom_certificates as $cert) {
        $certificate_name = $DB->get_field('customcert', 'name', array('id' => $cert->customcertid));

        $all_certificates[] = array(
            'certificateid' => $cert->customcertid,
            'dateraw' => $cert->timecreated,
            'datereceived' => date('d/m/Y', $cert->timecreated),
            'name' => $certificate_name,
            'link' => new moodle_url('/mod/customcert/my_certificates.php', array(
                'userid' => $id,
                'certificateid' => $cert->customcertid,
                'downloadcert' => 1
            ))
        );
    }
}

// Ordena o array $all_certificates em ordem decrescente
usort($all_certificates, function ($a, $b) {
    return $b['dateraw'] - $a['dateraw'];
});


// Get user badges
$badges = badges_get_user_badges($id);
$badges_formated = array();

if (!empty($badges)) {
    foreach ($badges as $badge) {        
        $badgeObj = new badge($badge->id);
        $badge_context = $badgeObj->get_context();
        $imageurl = moodle_url::make_pluginfile_url($badge_context->id, 'badges', 'badgeimage', $badge->id, '/', 'f1', FALSE); 
        
        $badge_link = new moodle_url('/badges/badge.php', ['hash' => $badge->uniquehash]);

        $badges_formated[] = array(
            'name' => $badge->name,
            'description' => $badge->description,
            'datereceived' => date('d/m/Y', $badge_issued->dateissued),
            'imageurl' => $imageurl,
            'link' => $badge_link
        );
    }
}

$templatecontext = [
    'userfullname' => fullname($user),
    'useremail' => $useremail,
    'haseditbutton' => $has_edit_button,
    'editprofile' => $edit_url,
    'userdescription' => $user->description,
    'userpictureurl' => $profile_picture_url,
    'userpicturealt' => $profile_picture_alt,
    'hascertificates' => !empty($custom_certificates),
    'certificates' => $all_certificates,
    'hasbadges' => !empty($badges),
    'badges' => $badges_formated,
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
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
    'navbar' => $navbar,
    'userid' => $userid,
    'rolename' => $rolestr,
    'isloggedin' => $isloggedin,
    'is_admin' => $is_admin,
    'items_theme_suap' => $items_theme_suap, 
    'getUserPreference' => $getUserPreference,
    'not_course_context_profile' => $notCourseContextProfile
];
echo $OUTPUT->render_from_template('theme_suap/layouts/mypublic', $templatecontext);