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
 * External message API
 *
 * @package    core_message
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_external\external_api;
use core_external\external_format_value;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use core_external\external_warnings;
use core_external\util;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/message/lib.php");
require_once($CFG->dirroot . "/message/externallib.php");

/**
 * Message external functions
 *
 * @package    core_message
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */

//  core_message_external
class theme_suap_external extends core_message_external {

    /**
     * Get conversations parameters.
     *
     * @return external_function_parameters
     * @since 3.6
     */
    public static function get_all_unread_conversations_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'The id of the user who we are viewing conversations for'),
            )
        );
    }

    /**
     * Get the list of conversations for the user.
     *
     * @param int $userid The id of the user who is performing the search
     * @param int $limitfrom
     * @param int $limitnum
     * @param int|null $type
     * @param bool|null $favourites
     * @param bool $mergeself whether to include self-conversations (true) or ONLY private conversations (false)
     *             when private conversations are requested.
     * @return stdClass
     * @throws \moodle_exception if the messaging feature is disabled on the site.
     * @since 3.2
     */
    public static function get_all_unread_conversations($userid) {
        global $CFG, $USER;

        // All the standard BL checks.
        if (empty($CFG->messaging)) {
            throw new moodle_exception('disabled', 'message');
        }

        $params = array(
            'userid' => $userid,
        );
        $params = self::validate_parameters(self::get_conversations_parameters(), $params);

        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);

        if (($USER->id != $params['userid']) && !has_capability('moodle/site:readallmessages', $systemcontext)) {
            throw new moodle_exception('You do not have permission to perform this action.');
        }

        $conversations = \core_message\api::get_all_unread_conversations(
            $params['userid'],
        );


        return (object) ['conversations' => $conversations];
    }

    /**
     * Get conversations returns.
     *
     * @return external_single_structure
     * @since 3.6
     */
    public static function get_all_unread_conversations_returns() {
        return new external_single_structure(
            [
                'conversations' => new external_multiple_structure(
                    self::get_conversation_structure(true)
                )
            ]
        );
    }



}