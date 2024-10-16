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
 * Controls the drawers general behavior
 * Has the same function of message_popup/notification_popover_controller
 *
 * @package
 * @copyright  2024 IFRN DEAD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core_message/message_repository'],
function($, Repository) {

    const body = document.body;
    let drawers = document.querySelectorAll('.drawer-content');
    let drawersToggler = document.querySelectorAll('.drawer-toggler');
    let closeButtons = document.querySelectorAll('.drawer-close');

    const counterToggler = document.querySelector('.counter-toggler');

    const searchForm = document.querySelector('.searchform-js');

    var closeAllDrawers  = function(drawers) {
        body.classList.remove('drawer-open');
        drawers.forEach((drawer) => {
            drawer.classList.remove('active-drawer');
        })
        drawersToggler.forEach((toggler) => {
            toggler.classList.remove('active-toggler');
        })
    }


    var init = function() {

        if(searchForm) {
            const searchSubmit = searchForm.querySelector('.search-js');
            const searchInput = searchForm.querySelector('.input-js');
            searchSubmit.addEventListener('click', () => {
                body.classList.remove('counter-close');
                if (window.innerWidth <= 767.98 && 
                    !body.classList.contains('counter-close')) {
                    closeAllDrawers(drawers);
                }
                searchInput.focus();
            })
        }

        counterToggler.addEventListener('click', () => {
            body.classList.toggle('counter-close');
            if (window.innerWidth <= 767.98 && 
                !body.classList.contains('counter-close')) {
                closeAllDrawers(drawers);
            }
            if(searchForm) {
                const searchInput = searchForm.querySelector('.input-js');
                searchInput.value = "";
            };
        });


        drawersToggler.forEach((toggler) => {
            toggler.addEventListener('click', () => {
                let drawerId = toggler.getAttribute('data-drawer');
                let drawer = document.getElementById(drawerId);

                if (drawer.classList.contains('active-drawer')) { //close drawer
                    drawer.classList.remove('active-drawer');
                    toggler.classList.remove('active-toggler');
                    body.classList.remove('drawer-open');
                } else { //open drawer
                    closeAllDrawers(drawers, drawersToggler);
                    drawer.classList.add('active-drawer');
                    toggler.classList.add('active-toggler');
                    body.classList.add('drawer-open');
                    if (window.innerWidth <= 767.98) {
                        body.classList.add('counter-close');
                    }
                }
            })
        })

        closeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                closeAllDrawers(drawers, drawersToggler);
            })
        })


    };

    return {
        init: init,
    };
});