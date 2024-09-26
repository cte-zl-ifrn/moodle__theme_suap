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


$items_theme_suap = [
    [
        'link' => [
            'title' => 'Admin',
            'url' => $CFG->wwwroot . '/admin/search.php',
            
        ]
    ],
   
];

$teste = "abacaxi";
$userid = $USER->id;

$templatecontext = [
    // 'items_theme_suap' => $items_theme_suap 
    'teste' => $teste,  
    'userid' => $userid  
];

echo $OUTPUT->render_from_template('core/user_action_menu_items', $templatecontext);
