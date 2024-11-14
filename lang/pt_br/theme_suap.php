<?php
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();                                                                                                
                                                                                                                                    
// A description shown in the admin theme selector.                                                                                 
$string['choosereadme'] = 'O tema SUAP é um tema filho do tema Boost';                
// The name of our plugin.                                                                                                          
$string['pluginname'] = 'SUAP';                                                                                                    
// We need to include a lang string for each block region.                                                                          
$string['region-side-pre'] = 'Direita';
// The name of the second tab in the theme settings.                                                                                
$string['advancedsettings'] = 'Configurações avançadas';                                                                                  
// The brand colour setting.                                                                                                        
$string['brandcolor'] = 'Cor da marca';                                                                                             
// The brand colour setting description.                                                                                            
$string['brandcolor_desc'] = 'Cor de destaque';     
// A description shown in the admin theme selector.                                                                                                                                                                                    
$string['configtitle'] = 'Configurações do tema SUAP';                                                                                          
// Name of the first settings tab.                                                                                                  
$string['generalsettings'] = 'Configurações gerais';                                                                                                                                                                                    
// Preset files setting.                                                                                                            
$string['presetfiles'] = 'Arquivos adicionais de predefinição de tema';                                                                           
// Preset files help text.                                                                                                          
$string['presetfiles_desc'] = 'Arquivos predefinidos podem ser usados ​​para alterar drasticamente a aparência do tema. Consulte <a href=https://docs.moodle.org/dev/Boost_Presets>Predefinições de Boost</a> para obter informações sobre como criar e compartilhar seus próprios arquivos predefinidos e consulte <a href=http://moodle.net/boost>Repositório de predefinições</a> para predefinições que outras pessoas compartilharam.';
// Preset setting.                                                                                                                  
$string['preset'] = 'Tema predefinido';                                                                                                 
// Preset help text.                                                                                                                
$string['preset_desc'] = 'Escolha uma predefinição para alterar amplamente a aparência do tema';                                                  
// Raw SCSS setting.                                                                                                                
$string['rawscss'] = 'SCSS bruto';                                                                                                    
// Raw SCSS setting help text.                                                                                                      
$string['rawscss_desc'] = 'Use este campo para fornecer o código SCSS ou CSS que será injetado no final do style sheet';       
// Raw initial SCSS setting.                                                                                                        
$string['rawscsspre'] = 'SCSS inicial bruto';                                                                                         
// Raw initial SCSS setting help text.                                                                                              
$string['rawscsspre_desc'] = 'Neste campo você pode fornecer o código SCSS de inicialização, ele será injetado antes de tudo. Na maioria das vezes você usará esta configuração para definir variáveis';
$string['drawer_course_index'] = "Índice da disciplina";
$string['drawer_blocks'] = "Gaveta de Blocos";
$string['drawer_user'] = "Menu do usuário";
$string['allconversations'] = "todas";
$string['unreadmessages'] = "Não lidas";

// frontpage-settings.php
$string['frontpagesettings'] = 'Configurações da página inicial';
$string['frontpage_title'] = 'Título da página inicial';
$string['frontpage_title_desc'] = '';
$string['frontpage_buttons_configtextarea'] = 'Configuração dos botões da página inicial';
$string['frontpage_buttons_configtextarea_desc'] = 'Apague o trecho (/n) e pressione "Enter" para aplicar a quebra de linha';
$string['frontpage_button_home'] = 'Início';
$string['frontpage_button_about'] = 'Sobre';
$string['hero_title'] = 'Título da seção de destaque';
$string['hero_title_desc'] = '';
$string['hero_subtitle'] = 'Subtítulo da seção de destaque';
$string['hero_subtitle_desc'] = '';
$string['hero_first_column_number'] = 'Número da primeira coluna da seção de destaque';
$string['hero_first_column_number_desc'] = '';
$string['hero_first_column_description'] = 'Descrição da primeira coluna da seção de destaque';
$string['hero_first_column_text'] = 'Texto da primeira coluna da seção de destaque';
$string['hero_first_column_text_desc'] = '';
$string['hero_second_column_number'] = 'Número da segunda coluna da seção de destaque';
$string['hero_second_column_number_desc'] = '';
$string['hero_second_column_description'] = 'Descrição da segunda coluna da seção de destaque';
$string['hero_second_column_description_desc'] = '';
$string['hero_second_column_text'] = 'Texto da segunda coluna da seção de destaque';
$string['hero_second_column_text_desc'] = '';
$string['hero_third_column_number'] = 'Número da terceira coluna da seção de destaque';
$string['hero_third_column_number_desc'] = '';
$string['hero_third_column_description'] = 'Descrição da terceira coluna da seção de destaque';
$string['hero_third_column_description_desc'] = '';
$string['hero_third_column_text'] = 'Texto da terceira coluna da seção de destaque';
$string['hero_third_column_text_desc'] = '';
$string['hero_fourth_column_number'] = 'Número da quarta coluna da seção de destaque';
$string['hero_fourth_column_number_desc'] = '';
$string['hero_fourth_column_description'] = 'Descrição da quarta coluna da seção de destaque';
$string['hero_fourth_column_description_desc'] = '';
$string['hero_fourth_column_text'] = 'Texto da quarta coluna da seção de destaque';
$string['hero_fourth_column_text_desc'] = '';
$string['hero_button_text'] = 'Texto do botão da seção de destaque';
$string['hero_button_text_desc'] = '';

$string['hero_configtextarea_test'] = 'Texto do botão da seção de destaque';
$string['hero_configtextarea_test_desc'] = '';

$string['pagination_secret'] = 'Segredo de paginação';
$string['pagination_secret_desc'] = 'É necessário criar um token na seção de web services do Moodle para dispositivos móveis';

$string['frontpage_main_courses_title'] = 'Título da seção de cursos da página inicial';
$string['frontpage_main_courses_title_desc'] = '';

$string['frontpage_buttons_configtextarea_when_user_logged'] = 'Configuração dos botões da página inicial quando o usuário está logado';
$string['frontpage_buttons_configtextarea_when_user_logged_desc'] = 'Apague o trecho (/n) e pressione "Enter" para aplicar a quebra de linha';
$string['frontpage_button_courses'] = 'Cursos';
$string['frontpage_button_courses_desc'] = '';
$string['frontpage_button_learningpaths'] = 'Trilhas';
$string['frontpage_button_learningpaths_desc'] = '';
