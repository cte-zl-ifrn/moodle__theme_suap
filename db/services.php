<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Controls the notification drawer
 * Has the same function of message_popup/notification_popover_controller
 *
 * @package    theme_suap
 * @copyright  2024 IFRN DEAD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// $functions = [
//     'theme_suap_get_all_unread_conversations' => array(
//         'classpath'   => 'theme/suap/classes/message/externallib.php', // Caminho para a classe.
//         'classname'   => 'theme_suap\external\theme_suap_external', // Classe onde a função está.
//         'methodname'  => 'get_all_unread_conversations', // Nome da função.
//         'description' => 'Retorna todas as conversas não lidas de um usuário',
//         'type'        => 'read', // Define que é uma operação de leitura.
//         'ajax'        => true, // Disponível para AJAX.
//         'capabilities' => '', // Permissões especiais (opcional).
//         'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE) // Disponível para o serviço mobile.
//     ),
// ];

// $services = array(
//    'Moodle mobile web service'  => array(
//         'functions' => array(), // Unused as we add the service in each function definition, third party services would use this.
//         'enabled' => 0,
//         'restrictedusers' => 0,
//         'shortname' => MOODLE_OFFICIAL_MOBILE_SERVICE,
//         'downloadfiles' => 1,
//         'uploadfiles' => 1
//     ),
// );