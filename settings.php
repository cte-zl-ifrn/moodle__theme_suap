<?php

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/adminlib.php');

if ($hassiteconfig) {
    $ADMIN->add('server', new theme_suap_admin_settingspage($ADMIN->fulltree));
}


                                                                                                                                    
// This is used for performance, we don't need to know about these settings on every page in Moodle, only when                      
// we are looking at the admin settings pages.                                                                                      
// if ($ADMIN->fulltree) {                                                                                                             
                                                                                                                                    
//     // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.                         
//     $settings = new theme_boost_admin_settingspage_tabs('themesettingsuap', get_string('configtitle', 'theme_suap'));             
                                                                                                           
//     // Each page is a tab - the first is the "General" tab.                                                                         
//     $page = new admin_settingpage('theme_suap_general', get_string('generalsettings', 'theme_suap'));                             
                                                                                                                                    
//     // Replicate the preset setting from boost.                                                                                     
//     $name = 'theme_suap/preset';                                                                                                   
//     $title = get_string('preset', 'theme_suap');                                                                                   
//     $description = get_string('preset_desc', 'theme_suap');                                                                        
//     $default = 'default.scss';                                                                                                      
                                                                                                                                    
//     // We list files in our own file area to add to the drop down. We will provide our own function to                              
//     // load all the presets from the correct paths.                                                                                 
//     $context = context_system::instance();                                                                                          
//     $fs = get_file_storage();                                                                                                       
//     $files = $fs->get_area_files($context->id, 'theme_suap', 'preset', 0, 'itemid, filepath, filename', false);                    
                                                                                                                                    
//     $choices = [];                                                                                                                  
//     foreach ($files as $file) {                                                                                                     
//         $choices[$file->get_filename()] = $file->get_filename();                                                                    
//     }                                                                                                                               
//     // These are the built in presets from Boost.                                                                                   
//     $choices['default.scss'] = 'default.scss';                                                                                      
//     $choices['plain.scss'] = 'plain.scss';                                                                                          
                                                                                                                                    
//     $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);                                     
//     $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
//     $page->add($setting);                                                                                                           
                                                                                                                                    
//     // Preset files setting.                                                                                                        
//     $name = 'theme_suap/presetfiles';                                                                                              
//     $title = get_string('presetfiles','theme_suap');                                                                               
//     $description = get_string('presetfiles_desc', 'theme_suap');                                                                   
                                                                                                                                    
//     $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,                                         
//         array('maxfiles' => 20, 'accepted_types' => array('.scss')));                                                               
//     $page->add($setting);     

//     // Variable $brand-color.                                                                                                       
//     // We use an empty default value because the default colour should come from the preset.                                        
//     $name = 'theme_suap/brandcolor';                                                                                               
//     $title = get_string('brandcolor', 'theme_suap');                                                                               
//     $description = get_string('brandcolor_desc', 'theme_suap');                                                                    
//     $setting = new admin_setting_configcolourpicker($name, $title, $description, '');                                               
//     $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
//     $page->add($setting);                                                                                                           
                                                                                                                                    
//     // Must add the page after definiting all the settings!                                                                         
//     $settings->add($page);                                                                                                          
                                                                                                                                    
//     // Advanced settings.                                                                                                           
//     $page = new admin_settingpage('theme_suap_advanced', get_string('advancedsettings', 'theme_suap'));                           
                                                                                                                                    
//     // Raw SCSS to include before the content.                                                                                      
//     $setting = new admin_setting_configtextarea('theme_suap/scsspre',                                                              
//         get_string('rawscsspre', 'theme_suap'), get_string('rawscsspre_desc', 'theme_suap'), '', PARAM_RAW);                      
//     $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
//     $page->add($setting);                                                                                                           
                                                                                                                                    
//     // Raw SCSS to include after the content.                                                                                       
//     $setting = new admin_setting_configtextarea('theme_suap/scss', get_string('rawscss', 'theme_suap'),                           
//         get_string('rawscss_desc', 'theme_suap'), '', PARAM_RAW);                                                                  
//     $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
//     $page->add($setting);                                                                                                           
                                                                                                                                    

//     $heading->add(new admin_setting_heading('theme_suap/pluginname', '', get_string('theme_suap_description', 'theme_suap')));
//     $heading->add(new admin_setting_configtext('theme_suap/frontpageteste', get_string('frontpageteste', 'theme_suap'), get_string('frontpageteste_desc', 'theme_suap'), 'AAAAA'));

//     $settings->add($heading);
    
//     $settings->add($page);                                                                                                          
// }