<?php

namespace theme_suap\output\core;

use core_course\course;
use html_writer;
// use context_course;

class course_renderer extends \core_course_renderer {
    public function frontpage() {
        global $CFG, $SITE;

        $output = '';

        if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
            $frontpagelayout = $CFG->frontpageloggedin;
        } else {
            $frontpagelayout = $CFG->frontpage;
        }

        foreach (explode(',', $frontpagelayout) as $v) {
            switch ($v) {
                // Display the main part of the front page.
                case FRONTPAGENEWS:
                    if ($SITE->newsitems) {
                        // Print forums only when needed.
                        require_once($CFG->dirroot .'/mod/forum/lib.php');
                        if (($newsforum = forum_get_course_forum($SITE->id, 'news')) &&
                                ($forumcontents = $this->frontpage_news($newsforum))) {
                            $newsforumcm = get_fast_modinfo($SITE)->instances['forum'][$newsforum->id];
                            $output .= $this->frontpage_part('skipsitenews', 'site-news-forum',
                                $newsforumcm->get_formatted_name(), $forumcontents);
                        }
                    }
                    break;

                case FRONTPAGEENROLLEDCOURSELIST:
                    $mycourseshtml = $this->frontpage_my_courses();
                    if (!empty($mycourseshtml)) {
                        $output .= $this->frontpage_part('skipmycourses', 'frontpage-course-list',
                            get_string('mycourses'), $mycourseshtml);
                    }
                    break;

                case FRONTPAGEALLCOURSELIST:                    
                    $output .= $this->render_courses();
                    break;

                case FRONTPAGECATEGORYNAMES:
                    $output .= $this->frontpage_part('skipcategories', 'frontpage-category-names',
                        get_string('categories'), $this->frontpage_categories_list());
                    break;

                case FRONTPAGECATEGORYCOMBO:
                    $output .= $this->frontpage_part('skipcourses', 'frontpage-category-combo',
                        get_string('courses'), $this->frontpage_combo_list());
                    break;

                case FRONTPAGECOURSESEARCH:
                    $output .= $this->box($this->course_search_form(''), 'd-flex justify-content-center');
                    break;

            }
            $output .= '<br />';
        }

        return $output;
    }

    protected function get_course_category($categoryid) {
        global $DB;

        return $DB->get_record('course_categories', ['id' => $categoryid]);
    }

    protected function render_courses() {
        global $OUTPUT;

        $output = html_writer::start_div('course-area');

        for ($i = 0; $i < 8; $i++) {
            $output .= html_writer::start_div('course-card skeleton');

            $output .= html_writer::start_div('course-image-container skeleton-image');
            $output .= html_writer::tag('div', '', ['class' => 'skeleton-image-placeholder skeleton']);
            $output .= html_writer::end_div();
            
            $output .= html_writer::tag('span', '', ['class' => 'skeleton-category skeleton']);
            $output .= html_writer::tag('p', '', ['class' => 'skeleton-name skeleton']);
        
            $output .= html_writer::end_div();
        }

        $output .= html_writer::end_div();

        return $output;
    }
}