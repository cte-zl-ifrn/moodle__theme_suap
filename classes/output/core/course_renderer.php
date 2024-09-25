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
                    $courses = array_slice(get_courses(), 0, 9);
                    $courses = array_slice($courses, 1, 8);
                    $output .= $this->render_courses($courses);
                    // $firstCourse = true;
                    // foreach ($courses as $course) {
                    //     // if ($firstCourse) {
                    //     //     $firstCourse = false;
                    //     //     continue;
                    //     // }
                    //     $output .= $this->render_courses($course);
                    // }
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

    function get_course_category($categoryid) {
        global $DB;

        return $DB->get_record('course_categories', ['id' => $categoryid]);
    }

    protected function render_courses($courses) {
        global $OUTPUT, $CFG;

        $output = '';

        $firstRow = array_slice($courses, 0, 4);
        $secondRow = array_slice($courses, 4, 4);

        $output .= $this->display_row($firstRow);
        $output .= $this->display_row($secondRow);

        return $output;
    }

    protected function display_row($courses) {
        $output = html_writer::start_div('row course-row');

        foreach ($courses as $course) {
            $output .= $this->render_course($course);
        }

        $output .= html_writer::end_div();

        return $output;
    }
    
    protected function render_course($course) {
        global $OUTPUT, $CFG;

        $courseimageurl = \core_course\external\course_summary_exporter::get_course_image($course);

        $output = html_writer::start_div('course-card');
        // $output = html_writer::start_div('col-md-3 course-card');
        $output .= html_writer::start_div('course-image-container');

        if ($courseimageurl) {
            $output .= html_writer::tag('img', '', ['src' => $courseimageurl, 'alt' => $course->fullname, 'class' => 'course-image']);
        } else {
            $defaultimageurl = $CFG->wwwroot . '/theme/suap/pix/default.jpeg';
            $output .= html_writer::tag('img', '', ['src' => $defaultimageurl, 'alt' => 'Imagem padrão de curso', 'class' => 'course-image']);
        }
        $output .= html_writer::end_div();

        if ($category = $this->get_course_category($course->category)) {
            $output .= html_writer::tag('span', $category->name, ['class' => 'course-category']);
        }

        $output .= html_writer::tag('p', $course->fullname, ['class' => 'course-name']);
        $output .= html_writer::end_div();

        return $output;
    }
    
    // protected function render_courses($course) {
    //     global $OUTPUT, $CFG;

    //     // $coursecontext = context_course::instance($course->id);

    //     $courseimageurl = \core_course\external\course_summary_exporter::get_course_image($course);

    //     // $output = html_writer::start_div('course-card');

    //     // if ($courseimageurl) {
    //     //     $output .= html_writer::tag('img', '', ['src' => $courseimageurl, 'alt' => $course->fullname, 'class' => 'course-image']);
    //     // } else {
    //     //     $defaultimageurl = $CFG->wwwroot . '/theme/suap/pix/default.jpeg';
    //     //     $output .= html_writer::tag('img', '', ['src' => $defaultimageurl, 'alt' => 'Imagem padrão de curso', 'class' => 'course-image']);
    //     // }

    //     // if ($category = $this->get_course_category($course->category)) {
    //     //     $output .= html_writer::tag('p', $category->name, ['class' => 'course-category']);
    //     // }
        
    //     // $output .= html_writer::tag('h3', $course->fullname);
    //     // $output .= html_writer::end_div();

    //     // Iniciar o HTML do card do curso
    //     // $output = html_writer::start_div('row');

    //     $output = html_writer::start_div('course-card');

    //     // Div para a imagem do curso
    //     $output .= html_writer::start_div('course-image-container');
        
    //     if ($courseimageurl) {
    //         // Exibir a imagem do curso se existir
    //         $output .= html_writer::tag('img', '', ['src' => $courseimageurl, 'alt' => $course->fullname, 'class' => 'course-image']);
    //     } else {
    //         // Usar imagem padrão se não houver imagem do curso
    //         $defaultimageurl = $CFG->wwwroot . '/theme/suap/pix/default.jpeg';
    //         $output .= html_writer::tag('img', '', ['src' => $defaultimageurl, 'alt' => 'Imagem padrão de curso', 'class' => 'course-image']);
    //     }

    //     $output .= html_writer::end_div(); // Fechar div da imagem

    //     // Obter e exibir a categoria do curso
    //     if ($category = $this->get_course_category($course->category)) {
    //         $output .= html_writer::tag('span', $category->name, ['class' => 'course-category']);
    //     }

    //     // Exibir o nome do curso
    //     $output .= html_writer::tag('p', $course->fullname, ['class' => 'course-name']);
    //     $output .= html_writer::end_div();
    //     // $output .= html_writer::end_div();

    //     return $output;
    // }
}