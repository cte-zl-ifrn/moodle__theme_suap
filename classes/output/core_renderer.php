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

namespace theme_suap\output;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_boost
 * @copyright  2024 DEAD IFRN, https://ead.ifrn.edu.br/portal/
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {

        $output = parent::standard_head_html();

        $output .= '<link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">';
        
        return $output;
    }
    /**
     * Renders the "breadcrumb" for all pages in boost.
     *
     * @return string the HTML for the navbar.
     */
    public function navbar(): string {
        $newnav = new \theme_suap\boostnavbar($this->page);
        return $this->render_from_template('core/navbar', $newnav);
    }

    // public function user_menu($user = null, $withlinks = null) {
    //     global $USER, $CFG;
    //     require_once($CFG->dirroot . '/user/lib.php');

    //     if (is_null($user)) {
    //         $user = $USER;
    //     }

    //     // Note: this behaviour is intended to match that of core_renderer::login_info,
    //     // but should not be considered to be good practice; layout options are
    //     // intended to be theme-specific. Please don't copy this snippet anywhere else.
    //     if (is_null($withlinks)) {
    //         $withlinks = empty($this->page->layout_options['nologinlinks']);
    //     }

    //     // Add a class for when $withlinks is false.
    //     $usermenuclasses = 'usermenu';
    //     if (!$withlinks) {
    //         $usermenuclasses .= ' withoutlinks';
    //     }

    //     $returnstr = "";

    //     // If during initial install, return the empty return string.
    //     if (during_initial_install()) {
    //         return $returnstr;
    //     }

    //     $loginpage = $this->is_login_page();
    //     $loginurl = get_login_url();

    //     // Get some navigation opts.
    //     $opts = user_get_user_navigation_info($user, $this->page);

    //     if (!empty($opts->unauthenticateduser)) {
    //         $returnstr = get_string($opts->unauthenticateduser['content'], 'moodle');
    //         // If not logged in, show the typical not-logged-in string.
    //         if (!$loginpage && (!$opts->unauthenticateduser['guest'] || $withlinks)) {
    //             $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
    //         }

    //         return html_writer::div(
    //             html_writer::span(
    //                 $returnstr,
    //                 'login nav-link'
    //             ),
    //             $usermenuclasses
    //         );
    //     }

    //     $avatarclasses = "avatars";
    //     $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
    //     $usertextcontents = $opts->metadata['userfullname'];

    //     // Other user.
    //     if (!empty($opts->metadata['asotheruser'])) {
    //         $avatarcontents .= html_writer::span(
    //             $opts->metadata['realuseravatar'],
    //             'avatar realuser'
    //         );
    //         $usertextcontents = $opts->metadata['realuserfullname'];
    //         $usertextcontents .= html_writer::tag(
    //             'span',
    //             get_string(
    //                 'loggedinas',
    //                 'moodle',
    //                 html_writer::span(
    //                     $opts->metadata['userfullname'],
    //                     'value'
    //                 )
    //             ),
    //             ['class' => 'meta viewingas']
    //         );
    //     }

    //     // Role.
    //     if (!empty($opts->metadata['asotherrole'])) {
    //         $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
    //         $usertextcontents .= html_writer::span(
    //             $opts->metadata['rolename'],
    //             'meta role role-' . $role
    //         );
    //     }

        
    //     // User login failures.
    //     if (!empty($opts->metadata['userloginfail'])) {
    //         $usertextcontents .= html_writer::span(
    //             $opts->metadata['userloginfail'],
    //             'meta loginfailures'
    //         );
    //     }

    //     // MNet.
    //     if (!empty($opts->metadata['asmnetuser'])) {
    //         $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
    //         $usertextcontents .= html_writer::span(
    //             $opts->metadata['mnetidprovidername'],
    //             'meta mnet mnet-' . $mnet
    //         );
    //     }

    //     $returnstr .= html_writer::span(
    //         html_writer::span($usertextcontents, 'usertext me-1') .
    //         html_writer::span($avatarcontents, $avatarclasses),
    //         'userbutton'
    //     );

    //     // Create a divider (well, a filler).
    //     $divider = new action_menu_filler();
    //     $divider->primary = false;

    //     $am = new action_menu();
    //     $am->set_menu_trigger(
    //         $returnstr,
    //         'nav-link'
    //     );
    //     $am->set_action_label(get_string('usermenu'));
    //     $am->set_nowrap_on_items();
    //     if ($withlinks) {
    //         $navitemcount = count($opts->navitems);
    //         $idx = 0;
    //         foreach ($opts->navitems as $key => $value) {
    //             switch ($value->itemtype) {
    //                 case 'divider':
    //                     // If the nav item is a divider, add one and skip link processing.
    //                     $am->add($divider);
    //                     break;

    //                 case 'invalid':
    //                     // Silently skip invalid entries (should we post a notification?).
    //                     break;

    //                 case 'link':
    //                     // Process this as a link item.
    //                     $pix = null;
    //                     if (isset($value->pix) && !empty($value->pix)) {
    //                         $pix = new pix_icon($value->pix, '', null, ['class' => 'iconsmall']);
    //                     } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
    //                         $value->title = html_writer::img(
    //                             $value->imgsrc,
    //                             $value->title,
    //                             ['class' => 'iconsmall']
    //                         ) . $value->title;
    //                     }

    //                     $al = new action_menu_link_secondary(
    //                         $value->url,
    //                         $pix,
    //                         $value->title,
    //                         ['class' => 'icon']
    //                     );
    //                     if (!empty($value->titleidentifier)) {
    //                         $al->attributes['data-title'] = $value->titleidentifier;
    //                     }
    //                     $am->add($al);
    //                     break;
    //             }

    //             $idx++;

    //             // Add dividers after the first item and before the last item.
    //             if ($idx == 1 || $idx == $navitemcount - 1) {
    //                 $am->add($divider);
    //             }
    //         }
    //     }

    //     return html_writer::div(
    //         $this->render($am),
    //         $usermenuclasses
    //     );
    // }



}