<?php

class theme_suap_admin_settingspage extends admin_settingpage
{

    public function __construct($admin_mode)
    {
        $plugin_name = 'theme_suap';
        parent::__construct($plugin_name, get_string('pluginname', $plugin_name), 'moodle/site:config', false, NULL);
        $this->setup($admin_mode);
    }

    function _($str, $args = null, $lazyload = false)
    {
        return get_string($str, $this->name);
    }

    function add_heading($name)
    {
        $this->add(new admin_setting_heading("{$this->name}/$name", $this->_($name), $this->_("{$name}_desc")));
    }

    function add_configtext($name, $default = '')
    {
        $this->add(new admin_setting_configtext("{$this->name}/$name", $this->_($name), $this->_("{$name}_desc"), $default));
    }

    function add_configtextarea($name, $default = '')
    {
        $this->add(new admin_setting_configtextarea("{$this->name}/$name", $this->_($name), $this->_("{$name}_desc"), $default));
    }

    function add_configselect($name, $default = '', $choices = [])
    {
        $this->add(new admin_setting_configselect("{$this->name}/$name", $this->_($name), $this->_("{$name}_desc"), $default, $choices));
    }

    function add_configstoredfile($name)
    {
        $this->add(new admin_setting_configstoredfile("{$this->name}/$name", $this->_($name), $this->_("{$name}_desc"), 'preset', 0,
            array('maxfiles' => 20, 'accepted_types' => array('.scss'))));
    }

    function get_presets_choices()
    {
        $choices = [];
        // Load presets from the file storage
        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'theme_suap', 'preset', 0, 'itemid, filepath, filename', false);
        
        foreach ($files as $file) {
            $choices[$file->get_filename()] = $file->get_filename();
        }
        
        // Add built-in presets
        $choices['default.scss'] = 'default.scss';
        $choices['plain.scss'] = 'plain.scss';
        
        return $choices;
    }

    function add_frontpage_info()
    {
        global $SITE;

        $this->add_configtext('frontpage_title', 'Moodle');
        $this->add_configtext('frontpage_first_button', 'Inicio');
        $this->add_configtext('frontpage_second_button', 'Sobre');

        $this->add_configtext('hero_title', 'O Moodle em números');
        $this->add_configtext('hero_subtitle', 'Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.');

        $this->add_configtext('hero_first_column_number', '99%');
        $this->add_configtext('hero_first_column_description', 'Success Rate');
        $this->add_configtext('hero_first_column_text', 'Business completion is achieved through a success rate that’s unmatched in the industry of business analysis.');
        
        $this->add_configtext('hero_second_column_number', '49K');
        $this->add_configtext('hero_second_column_description', 'Customer Reviews');
        $this->add_configtext('hero_second_column_text', 'With reviews of unmatched quality, our business achieves a satisfaction level unmatched in the industry.');

        $this->add_configtext('hero_third_column_number', '$3mil');
        $this->add_configtext('hero_third_column_description', 'Monthly Income');
        $this->add_configtext('hero_third_column_text', 'With an active and enraptured user based our income is in excess of every monthly estimate, achieving yearly goals.');

        $this->add_configtext('hero_fourth_column_number', '8mil');
        $this->add_configtext('hero_fourth_column_description', 'Total Users');
        $this->add_configtext('hero_fourth_column_text', 'Our user base is an exemplary showcase of our business functions and processes. Frameworks that support all users.');

        $this->add_configtext('hero_button_text', 'Sobre nós');
    }

    function setup($admin_mode)
    {
        global $CFG;
        
        if ($admin_mode) {
            $settings = new theme_boost_admin_settingspage_tabs('themesettingsuap', get_string('configtitle', 'theme_suap'));
    
            // General page
            $page = new admin_settingpage('theme_suap_general', get_string('generalsettings', 'theme_suap'));
    
            // Add preset setting
            $this->add_configselect('preset', 'default.scss', $this->get_presets_choices());
    
            // Add preset files setting
            $this->add_configstoredfile('presetfiles');
    
            // Brand color setting
            $this->add_configtext('brandcolor', '');
    
            $this->add_frontpage_info();
    
            // Advanced settings page
            $page_advanced = new admin_settingpage('theme_suap_advanced', get_string('advancedsettings', 'theme_suap'));
    
            // Raw SCSS settings
            $this->add_configtextarea('scsspre', '');
            $this->add_configtextarea('scss', '');
    
            // Must add the pages after defining all the settings!
            $settings->add($page);
            $settings->add($page_advanced);
        }
    }
}