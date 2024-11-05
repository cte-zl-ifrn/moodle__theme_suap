<?php
/**
 *
 * @package    theme_suap
 * @copyright 2024 IFRN
 */

namespace theme_suap\output\core_user;

use core_user\output\myprofile\renderer;

class myprofile_renderer extends renderer {
    //Apaga a profile tree 
    public function render(\renderable $widget) {
        return '';
    }
}