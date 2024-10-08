<?php

defined('MOODLE_INTERNAL') || die();


require_once(__DIR__ . "/settings/theme_suap_admin_settings_tabs.php");


if ($ADMIN->fulltree) {
    $settings = new theme_suap_admin_settings_tabs();                                                                                                     
}
