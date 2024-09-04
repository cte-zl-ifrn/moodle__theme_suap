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
$string['configtitle'] = 'Configurações do SUAP';                                                                                          
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
$string['rawscss'] = 'Raw SCSS';                                                                                                    
// Raw SCSS setting help text.                                                                                                      
$string['rawscss_desc'] = 'Use este campo para fornecer o código SCSS ou CSS que será injetado no final do style sheet';       
// Raw initial SCSS setting.                                                                                                        
$string['rawscsspre'] = 'Raw initial SCSS';                                                                                         
// Raw initial SCSS setting help text.                                                                                              
$string['rawscsspre_desc'] = 'Neste campo você pode fornecer o código SCSS de inicialização, ele será injetado antes de tudo. Na maioria das vezes você usará esta configuração para definir variáveis';
$string['drawer_course_index'] = "Índice da disciplina";
$string['drawer_blocks'] = "Gaveta de Blocos";