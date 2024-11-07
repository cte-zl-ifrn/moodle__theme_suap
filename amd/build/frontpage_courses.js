define([["core_user/repository"]], function (RepositoryUser) {
    let url = '';
    let currentPage = 0;
    const limit = 8;
    let totalCourses = 0;

    const courseArea = document.querySelector('.course-area');
    const searchInput = document.querySelector('#search');
    const paginationNumbers = document.querySelector('#pagination-numbers');
    const prevPageButton = document.querySelector('#prev-page');
    const nextPageButton = document.querySelector('#next-page');

    async function loadCourses() {
        try {
            const filters = getFilter();
            const queryParams = `page=${currentPage}&limit=${limit}&search=${searchInput.value}&workload=${filters.workload}&certificate=${filters.certificate}&lang=${filters.lang}&learningpath=${filters.learningpath}`;
            const response = await fetch(`${url}?${queryParams}`).catch(error => console.error('Error fetching courses:', error));
            
            const { total, courses, baseurl } = await response.json();
            totalCourses = total;

            courseArea.innerHTML = '';

            courses.forEach(course => {
                const certificateArea = course.has_certificate !== "Sim" ? '' : `
                    <div class="course-certificate">
                        <p class="course-certificate-text">Certificado</p>
                        <span class="course-certificate-value"><img src="${baseurl}/theme/suap/pix/checkmark-circle-outline.svg" alt=""></span>
                    </div>
                `;
                const langArea = !course.lang ? '' : `
                    <div class="course-lang">
                        <p class="course-lang-text">Idioma</p>
                        <span class="course-lang-value">${selectLangFlag(course.lang)}</span>
                    </div>
                `;
                const workloadArea = !course.workload ? '' : `
                    <div class="course-workload">
                        <p class="course-workload-text">Carga Horária</p>
                        <span class="course-workload-value">${course.workload + " horas"}</span>
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
                            ${workloadArea}
                            ${certificateArea}
                            ${langArea}
                        </div>
                    </div>
                `;
            });

            correctMainPadding();
            updatePaginationButtons();
        } catch (error) {
            console.error('Error fetching courses:', error);
        }
    }

    function updatePaginationButtons() {
        paginationNumbers.innerHTML = '';
        prevPageButton.disabled = currentPage === 0;
        nextPageButton.disabled = (currentPage + 1) * limit >= totalCourses;

        const totalPages = Math.ceil(totalCourses / limit);
        const pageRange = 2;
        let startPage = Math.max(0, currentPage - pageRange);
        let endPage = Math.min(totalPages - 1, currentPage + pageRange);

        if (startPage > 2) {
            createPageButton(0);
            createPageButton(1);
            paginationNumbers.innerHTML += '... ';
        }

        for (let i = startPage; i <= endPage; i++) {
            createPageButton(i);
        }

        if (endPage < totalPages - 3) {
            paginationNumbers.innerHTML += ' ...';
            createPageButton(totalPages - 2);
            createPageButton(totalPages - 1);
        }

        nextPageButton.setAttribute('page', currentPage + 1);
        prevPageButton.setAttribute('page', currentPage - 1);
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
            loadCourses();
            updateActivePageButton();
        });

        paginationNumbers.appendChild(button);
    }

    function selectLangFlag(lang) {
        switch (lang) {
            case 'pt_br': return '🇧🇷';
            case 'en': return '🇺🇸';
            case 'es': return '🇪🇸';
            default: return '🌐';
        }
    }

    function updateActivePageButton() {
        document.querySelectorAll('.pagination-number').forEach(button => button.classList.remove('active'));
        const activeButton = document.querySelector(`.pagination-number:nth-child(${currentPage + 1})`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    function getFilter() {
        const filters = { workload: [], certificate: [], lang: [], learningpath: [] };

        document.querySelectorAll('.filter-content-workload-column input[type="checkbox"]:checked').forEach(checkbox => filters.workload.push(checkbox.value));
        filters.workload = filters.workload.join(',');

        document.querySelectorAll('.filter-content-certificate-column input[type="checkbox"]:checked').forEach(checkbox => filters.certificate.push(checkbox.value));
        filters.certificate = filters.certificate.join(',');

        document.querySelectorAll('.filter-content-lang-column input[type="checkbox"]:checked').forEach(checkbox => filters.lang.push(checkbox.value));
        filters.lang = filters.lang.join(',');

        document.querySelectorAll('.filter-content-learningpath-column input[type="checkbox"]:checked').forEach(checkbox => filters.learningpath.push(checkbox.value));
        filters.learningpath = filters.learningpath.join(',');

        return filters;
    }

    function updateFilterBadge() {
        const filters = getFilter();
        let totalFilters = Object.values(filters).reduce((acc, val) => acc + (val ? val.split(',').length : 0), 0);

        const badge = document.querySelector('.filter-badge');
        badge.style.display = totalFilters > 0 ? 'inline-block' : 'none';
        badge.innerHTML = totalFilters > 0 ? totalFilters : '';
    }

    function closeFilter() {
        document.querySelector('#filter-area').style.display = 'none';
        document.querySelector('#modal-overlay').style.display = 'none';
    }

    function correctMainPadding() {
        const main = document.querySelector('[role="main"]');
        main.style.paddingLeft = '0';
        main.style.paddingRight = '0';
    }

    searchInput.addEventListener('input', loadCourses);

    prevPageButton.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            loadCourses();
        }
    });

    nextPageButton.addEventListener('click', () => {
        if ((currentPage + 1) * limit < totalCourses) {
            currentPage++;
            loadCourses();
        }
    });

    document.querySelector('#filter-courses').addEventListener('click', () => {
        document.querySelector('#filter-area').style.display = 'block';
        document.querySelector('#modal-overlay').style.display = 'block';
    });

    document.querySelector('#clear-filter').addEventListener('click', () => {
        document.querySelectorAll('.filter-content input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        updateFilterBadge();
    });

    document.querySelector('#apply-filter').addEventListener('click', () => {
        currentPage = 0;
        loadCourses();
        closeFilter();
        updateFilterBadge();
    });

    document.querySelector('.counter-toggler').addEventListener('click', () => {
        const div = document.querySelector('#sidebar-area');
        if (div.classList.contains('sidebar-opened')) {
            div.className = 'sidebar-closed';
        } else {
            div.className = 'sidebar-opened';
        }

        // TODO: Salvar preferência do usuário corretamente
        // if(body.classList.contains('counter-close')) {
        //     RepositoryUser.setUserPreference('theme_suap_counter_close', true);
        // } else {
        //     RepositoryUser.setUserPreference('theme_suap_counter_close', false);
        // }
    });

    window.addEventListener('load', correctMainPadding);

    return {
        init: (requestUrl) => {
            url = requestUrl;
            loadCourses();
        }
    };
});

