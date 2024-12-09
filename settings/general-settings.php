<?php

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . "/settings_page_tab.php");


class general_settings_tab extends settings_page_tab {

    public function __construct() {
        parent::__construct('theme_suap_general', 'generalsettings');
    }

    protected function create_page_settings() {
        // Replica a configuração predefinida do tema Boost.
        $name = 'preset';
        $default = 'default.scss';                                                                               
        $context = context_system::instance();                                                                                          
        $fs = get_file_storage();                                                                                                       
        $files = $fs->get_area_files($context->id, 'theme_suap', 'preset', 0, 'itemid, filepath, filename', false);                    

        $choices = [];                                                                                                                  
        foreach ($files as $file) {                                                                                                     
            $choices[$file->get_filename()] = $file->get_filename();                                                                    
        }                                                                                                                               

        $choices['default.scss'] = 'default.scss';                                                                                      
        $choices['plain.scss'] = 'plain.scss';

        $this->add_setting_configselect($name, $default, $choices, true);

        $name = 'presetfiles';
        $default = 'preset';
        $options = array('maxfiles' => 20, 'accepted_types' => array('.scss'));

        $this->add_setting_configstoredfile($name, $default, $options);

        $name = 'brandcolor';
        $this->add_setting_configcolourpicker($name, true);

        $name = 'layouttype';
        $this->add_setting_configcheckbox($name, 0);
    }
}
