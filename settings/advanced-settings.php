<?php

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . "/settings_page_tab.php");


class advanced_settings_tab extends settings_page_tab {

    public function __construct() {
        parent::__construct('theme_suap_advanced', 'advancedsettings');
    }

    protected function create_page_settings() {
        $name = 'rawscsspre';
        $updatecallback = true;
        $this->add_setting_configtextarea($name, $updatecallback);

        $name = 'rawscss';
        $this->add_setting_configtextarea($name, $updatecallback);

        $this->add_setting_configtext('pagination_secret', '', $updatecallback);
    }
}
