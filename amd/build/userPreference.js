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

define(["jquery", "core/ajax", "core_user/repository"], function (
    $,
    Ajax,
    Repository
) {
    var init = function () {
        // Seleciona os elementos do DOM
        const contentpart1 = document.querySelector("#content-part1");
        const countcontent = document.querySelector("#counter-content");
        const preferenceName = "visual_preference";

        // Evento 'change' para o checkbox
        document
            .getElementById("preferVisual")
            .addEventListener("change", function () {
                if (this.checked) {
                    contentpart1.classList.add("content-original");
                    countcontent.classList.add("content-reverse");

                    // Salvar a preferência
                    Repository.setUserPreference(preferenceName, true);
                } else {
                    contentpart1.classList.remove("content-original");
                    countcontent.classList.remove("content-reverse");
                    console.log("normal");

                    // Salvar a preferência
                    Repository.setUserPreference(preferenceName, false);
                }
            });

        // console.log("teste 1");

        let getUserPreference;
        contentpart1.classList.add("content-original");
        countcontent.classList.add("content-reverse");

        Repository.getUserPreference(preferenceName).then(function (result) {
            let getUserPreference = result;
            console.log(getUserPreference);
            if (getUserPreference) {
                console.log("mudou");
                document.getElementById("preferVisual").checked = true;
                // contentpart1.classList.add("content-original");
                // countcontent.classList.add("content-reverse");
            } else {
                document.getElementById("preferVisual").checked = false;
                // contentpart1.classList.remove("content-original");
                // countcontent.classList.remove("content-reverse");
            }
        });

        // Função para restaurar o estado ao carregar a página
        // document.addEventListener("DOMContentLoaded", async () => {
        //     console.log("teste 2 ");
        //     try {
        //         const getUserPreference = await Repository.getUserPreference(
        //             preferenceName
        //         );
        //         // Agora você tem o valor de getUserPreference e pode atualizar o DOM com segurança
        //         if (getUserPreference === true || getUserPreference === 1) {
        //             contentpart1.classList.add("content-original");
        //             countcontent.classList.add("content-reverse");
        //         } else {
        //             contentpart1.classList.remove("content-original");
        //             countcontent.classList.remove("content-reverse");
        //         }
        //     } catch (error) {
        //         console.error("Erro ao obter a preferência:", error);
        //     }
        // });
    };

    return {
        init: init,
    };
});
