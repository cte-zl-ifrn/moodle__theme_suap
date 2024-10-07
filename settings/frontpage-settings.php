<?php

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . "/settings_page_tab.php");


class frontpage_settings_tab extends settings_page_tab {

    public function __construct() {
        parent::__construct('theme_suap_frontpage', 'frontpagesettings');
    }

    protected function create_page_settings() {
        $this->add_setting_configtext('frontpage_title', 'Teste');
        $this->add_setting_configtext('frontpage_first_button', 'Inicio');
        $this->add_setting_configtext('frontpage_second_button', 'Sobre');

        $this->add_setting_configtext('hero_title', 'O Moodle em números');
        $this->add_setting_configtext('hero_subtitle', 'Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.');

        $this->add_setting_configtext('hero_first_column_number', '99%');
        $this->add_setting_configtext('hero_first_column_description', 'Success Rate');
        $this->add_setting_configtext('hero_first_column_text', 'Business completion is achieved through a success rate that’s unmatched in the industry of business analysis.');

        $this->add_setting_configtext('hero_second_column_number', '49K');
        $this->add_setting_configtext('hero_second_column_description', 'Customer Reviews');
        $this->add_setting_configtext('hero_second_column_text', 'With reviews of unmatched quality, our business achieves a satisfaction level unmatched in the industry.');

        $this->add_setting_configtext('hero_third_column_number', '$3mil');
        $this->add_setting_configtext('hero_third_column_description', 'Monthly Income');
        $this->add_setting_configtext('hero_third_column_text', 'With an active and enraptured user base, our income is in excess of every monthly estimate, achieving yearly goals.');

        $this->add_setting_configtext('hero_fourth_column_number', '8mil');
        $this->add_setting_configtext('hero_fourth_column_description', 'Total Users');
        $this->add_setting_configtext('hero_fourth_column_text', 'Our user base is an exemplary showcase of our business functions and processes. Frameworks that support all users.');

        $this->add_setting_configtext('hero_button_text', 'Sobre nós');
    }
}