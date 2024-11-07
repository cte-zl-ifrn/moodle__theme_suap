define([], 
    function () {
        let url = '';
        let currentPage = 0;
        const limit = 8;
        let totalCourses = 0;

        async function loadCourses(page, limit, search = '') {
            try {
                const response = await fetch(`${url}?page=${page}&limit=${limit}&search=${search}`)
                    .catch(error => console.error('Error fetching courses:', error));
                const {total, courses, baseurl} = await response.json();
                totalCourses = total;

                const courseArea = document.querySelector('.course-area');
                courseArea.innerHTML = '';

                courses.forEach(course => {
                    const certificateArea = course.has_certificate !== "Sim" ? '' : `
                        <div class="course-certificate">
                            <p class="course-certificate-text">Certificado</p>
                            <span class="course-certificate-value"><img src="${baseurl}/theme/suap/pix/checkmark-circle-outline.svg" alt=""></span>
                        </div>
                    `;
                    courseArea.innerHTML += `
                        <div class="course-card" id="${course.id}">
                            <a class="course-image-container" href="${baseurl}/course/view.php?id=${course.id}">
                                <img src="${course.image_url}" alt="${course.fullname}" class="course-image">
                            </a>
                            <a class="course-category" href="${course.category_url}">${course.category_name}</a>
                            <a class="course-name" href="${baseurl}/course/view.php?id=${course.id}">${course.fullname}</a>
                            <div class="course-detail">
                                <div class="course-workload">
                                    <p class="course-workload-text">Carga Horária</p>
                                    <span class="course-workload-value">${course.workload ? course.workload + " horas" : "Não definida"}</span>
                                </div>
                                ${certificateArea}
                                <div class="course-lang">
                                    <p class="course-lang-text">Idioma</p>
                                    <span class="course-lang-value">${selectLangFlag(course.lang)}</span> 
                                </div>
                            </div>
                        </div>
                    `;
                });

                updatePaginationButtons(total);
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        }

        function updatePaginationButtons(total) {
            const paginationNumbers = document.querySelector('#pagination-numbers');
            const prev = document.querySelector('#prev-page');
            const next = document.querySelector('#next-page');

            paginationNumbers.innerHTML = '';
            prev.disabled = currentPage === 0;
            next.disabled = (currentPage + 1) * limit >= total;

            const totalPages = Math.ceil(total / limit);
            const pageRange = 2;

            let startPage = Math.max(0, currentPage - pageRange);
            let endPage = Math.min(totalPages - 1, currentPage + pageRange);

            if (startPage > 2) {
                createPageButton(0);
                createPageButton(1);
                paginationNumbers.innerHTML += '... ';
            } else {
                for (let i = 0; i < startPage; i++) {
                    createPageButton(i);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                createPageButton(i);
            }

            if (endPage < totalPages - 3) {
                paginationNumbers.innerHTML += ' ...';
                createPageButton(totalPages - 2);
                createPageButton(totalPages - 1);
            } else {
                for (let i = endPage + 1; i < totalPages; i++) {
                    createPageButton(i);
                }
            }

            next.setAttribute('page', currentPage + 1);
            prev.setAttribute('page', currentPage - 1);
        }

        function createPageButton(page) {
            const button = document.createElement('button');
            button.classList.add('pagination-number');
            button.textContent = page + 1;
            
            if (page === currentPage) {
                button.classList.add('active');
            }

            button.addEventListener('click', () => {
                currentPage = page;
                loadCourses(currentPage, limit);
                
                updateActivePageButton();
            });
            
            document.querySelector('#pagination-numbers').appendChild(button);
        }

        function selectLangFlag(lang) {
            if (lang === 'pt_br') {
                return '🇧🇷';
            }

            if (lang === 'en') {
                return '🇺🇸';
            }

            if (lang === 'es') {
                return '🇪🇸';
            }

            return '🌐';
        }

        function updateActivePageButton() {
            document.querySelectorAll('.pagination-number').forEach(button => {
                button.classList.remove('active');
            });

            const activeButton = document.querySelector(`.pagination-number:nth-child(${currentPage + 1})`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
        }

        document.querySelector('#prev-page').addEventListener('click', () => {
            if (currentPage > 0) {
                currentPage--;
                loadCourses(currentPage, limit);
            }
        });

        document.querySelector('#next-page').addEventListener('click', () => {
            if ((currentPage + 1) * limit < totalCourses) {
                currentPage++;
                loadCourses(currentPage, limit);
            } else {
                console.log('No more courses to load');
            }
        });

        document.querySelector('#search').addEventListener('input', () => {
            const search = document.querySelector('#search').value;
            loadCourses(currentPage, limit, search);
        });

        document.querySelector('#filter-courses').addEventListener('click', () => {
            document.querySelector('#filter-area').style.display = 'block';
            document.querySelector('#modal-overlay').style.display = 'block';
        });

        document.querySelector('#close-filter').addEventListener('click', () => {
            document.querySelector('#filter-area').style.display = 'none';
            document.querySelector('#modal-overlay').style.display = 'none';
        });

        document.querySelector('#modal-overlay').addEventListener('click', () => {
            document.querySelector('#filter-area').style.display = 'none';
            document.querySelector('#modal-overlay').style.display = 'none';
        });

        document.querySelector('#clear-filter').addEventListener('click', () => {
            const checkboxes = document.querySelectorAll('.filter-content input[type="checkbox"]');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        document.querySelector('#apply-filter').addEventListener('click', () => {
            const filters = {
                workload: [],
                certificate: {},
                lang: {},
                learningpath: []
            };

            const workloadCheckboxes = document.querySelectorAll('.filter-content-workload-column input[type="checkbox"]:checked');
            workloadCheckboxes.forEach(checkbox => {
                filters.workload.push(parseInt(checkbox.value));
            });

            const certificateCheckboxes = document.querySelectorAll('.filter-content-certificate-column input[type="checkbox"]:checked');
            certificateCheckboxes.forEach(checkbox => {
                filters.certificate[checkbox.value] = true;
            });

            const langCheckboxes = document.querySelectorAll('.filter-content-lang-column input[type="checkbox"]:checked');
            langCheckboxes.forEach(checkbox => {
                filters.lang[checkbox.value] = true;
            });

            const learningpathCheckboxes = document.querySelectorAll('.filter-content-learningpath-column input[type="checkbox"]:checked');
            learningpathCheckboxes.forEach(checkbox => {
                filters.learningpath.push(checkbox.value);
            });

            console.log('Filtros aplicados:', filters);
        });

        window.addEventListener('load', () => {
            document.querySelector('[role="main"]').style.paddingLeft = '0';
            document.querySelector('[role="main"]').style.paddingRight = '0';
        });

        return {
            init: (requestUrl) => {
                url = requestUrl;
                loadCourses(currentPage, limit);
            }
        };
    }
);
