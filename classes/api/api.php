<?php
/**
 * Class used to return information to display for the message area.
 *
 * @copyright  2024 DEAD IFRN
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_suap\api;

// use core_favourites\local\entity\favourite;

defined('MOODLE_INTERNAL') || die();

class api {

    /**
     * The action for reading a message.
     */
    const MESSAGE_ACTION_READ = 1;

    /**
     * The action for deleting a message.
     */
    const MESSAGE_ACTION_DELETED = 2;

    /**
     * The action for reading a message.
     */
    const CONVERSATION_ACTION_MUTED = 1;

    /**
     * The privacy setting for being messaged by anyone within courses user is member of.
     */
    const MESSAGE_PRIVACY_COURSEMEMBER = 0;

    /**
     * The privacy setting for being messaged only by contacts.
     */
    const MESSAGE_PRIVACY_ONLYCONTACTS = 1;

    /**
     * The privacy setting for being messaged by anyone on the site.
     */
    const MESSAGE_PRIVACY_SITE = 2;

    /**
     * An individual conversation.
     */
    const MESSAGE_CONVERSATION_TYPE_INDIVIDUAL = 1;

    /**
     * A group conversation.
     */
    const MESSAGE_CONVERSATION_TYPE_GROUP = 2;

    /**
     * A self conversation.
     */
    const MESSAGE_CONVERSATION_TYPE_SELF = 3;

    /**
     * The state for an enabled conversation area.
     */
    const MESSAGE_CONVERSATION_ENABLED = 1;

    /**
     * The state for a disabled conversation area.
     */
    const MESSAGE_CONVERSATION_DISABLED = 0;

    /**
     * The max message length.
     */
    const MESSAGE_MAX_LENGTH = 4096;

    /**
     * Get all unread conversations for the user without grouping by type or favourites.
     *
     * @param int $userid The id of the user whose unread conversations we'll fetch.
     * @return array The list of unread conversations.
     */
    public static function get_all_unread_conversations(int $userid): array {
        global $DB;

        // SQL to get all unread conversations the user is in.
        $sql = 'SELECT conv.id as conversationid, conv.type, count(m.id) as unreadcount,
                    m.smallmessage, m.fullmessage, m.timecreated,
                    u.firstname, u.lastname
                    FROM {message_conversations} conv
            INNER JOIN {messages} m
                    ON conv.id = m.conversationid
            INNER JOIN {message_conversation_members} mcm
                    ON conv.id = mcm.conversationid
            INNER JOIN {user} u
                    ON u.id = m.useridfrom
            LEFT JOIN {message_user_actions} mua
                    ON (mua.messageid = m.id AND mua.userid = ? AND 
                        (mua.action = ? OR mua.action = ?))
                WHERE mcm.userid = ?
                AND m.useridfrom != ?
                AND mua.id is NULL
                AND conv.enabled = 1
            GROUP BY conv.id, m.smallmessage, m.fullmessage, m.timecreated, u.firstname, u.lastname
            ORDER BY m.timecreated DESC';

        // Execute the query with the provided parameters.
        $unreadconversations = $DB->get_records_sql($sql, [
            $userid,
            self::MESSAGE_ACTION_READ,
            self::MESSAGE_ACTION_DELETED,
            $userid,
            $userid
        ]);

        // Prepare the return array with the conversation id and unread count.
        $conversations = [];
        foreach ($unreadconversations as $unreadconv) {
            $conversations[] = [
                'conversationid' => $unreadconv->conversationid,
                'unreadcount' => $unreadconv->unreadcount,
                'smallmessage' => $unreadconv->smallmessage,
                'fullmessage' => $unreadconv->fullmessage,  
                'timecreated' => userdate($unreadconv->timecreated),
                'sendername' => $unreadconv->firstname . ' ' . $unreadconv->lastname,
            ];
        }

        return ['conversations' => $conversations];
    }

}