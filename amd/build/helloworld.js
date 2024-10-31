define([], 
    function () {
        const wsfunction = 'core_course_search_courses';
        const moodlewsrestformat = 'json';
        const criterianame = 'tagid';
        const criteriavalue = '1';
        let currentPage = 0;
        let perPage = 1;
        let url = '';
        let token = '';
        let totalCourses = 0;

        async function loadCourses(page, perPage) {
            try {
                const searchParams = new URLSearchParams({
                    wstoken: token,
                    wsfunction: wsfunction,
                    moodlewsrestformat: moodlewsrestformat,
                    criterianame: criterianame,
                    criteriavalue: criteriavalue,
                    page: page,
                    perpage: perPage
                });

                const response = await fetch(`${url}?${searchParams.toString()}`).then(response => {
                    console.log(response);
                    return response;
                });
                const data = await response.json();
                console.log(data);
                
                totalCourses = data.total;
                const courses = data.courses;

                const courseArea = document.querySelector('.course-area');
                courseArea.innerHTML = '';

                courses.forEach(course => {
                    courseArea.innerHTML += `
                        <div class="course-card">
                            <div class="course-image-container">
                                <img src="${course.courseimage}" alt="${course.fullname}" class="course-image">
                            </div>
                            <span class="course-category">${course.categoryname}</span>
                            <p class="course-name">${course.fullname}</p>
                        </div>
                    `;
                });

                updatePaginationButtons(totalCourses);
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
            next.disabled = (currentPage + 1) * perPage >= total;

            const totalPages = Math.ceil(total / perPage);
            const pageRange = 2; // Número de páginas visíveis antes e depois da atual

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
                loadCourses(currentPage, perPage);
                updateActivePageButton();
            });

            document.querySelector('#pagination-numbers').appendChild(button);
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
                loadCourses(currentPage, perPage);
            }
        });

        document.querySelector('#next-page').addEventListener('click', () => {
            if ((currentPage + 1) * perPage < totalCourses) {
                currentPage++;
                loadCourses(currentPage, perPage);
            } else {
                console.log('No more courses to load');
            }
        });

        window.addEventListener('load', () => {
            document.querySelector('[role="main"]').style.paddingLeft = '0';
            document.querySelector('[role="main"]').style.paddingRight = '0';
        });

        return {
            init: (requestUrl, requestToken) => {
                url = requestUrl;
                token = requestToken;
                loadCourses(currentPage, perPage);
            }
        };
    }
);
