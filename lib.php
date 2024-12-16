<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.

function theme_suap_get_main_scss_content($theme) {                                                                                
    global $CFG;                                                                                                                    
                                                                                                                                    
    $scss = '';                                                                                                                     
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;                                                 
    $fs = get_file_storage();                                                                                                       
                                                                                                                                    
    $context = context_system::instance();                                                                                          
    if ($filename == 'default.scss') {                                                                                              
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    } else if ($filename == 'plain.scss') {                                                                                         
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');                                          
                                                                                                                                    
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_suap', 'preset', 0, '/', $filename))) {              
        // This preset file was fetched from the file area for theme_suap and not theme_boost (see the line above).                
        $scss .= $presetfile->get_content();                                                                                        
    } else {                                                                                                                        
        // Safety fallback - maybe new installs etc.                                                                                
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    }
    
    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.                                        
    $pre = file_get_contents($CFG->dirroot . '/theme/suap/scss/pre.scss');                                                         
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.                                    
    $post = file_get_contents($CFG->dirroot . '/theme/suap/scss/post.scss');
                                                                                                                                    
    // Combine them together.                                                                                                       
    return $pre . "\n" . $scss . "\n" . $post;                                                                                                                     
}

// Essa função é responsável por transformar uma configtextarea(label, link, icon, target e capabilities) em um objeto.
function parse_configtextarea_string($config_string) {
    $default_value = 'N/A';
    $lines = explode("\n", trim($config_string));
    $result = [];

    foreach ($lines as $line) {
        $parts = preg_split('/\|/', $line);

        foreach ($parts as &$part) {
            $part = trim($part);
            if (empty($part)) {
                $part = $default_value;
            }
        }

        if (strpos($parts[0], ',') !== false) {
            $array_label = explode(',', $parts[0]);
            $parts[0] = get_string($array_label[0], $array_label[1]);
        }

        $result[] = [
            'label' => $parts[0],
            'link' => $parts[1],
            'icon' => $parts[2],
            'target' => $parts[3],
            'capabilities' => $parts[4]
        ];
    }

    return $result;
}

/**
 * Get the current user preferences that are available
 *
 * @return array[]
 */
function theme_suap_user_preferences(): array {
    return [
        'visual_preference' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => false,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
        'theme_suap_counter_close' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => false,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ]
      
    ];
}

/**
 * Adiciona itens específicos de administrador ao menu de usuário.
 *
 * @param array $items O array de itens para adicionar os links.
 * @return array O array atualizado com os itens de administrador.
 */
function theme_suap_add_admin_items_user_menu(): ?array {
    
    if(is_siteadmin($USER->id)) {
        $items[] = [
            'link' => [
                'title' => get_string('administrationsite', 'core'),
                'url' => $CFG->wwwroot . '/admin/search.php',
            ]
        ];

        $items[] = [
            'link' => [
                'title' => get_string('mycourses', 'core'),
                'url' => $CFG->wwwroot . '/my/courses.php',
            ]
        ]; 
    }

    return $items;
}
