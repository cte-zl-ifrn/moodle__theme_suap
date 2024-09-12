<?php

namespace theme_suap\output\core;

use core_course\course;
use core_course_get_courses;
use html_writer;
use get_file_storage;
use context_course;
use moodle_url;

class course_renderer extends \core_course_renderer {
    // public function frontpage() {
    //     return "aaaaaaaaaaaaaaaa";
    // }

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
                    // $availablecourseshtml = $this->frontpage_available_courses();
                    // $output .= $this->frontpage_part('skipavailablecourses', 'frontpage-available-course-list',
                    //     get_string('availablecourses'), $availablecourseshtml);
                    // break;

                    $courses = get_courses();
                    $firstCourse = true;
                    foreach ($courses as $course) {
                        if ($firstCourse) {
                            $firstCourse = false;
                            continue;
                        }
                        $output .= $this->render_course($course);
                    }
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

    protected function render_course($course) {
        global $OUTPUT;

        // Obter o contexto do curso
        $coursecontext = context_course::instance($course->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files($coursecontext->id, 'course', 'overviewfiles', 0, 'sortorder DESC, id ASC', false);
        
        // Inicializar a variável da imagem
        $courseimage = '';

        // Verificar se há arquivos e pegar a primeira imagem
        // if (count($files) >= 1) {
        //     $mainfile = array_shift($files);
        //     $courseimage = $OUTPUT->image_url($mainfile->get_filename(), 'course'); // Gera a URL da imagem
        // }
        $mainfile = array_shift($files);

        if ($mainfile) {
            // Gerar a URL da imagem
            $courseimage = moodle_url::make_pluginfile_url(
                $mainfile->get_contextid(),
                'course',
                'overviewfiles',
                $mainfile->get_itemid(),
                '/',
                $mainfile->get_filename()
            );
        }

        // Renderizar o HTML do curso
        $output = html_writer::start_div('course-card');

        if ($courseimage) {
            $output .= html_writer::tag('img', '', ['src' => $courseimage, 'alt' => $course->fullname, 'class' => 'course-image']);
        }
        
        // Adicionar a imagem do curso se existir
        if ($courseimage) {
            $output .= html_writer::tag('img', '', ['src' => $courseimage, 'alt' => $course->fullname, 'class' => 'course-image']);
        }
        
        $output .= html_writer::tag('h3', $course->fullname);
        $output .= html_writer::tag('p', $course->id);

        // $output .= html_writer::tag('p', count($files));
        // $output .= html_writer::tag('p', $mainfile->get_filename());

        $output .= html_writer::end_div();
        
        return $output;
    }
}