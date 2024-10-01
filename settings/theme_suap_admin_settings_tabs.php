<?php

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . "/frontpage-settings.php");
require_once(__DIR__ . "/advanced-settings.php");
require_once(__DIR__ . "/general-settings.php");


class theme_suap_admin_settings_tabs extends theme_boost_admin_settingspage_tabs
{
    public function __construct()
    {
        parent::__construct('themesettingsuap', get_string('configtitle', 'theme_suap'));

        $this->add(new general_settings_tab());
        $this->add(new advanced_settings_tab());
        $this->add(new frontpage_settings_tab());
    }
}