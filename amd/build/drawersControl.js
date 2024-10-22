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

define(["core_user/repository"], function (RepositoryUser) {
    const body = document.body;
    const breakpointSM = 767.98;
    let backdrop = document.querySelector('[data-region="suap-backdrop"]');

    let drawers = document.querySelectorAll('.drawer-content');
    let drawersToggler = document.querySelectorAll('.drawer-toggler');
    let closeButtons = document.querySelectorAll('.drawer-close');

    const counterToggler = document.querySelector(".counter-toggler");

    const searchForm = document.querySelector(".searchform-js");

    var closeAllDrawers = function (drawers) {
        body.classList.remove("drawer-open");
        drawers.forEach((drawer) => {
            drawer.classList.remove("active-drawer");
        });
        drawersToggler.forEach((toggler) => {
            toggler.classList.remove("active-toggler");
        });
    };

    const preferenceCounter = 'theme_suap_counter_close';

    var init = function() {

        if (window.innerWidth <= breakpointSM) {
            body.classList.add('counter-close');
        }
        backdrop.classList.remove('hidden');

        if(searchForm) {
            const searchSubmit = searchForm.querySelector('.search-js');
            const searchInput = searchForm.querySelector('.input-js');
            searchSubmit.addEventListener('click', () => {
                body.classList.remove('counter-close');
                if (window.innerWidth <= breakpointSM && 
                    !body.classList.contains('counter-close')) {
                    closeAllDrawers(drawers);
                }
                searchInput.focus();
            });
        }

        // Caso o usuário diminua largura e esteja com counter e drawer abertas
        window.addEventListener('resize', function() {
            if(window.innerWidth <= breakpointSM && 
            !body.classList.contains('counter-close') && 
            body.classList.contains('drawer-open')) {
                body.classList.add('counter-close')
            }
        });

        // ao clicar no backdrop fecha counter ou drawers
        backdrop.addEventListener('click', function(e) {
            if(e.target === e.currentTarget) {
                body.classList.add('counter-close');
                if (body.classList.contains('drawer-open')) {
                    closeAllDrawers(drawers);
                }
            }
        })

        counterToggler.addEventListener('click', () => {
            body.classList.toggle('counter-close');
            if(body.classList.contains('counter-close')) {
                RepositoryUser.setUserPreference(preferenceCounter, true);
            } else {
                RepositoryUser.setUserPreference(preferenceCounter, false);
            }
            if (window.innerWidth <= breakpointSM && 
                !body.classList.contains('counter-close')) {
                closeAllDrawers(drawers);
            }
            if(searchForm) {
                const searchInput = searchForm.querySelector('.input-js');
                searchInput.value = "";
            }
        });

        drawersToggler.forEach((toggler) => {
            toggler.addEventListener("click", () => {
                let drawerId = toggler.getAttribute("data-drawer");
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
                    if (window.innerWidth <= breakpointSM) {
                        body.classList.add('counter-close');
                    }
                }
            });
        });

        closeButtons.forEach((button) => {
            button.addEventListener("click", () => {
                closeAllDrawers(drawers, drawersToggler);
            });
        });
    };

    return {
        init: init,
    };
});
