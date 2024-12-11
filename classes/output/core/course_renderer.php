<?php

namespace theme_suap\output\core;

use core_course\course;
use html_writer;
use stdClass;
use core_course\external\course_summary_exporter;

use core_course\customfield\course_handler;

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

    /**
     * Renders course info box.
     *
     * @param stdClass $course
     * @return string
     */
    public function course_info_box(stdClass $course) {
        global $OUTPUT, $DB;

        // Pega os custom fields que tiver no curso
        $handler = course_handler::create();
        $datas = $handler->get_instance_data($course->id, true);

        foreach ($datas as $data) {
            if (empty($data->get_value())) {
                continue;
            }
            // $cat = $data->get_field()->get_category()->get('name');
            $custom_fields[$data->get_field()->get('shortname')] = $data->get_value();
        }

        $categoryid = $course->category;
        $category = $DB->get_record('course_categories', ['id' => $categoryid]);

        $imageurl = course_summary_exporter::get_course_image($course);
        if (!$imageurl) {
            $imageurl = $CFG->wwwroot . '/theme/suap/pix/default-course-image.webp';
        }

        $enrolment_methods = enrol_get_instances($course->id, true);
        $enrolment_types = [];
        
        $self_enrolment = null;
        foreach ($enrolment_methods as $method) {
            $enrolment_types[] = $method->enrol;
            if ($method->enrol === 'self' && $method->status == ENROL_INSTANCE_ENABLED) {
                $requires_password = !empty($method->password);

                $self_enrolment = [
                    'id' => $course->id,
                    'instance' => $method->id,
                    'sesskey' => sesskey(),
                    'require_password' => $requires_password
                ];
            }
        };

        $templatecontext = [
            'fullcoursename' => $course->fullname,
            'summary' => $course->summary,
            'category' => $category->name,
            'imageurl' => $imageurl,
            'self_enrolment' => $self_enrolment,
            'workload' => $custom_fields['carga_horaria'],
            'has_certificate' => $custom_fields['tem_certificado'],
            'teacher_image' => $CFG->wwwroot . '/theme/suap/pix/default-course-image.webp',
        ];
        echo $OUTPUT->render_from_template('theme_suap/enroll_course', $templatecontext);
    }

    protected function get_course_category($categoryid) {
        global $DB;

        return $DB->get_record('course_categories', ['id' => $categoryid]);
    }

    protected function render_courses() {
        global $OUTPUT;

        $output = html_writer::start_div('course-area');

        for ($i = 0; $i < 8; $i++) {
            $output .= html_writer::start_div('frontpage-course-card skeleton');

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