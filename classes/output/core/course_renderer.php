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
                    $courses = [];
                    
                    $firstCourse = true;
                    foreach (get_courses() as $course) {
                        if ($firstCourse) {
                            $firstCourse = false;
                            continue;
                        }
                        $courses[] = $course;
                    }
                    
                    $output .= $this->render_courses($courses, 4);
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

    protected function render_courses($courses, $limit) {
        global $OUTPUT, $CFG;

        if (count($courses) > $limit) {
            $courses = array_slice($courses, 0, $limit);
        }

        $output = html_writer::start_div('course-area');

        foreach ($courses as $course) {
            $courseimageurl = \core_course\external\course_summary_exporter::get_course_image($course);

            $output .= html_writer::start_div('course-card');
            $output .= html_writer::start_div('course-image-container');
            
            // Adiciona a imagem do curso
            if ($courseimageurl) {
                $output .= html_writer::tag('img', '', ['src' => htmlspecialchars($courseimageurl), 'alt' => htmlspecialchars($course->fullname), 'class' => 'course-image']);
            } else {
                // Imagem padrão se não houver imagem do curso
                $defaultimageurl = "{$CFG->wwwroot}/theme/suap/pix/default.jpeg";
                $output .= html_writer::tag('img', '', ['src' => htmlspecialchars($defaultimageurl), 'alt' => 'Imagem padrão de curso', 'class' => 'course-image']);
            }

            $output .= html_writer::end_div();
        
            // Adiciona a categoria do curso se existir
            if ($category = $this->get_course_category($course->category)) {
                $output .= html_writer::tag('span', htmlspecialchars($category->name), ['class' => 'course-category']);
            }
        
            // Adiciona o nome do curso
            $output .= html_writer::tag('p', htmlspecialchars($course->fullname), ['class' => 'course-name']);
            $output .= html_writer::end_div();
        }

        $output .= html_writer::end_div();
        // return $output;

        // $output = '';
        return $output;
    }

    // public function search_courses($searchcriteria) {
    //     // global $CFG;
    //     // $content = 'aqui vai o conteúdo da busca';
    //     // return $content;

    //     global $DB;

    //     $courses = $DB->get_records('course', ['visible' => 1]);
    //     return $courses;
    // }
}